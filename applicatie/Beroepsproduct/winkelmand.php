<?php
require_once 'functies/db_connectie.php';
require_once 'functies/bestellingVerwerken.php';

session_start();

if (!isset($_SESSION['username'])) {
    header('Location: inloggen.php'); // Doorverwijzen naar de inlogpagina
    exit;
}


$clientUsername = $_SESSION['username'];  // Haal de gebruikersnaam op uit de sessie

// Start winkelmand als sessie niet is ingesteld
if (!isset($_SESSION['winkelmand'])) {
    $_SESSION['winkelmand'] = [];
}

// Verkrijg de klantgegevens uit de database (gebruikersnaam wordt gebruikt om te zoeken)
try {
    $db = maakVerbinding();
    $query = "SELECT username, address FROM users WHERE username = :clientUsername";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':clientUsername', $clientUsername, PDO::PARAM_STR);
    $stmt->execute();

    // Haal de klantgegevens op
    $clientData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($clientData) {
        $clientName = $clientData['username'];  // Klantnaam
        $clientAddress = $clientData['address'];  // Klantadres
    } else {
        echo "Klantgegevens niet gevonden.";
        exit;
    }
} catch (PDOException $e) {
    echo "Fout bij het ophalen van klantgegevens: " . $e->getMessage();
    exit;
}

// Verwerk toevoegen aan winkelmand
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product'], $_POST['prijs'], $_POST['aantal'])) {
    $productNaam = $_POST['product'];
    $productPrijs = floatval($_POST['prijs']);
    $productAantal = intval($_POST['aantal']);

    // Controleer of product al in de winkelmand zit
    $productBestaat = false;
    foreach ($_SESSION['winkelmand'] as &$item) {
        if ($item['naam'] === $productNaam) {
            $productBestaat = true;
            $item['aantal'] += $productAantal;
            break;
        }
    }

    if (!$productBestaat) {
        $_SESSION['winkelmand'][] = [
            'id' => count($_SESSION['winkelmand']),
            'naam' => $productNaam,
            'prijs' => $productPrijs,
            'aantal' => $productAantal
        ];
    }

    header('Location: winkelmand.php');
    exit;
}

// Verwerk verwijderverzoek
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verwijder'])) {
    $index = intval($_POST['verwijder']);
    if (isset($_SESSION['winkelmand'][$index])) {
        unset($_SESSION['winkelmand'][$index]);
        $_SESSION['winkelmand'] = array_values($_SESSION['winkelmand']);
    }
    header('Location: winkelmand.php');
    exit;
}

// Verwerk bestelling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bestel'])) {
    // Haal het adres op uit de database voor de ingelogde gebruiker
    $db = maakVerbinding();
    $query = "SELECT address FROM users WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
    $stmt->execute();

    // Haal het resultaat op
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Controleer of het adres bestaat
    if ($user && isset($user['address'])) {
        $address = $user['address'];  // Haal het adres uit de database
    } else {
        echo "<p>Fout: Het adres van de gebruiker kan niet worden gevonden.</p>";
        exit;
    }

    // Sla de winkelmand op als bestelling
    if (!isset($_SESSION['bestellingen'])) {
        $_SESSION['bestellingen'] = [];
    }
    $_SESSION['bestellingen'][] = $_SESSION['winkelmand'];

    // Haal gegevens van de ingelogde gebruiker op
    $clientUsername = $_SESSION['username'];
    $clientName = $clientData['username'];  // Klantnaam
    $personnelUsername = 'sahar';  // Vaste medewerkerusername
    $orderDatetime = date('Y-m-d H:i:s');
    $status = '3';  // Dit kan later dynamisch worden aangepast (bijv. in afwachting, verzonden, etc.)

    // Verbind met de database
    try {
        $query = "INSERT INTO pizza_order (client_username, client_name, personnel_username, datetime, status, address) 
                  VALUES (:client_username, :client_name, :personnel_username, :datetime, :status, :address)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':client_username', $clientUsername);
        $stmt->bindParam(':client_name', $clientName);
        $stmt->bindParam(':personnel_username', $personnelUsername);
        $stmt->bindParam(':datetime', $orderDatetime);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':address', $address);
        $stmt->execute();

        // Leeg winkelmand
        $_SESSION['winkelmand'] = [];

        // Doorverwijzen op basis van de rol van de gebruiker
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] === 'Personnel') {
                header('Location: bestellingsOverzicht.php');
            } elseif ($_SESSION['role'] === 'Client') {
                header('Location: profiel.php');
            }
        } else {
            // Als de rol onbekend is, terug naar de homepage
            header('Location: index.php');
        }
    } catch (PDOException $e) {
        echo "Fout tijdens het verwerken van de bestelling: " . $e->getMessage();
        exit;
    }
    exit;
}

// Hier wordt het totaal gerekend
$totaal = 0;
foreach ($_SESSION['winkelmand'] as $item) {
    $totaal += $item['prijs'] * $item['aantal'];
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winkelmand - Pizzeria Sole Machina</title>
    <link rel="stylesheet" href="css/bb.css" />
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="css/les03_grid.css" />
    <link rel="stylesheet" href="css/tijdelijke.css" />
    <link rel="stylesheet" href="css/nw.css" />
</head>
<body>

    <?php
    include_once('functies/header.php');
    include_once('functies/navbar.php');
    ?>

    <main>
        <div class="winkelmand-container">
            <h2>Winkelmand</h2>
            <form method="POST">
                <?php if (!empty($_SESSION['winkelmand'])): ?>
                    <?php foreach ($_SESSION['winkelmand'] as $index => $item): ?>
                        <div class="winkelmand-item">
                            <div class="item-details">
                                <h3><?php echo htmlspecialchars($item['naam']); ?></h3>
                                <p>Prijs: €<?php echo number_format($item['prijs'], 2, ',', '.'); ?></p>
                                <p>Aantal: <?php echo $item['aantal']; ?></p>
                            </div>
                            <!-- Verwijderknop -->
                            <button type="submit" name="verwijder" value="<?php echo $index; ?>" class="verwijder-knop">Verwijderen</button>
                        </div>
                    <?php endforeach; ?>

                    <div class="winkelmand-totaal">
                        <h3>Totaal: €<?php echo number_format($totaal, 2, ',', '.'); ?></h3>
                    </div>

                    <div class="bestel-knop-container">
                        <button type="submit" name="bestel" class="bestel-knop">Bestel</button>
                    </div>
                <?php else: ?>
                    <p>Je winkelmand is leeg.</p>
                <?php endif; ?>
            </form>
        </div>
    </main>

    <?php include_once('functies/footer.php'); ?>

</body>
</html>

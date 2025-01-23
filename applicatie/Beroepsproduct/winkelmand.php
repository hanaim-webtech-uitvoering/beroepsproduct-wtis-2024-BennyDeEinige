<?php
require_once 'functies/db_connectie.php';
require_once 'functies/bestellingVerwerken.php';

session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['username'])) {
    header('Location: inloggen.php');
    exit;
}

// Initialiseer winkelmand
if (!isset($_SESSION['winkelmand'])) {
    $_SESSION['winkelmand'] = [];
}

// Haal de gebruikersnaam op uit de sessie
$clientUsername = $_SESSION['username'];

// Verbind met de database
$db = maakVerbinding();

// Haal klantgegevens op
$clientData = haalKlantgegevens($db, $clientUsername);
if (!$clientData) {
    echo "Klantgegevens niet gevonden.";
    exit;
}

// Verwerk toevoegen aan winkelmand
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product'], $_POST['prijs'], $_POST['aantal'])) {
    voegToeAanWinkelmand($_SESSION['winkelmand'], $_POST['product'], $_POST['prijs'], $_POST['aantal']);
    header('Location: winkelmand.php');
    exit;
}

// Verwerk verwijderverzoek
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verwijder'])) {
    verwijderUitWinkelmand($_SESSION['winkelmand'], intval($_POST['verwijder']));
    header('Location: winkelmand.php');
    exit;
}

// Verwerk bestelling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bestel'])) {
    if (verwerkBestelling($db, $clientData)) {
        $_SESSION['winkelmand'] = []; // Leeg de winkelmand
        header('Location: ' . bepaalRedirect($_SESSION['role'] ?? null));
        exit;
    } else {
        echo "Fout tijdens het verwerken van de bestelling.";
        exit;
    }
}

// Bereken totaal
$totaal = berekenTotaal($_SESSION['winkelmand']);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzeria Sole Machina</title>
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

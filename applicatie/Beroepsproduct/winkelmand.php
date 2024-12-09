<?php
require_once 'functies/db_connectie.php';
session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['username'])) {
    header('Location: inloggen.php');
    exit;
}

// Start winkelmand als sessie niet is ingesteld
if (!isset($_SESSION['winkelmandje'])) {
    $_SESSION['winkelmandje'] = [];
}

// Verwerk toevoegen aan winkelmand
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product'], $_POST['prijs'], $_POST['aantal'])) {
    $productNaam = $_POST['product'];
    $productPrijs = floatval($_POST['prijs']);
    $productAantal = intval($_POST['aantal']);

    // Controleer of product al in de winkelmand zit
    $productBestaat = false;
    foreach ($_SESSION['winkelmandje'] as $item) {
        if ($item['naam'] === $productNaam) {
            $productBestaat = true;
            $_SESSION['winkelmandje'][$item['id']]['aantal'] += $productAantal;
            break;
        }
    }

    if (!$productBestaat) {
        $_SESSION['winkelmandje'][] = [
            'id' => count($_SESSION['winkelmandje']),
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
    if (isset($_SESSION['winkelmandje'][$index])) {
        unset($_SESSION['winkelmandje'][$index]);
        $_SESSION['winkelmandje'] = array_values($_SESSION['winkelmandje']);
    }
    header('Location: winkelmand.php');
    exit;
}

// Bereken totaal
$totaal = 0;
foreach ($_SESSION['winkelmandje'] as $item) {
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
            <h2>Winkelmandje</h2>

            <!-- Formulier om product toe te voegen aan winkelmand -->
            <!-- <form method="POST">
                <div>
                    <input type="text" name="product" placeholder="Product naam" required>
                    <input type="number" name="prijs" placeholder="Prijs" step="0.01" required>
                    <input type="number" name="aantal" placeholder="Aantal" min="1" required>
                    <button type="submit">Toevoegen</button>
                </div>
            </form> -->

            <form method="POST">
                <?php if (!empty($_SESSION['winkelmandje'])): ?>
                    <?php foreach ($_SESSION['winkelmandje'] as $index => $item): ?>
                        <div class="winkelmand-item">
                            <div class="item-details">
                                <h3><?php echo htmlspecialchars($item['naam']); ?></h3>
                                <p>Prijs: €<?php echo number_format($item['prijs'], 2, ',', '.'); ?></p>
                                <label>
                                    Aantal:
                                    <input type="number" name="aantal[<?php echo $index; ?>]" value="<?php echo $item['aantal']; ?>" class="aantal-input" min="1">
                                </label>
                            </div>
                            <!-- Verwijderknop -->
                            <button type="submit" name="verwijder" value="<?php echo $index; ?>" class="verwijder-knop">Verwijderen</button>
                        </div>
                    <?php endforeach; ?>

                    <div class="winkelmand-totaal">
                        <h3>Totaal: €<?php echo number_format($totaal, 2, ',', '.'); ?></h3>
                    </div>
                <?php else: ?>
                    <p>Je winkelmandje is leeg.</p>
                <?php endif; ?>
            </form>
        </div>
    </main>

    <?php
        include_once('functies/footer.php');
    ?>
</body>
</html>

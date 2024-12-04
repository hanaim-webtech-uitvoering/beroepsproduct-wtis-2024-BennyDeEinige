<?php
require_once 'functies/db_connectie.php';

session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user'])) {
    // Niet ingelogd: stuur ze naar de inlogpagina
    header("Location: inloggen.php");
}

// Start de sessie om het winkelmandje op te slaan
if (!isset($_SESSION['winkelmandje'])) {
    $_SESSION['winkelmandje'] = [];
}

// Verwerk formulier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['aantal'])) {
        // Werk hoeveelheden bij
        foreach ($_POST['aantal'] as $index => $nieuwAantal) {
            $_SESSION['winkelmandje'][$index]['aantal'] = max(1, intval($nieuwAantal));
        }
    }

    if (isset($_POST['verwijder'])) {
        // Verwijder een item uit het winkelmandje
        $index = intval($_POST['verwijder']);
        unset($_SESSION['winkelmandje'][$index]);
        $_SESSION['winkelmandje'] = array_values($_SESSION['winkelmandje']); // Herindexeer de array
    }
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
                            <button type="submit" name="verwijder" value="<?php echo $index; ?>" class="verwijder-knop">Verwijderen</button>
                        </div>
                    <?php endforeach; ?>

                    <div class="winkelmand-totaal">
                        <h3>Totaal: €<?php echo number_format($totaal, 2, ',', '.'); ?></h3>
                        <button type="submit" class="afrekenen">Winkelmandje bijwerken</button>
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

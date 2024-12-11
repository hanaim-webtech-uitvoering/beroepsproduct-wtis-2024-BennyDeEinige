<?php
require_once 'functies/db_connectie.php';
session_start();

// // Controleer of de gebruiker is ingelogd
// if (!isset($_SESSION['username'])) {
//     header('Location: inloggen.php');
//     exit;
// }
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
        <div class="button-container">
            <form action="medewerkerOverzicht.php" method="get">
                <button type="submit">Ga naar Medewerker Overzicht</button>
            </form>
            <form action="bestellingsOverzicht.php" method="get">
                <button type="submit">Ga naar Bestellings Overzicht</button>
            </form>
        </div>
    </main>

    <?php
        include_once('functies/footer.php');
    ?>
</body>
</html>

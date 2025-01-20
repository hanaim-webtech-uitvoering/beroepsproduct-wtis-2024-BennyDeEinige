<?php
require_once 'functies/db_connectie.php';
require_once 'functies/getUserOrders.php';
require_once 'functies/statusInText.php';

session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['username'])) {
    header("Location: inloggen.php");

}

$username = $_SESSION['username']; // Haal de gebruikersnaam op uit de sessie

// Haal de bestellingen op
$bestellingen = getUserOrders($username); 

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzeria Sole Machina</title>
    <link rel="stylesheet" href="css/bb.css">
    <link rel="stylesheet" href="css/nw.css" />
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="css/les03_grid.css" />
    <link rel="stylesheet" href="css/tijdelijke.css" />
</head>
<body>
  <?php 
    include_once('functies/header.php'); 
    include_once('functies/navbar.php'); 
  ?>

  <div class="container">
    <h2>Welkom, <?php echo htmlspecialchars($username); ?>!</h2>

    <h3>Jouw Bestellingen</h3>
    <?php if (!empty($bestellingen)): ?>
        <table>
            <thead>
                <tr>
                    <th>Bestelling ID</th>
                    <th>Datum</th>
                    <th>Status</th>
                    <th>Adres</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bestellingen as $bestelling): ?>
                    <tr>
                        <td><?php echo ($bestelling['order_id']); ?></td>
                        <td><?php echo ($bestelling['datetime']); ?></td>
                        <td><?php echo ($bestelling['status']); ?></td>
                        <td><?php echo ($bestelling['address']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Je hebt nog geen bestellingen geplaatst.</p>
    <?php endif; ?>
    
    <p>Dit is jouw profielpagina.</p>
    <a href="functies/logout.php">Uitloggen</a>
  </div>

  <?php include_once('functies/footer.php'); ?>
</body>
</html>

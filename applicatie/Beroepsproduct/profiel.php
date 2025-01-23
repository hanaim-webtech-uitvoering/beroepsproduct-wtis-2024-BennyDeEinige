<?php
require_once 'functies/db_connectie.php';
require_once 'functies/getUserOrders.php';
require_once 'functies/statusInText.php'; // Zorg ervoor dat deze functie is inbegrepen

session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['username'])) {
    header("Location: inloggen.php");
    exit;
}

$username = $_SESSION['username']; // Haal de gebruikersnaam op uit de sessie

// Haal de bestellingen op voor de ingelogde gebruiker
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
                    <?php
                    // Zet de status om naar leesbare tekst
                    $statusText = getStatusText($bestelling['status']);
                    ?>
                    <tr>
                        <td><a href="bestellingDetail.php?order_id=<?php echo $bestelling['order_id']; ?>"><?php echo $bestelling['order_id']; ?></a></td>
                        <td><?php echo isset($bestelling['datetime']) ? htmlspecialchars($bestelling['datetime']) : 'N/A'; ?></td> <!-- Controleer of de waarde bestaat -->
                        <td><?php echo ($bestelling['status']); ?></td> <!-- Zet status om naar tekst -->
                        <td><?php echo isset($bestelling['address']) ? htmlspecialchars($bestelling['address']) : 'N/A'; ?></td> <!-- Controleer of de waarde bestaat -->
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

<?php
require_once 'functies/db_connectie.php';

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: inloggen.php");
    exit();
}

$username = $_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profiel - Pizzeria Sole Machina</title>
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
    <p>Dit is jouw profielpagina.</p>
    <a href="functies/logout.php">Uitloggen</a>
</div>

<?php include_once('functies/footer.php'); ?>
</body>
</html>

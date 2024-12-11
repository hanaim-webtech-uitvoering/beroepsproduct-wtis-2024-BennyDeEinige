<?php
require_once 'functies/db_connectie.php';
require_once 'functies/loginMedewerker.php';
session_start();

// Als de gebruiker al is ingelogd, doorsturen naar medewerkerOverzicht.php
if (isset($_SESSION['username'])) {
    header("Location: bestellingsOverzicht.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['wachtwoord'];

    // Loginfunctie aanroepen
    $loginResult = loginMedewerker($username, $password);

    if ($loginResult === true) {
        // Login succesvol, doorsturen naar medewerkerOverzicht.php
        header("Location: bestellingsOverzicht.php");
        exit;
    } else {
        // Foutmelding tonen
        $error = $loginResult;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EindOpdracht Pizzeria Sole Machina</title>
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

  <div class="container-sven">
    <form class="login-form" method="post">
      <h2>Medewerker Inloggen</h2>
      <?php if ($error): ?>
        <p><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>
        <div>
            <input type="text" name="username" placeholder="Gebruikersnaam" required>
        </div>
        <div>
            <input type="password" name="wachtwoord" placeholder="Wachtwoord" required>
        </div>
      <button type="submit" name="login">Inloggen</button>
    </form>
  </div>

  <?php include_once('functies/footer.php');?>
</body>
</html>

<?php
require_once 'functies/db_connectie.php';
require_once 'functies/loginfunctie.php';
session_start();

// Als er is ingelogd wordt de gebruiker doorgestuurd naar profuel.php
if (isset($_SESSION['username'])) {
    header("Location: profiel.php");
    exit();
}

// Het maken van een melding die eventueel gevuld kan worden
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['wachtwoord'];

    // Loginfunctie aanroepen
    $loginResult = loginUser($username, $password);

    if ($loginResult === true) {
        // Login succesvol, doorsturen naar profielpagina
        header("Location: profiel.php");
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
    <meta charset="UTF-8">
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

<div class="container-sven">
    <form class="login-form" method="post">
        <h2>Klant Inloggen</h2>
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

<?php include_once('functies/footer.php'); ?>
</body>
</html>


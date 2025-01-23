<?php
require_once('functies/db_connectie.php');
require_once('functies/registratie_verwerking.php');

$error = '';  
$success = ''; 

// Controleer of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Haal de gegevens op
    $data = [
        'first_name' => htmlspecialchars(trim($_POST['first_name'] ?? '')),
        'last_name' => htmlspecialchars(trim($_POST['last_name'] ?? '')),
        'address' => htmlspecialchars(trim($_POST['address'] ?? '')),
        'username' => htmlspecialchars(trim($_POST['username'] ?? '')),
        'password' => $_POST['password'] ?? ''
    ];

    // Verwerk de registratie
    $result = registreerGebruiker($data);

    if ($result === true) {
        $success = "Je bent succesvol geregistreerd! Je kunt nu inloggen.";
    } else {
        $error = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzeria Sole Machina</title>
    <link rel="stylesheet" href="css/bb.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/les03_grid.css">
    <link rel="stylesheet" href="css/tijdelijke.css">
    <link rel="stylesheet" href="css/nw.css">
</head>
<body>

    <?php include_once('functies/header.php'); ?>
    <?php include_once('functies/navbar.php'); ?>

    <div class="registration-form">
        <h2>Registreren</h2>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="registratie.php" method="post">
            <label for="first_name">Voornaam</label>
            <input type="text" id="first_name" name="first_name" placeholder="Voornaam" value="<?php echo htmlspecialchars($data['first_name'] ?? ''); ?>" required><br>

            <label for="last_name">Achternaam</label>
            <input type="text" id="last_name" name="last_name" placeholder="Achternaam" value="<?php echo htmlspecialchars($data['last_name'] ?? ''); ?>" required><br>

            <label for="address">Adres</label>
            <input type="text" id="address" name="address" placeholder="Adres" value="<?php echo htmlspecialchars($data['address'] ?? ''); ?>" required><br>

            <label for="username">Gebruikersnaam</label>
            <input type="text" id="username" name="username" placeholder="Gebruikersnaam" value="<?php echo htmlspecialchars($data['username'] ?? ''); ?>" required><br>

            <label for="password">Wachtwoord</label>
            <input type="password" id="password" name="password" placeholder="Wachtwoord" required><br>

            <button type="submit">Registreren</button>
        </form>
    </div>

    <?php include_once('functies/footer.php'); ?>

</body>
</html>

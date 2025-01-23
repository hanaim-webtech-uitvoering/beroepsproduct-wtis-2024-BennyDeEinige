<?php
require_once 'functies/db_connectie.php'; 
require_once 'functies/bestellingen.php'; 
require_once 'functies/statusInText.php'; 

session_start();
$db = maakVerbinding(); 

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Personnel') {
    header('Location: inloggen.php');
    exit;
}

// Haal de recente bestellingen op
$query = 'SELECT order_id, client_username, client_name, personnel_username, datetime, status, address FROM Pizza_order ORDER BY datetime DESC';
$stmt = $db->prepare($query);

// Controleer of de query succesvol is uitgevoerd
$queryResult = $stmt->execute();
if (!$queryResult) {
    echo "Er is een probleem met het ophalen van de bestellingen. Probeer het later opnieuw.";
    exit;
}

// Haal de bestellingen op
$orders = $stmt->fetchAll();
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

<?php include_once('functies/header.php'); ?>
<?php include_once('functies/navbar.php'); ?>

<main>
    <h2>Overzicht van Bestellingen</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Klant Username</th>
                <th>Klant Naam</th>
                <th>Medewerker Username</th>
                <th>Datum</th>
                <th>Status</th>
                <th>Adres</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Controleer of er bestellingen zijn en toon deze in de tabel
            if (count($orders) > 0) {
                foreach ($orders as $order) {
                    // Zet de status om naar tekst
                    $statusText = getStatusText($order['status']); 
                    echo "<tr>
                            <td><a href='bestellingDetail.php?order_id={$order['order_id']}'>{$order['order_id']}</a></td>
                            <td>{$order['client_username']}</td>
                            <td>{$order['client_name']}</td>
                            <td>{$order['personnel_username']}</td>
                            <td>{$order['datetime']}</td>
                            <td>{$statusText}</td>  <!-- Toon de status als tekst -->
                            <td>{$order['address']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Geen bestellingen gevonden.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</main>

<?php include_once('functies/footer.php'); ?>

</body>
</html>

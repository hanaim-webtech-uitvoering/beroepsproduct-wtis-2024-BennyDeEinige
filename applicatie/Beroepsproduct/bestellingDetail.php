<?php
require_once 'functies/db_connectie.php'; // Zorg ervoor dat dit bestand de juiste databaseverbinding bevat

session_start();

// Controleer of de gebruiker is ingelogd en personeel is
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Personnel') {
    header('Location: inloggen.php');
    exit;
}

// Controleer of een order_id is meegegeven in de URL
if (!isset($_GET['order_id'])) {
    echo "Geen bestelling geselecteerd.";
    exit;
}

$order_id = $_GET['order_id'];

// Haal de bestelling op
try {
    $db = maakVerbinding(); // Maak verbinding met de database

    // SQL-query om de producten van een specifieke bestelling op te halen
    $query = 'SELECT product_name, quantity
              FROM Pizza_Order_Product
              WHERE order_id = :order_id';

    // Bereid en voer de query uit
    $stmt = $db->prepare($query);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();

    // Haal de producten op
    $orderProducts = $stmt->fetchAll();

    // Haal de orderdetails op van Pizza_order (optioneel)
    $queryOrder = 'SELECT * FROM Pizza_order WHERE order_id = :order_id';
    $stmtOrder = $db->prepare($queryOrder);
    $stmtOrder->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmtOrder->execute();
    $orderDetails = $stmtOrder->fetch();

} catch (Exception $e) {
    echo "Fout bij ophalen van de bestelling: " . $e->getMessage();
    exit;
}

// Functie om de status om te zetten naar tekst
function getStatusText($status) {
    switch ($status) {
        case 1:
            return "Ontvangen";
        case 2:
            return "Wordt voorbereid";
        case 3:
            return "Wordt bezorgd";
        default:
            return "Onbekend";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestelling Details</title>
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
    <h2>Details van Bestelling #<?php echo $order_id; ?></h2>
    
    <h3>Bestelgegevens</h3>
    <p><strong>Klant Username:</strong> <?php echo $orderDetails['client_username']; ?></p>
    <p><strong>Medewerker Username:</strong> <?php echo $orderDetails['personnel_username']; ?></p>
    <p><strong>Status:</strong> <?php echo getStatusText($orderDetails['status']); ?></p>  <!-- Toon de status als tekst -->
    <p><strong>Adres:</strong> <?php echo $orderDetails['address']; ?></p>
    <p><strong>Datum:</strong> <?php echo $orderDetails['datetime']; ?></p>
    
    <h3>Bestelde Producten</h3>
    <table>
        <thead>
            <tr>
                <th>Productnaam</th>
                <th>Hoeveelheid</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($orderProducts) > 0) {
                foreach ($orderProducts as $product) {
                    echo "<tr>
                            <td>{$product['product_name']}</td>
                            <td>{$product['quantity']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='2'>Geen producten gevonden voor deze bestelling.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</main>

<?php include_once('functies/footer.php'); ?>

</body>
</html>

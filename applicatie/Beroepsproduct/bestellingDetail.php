<?php
require_once 'functies/db_connectie.php'; 
require_once 'functies/statusInText.php'; 

session_start();

// Controleer of een order_id is meegegeven in de URL en controleer deze
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id']) || $_GET['order_id'] <= 0) {
    echo "Ongeldige bestelling geselecteerd.";
    exit;
}

$order_id = (int)$_GET['order_id'];
$db = maakVerbinding();

// Query om de producten van een specifieke bestelling op te halen
$query = "SELECT product_name, quantity FROM Pizza_Order_Product WHERE order_id = $order_id";
$queryResult = $db->query($query);

// Haal de resultaten op
$orderProducts = $queryResult->fetchAll();

// Haal de gegevens van de bestelling op van Pizza_order 
$queryOrder = 'SELECT * FROM Pizza_order WHERE order_id = :order_id';
$stmtOrder = $db->prepare($queryOrder);
// Direct parameter binden via execute
$stmtOrder->execute([':order_id' => $order_id]);
$orderDetails = $stmtOrder->fetch();

// Controleer of de bestelling bestaat
if (!$orderDetails) {
    echo "Bestelling niet gevonden.";
    exit;
}
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
    <h2>Details van Bestelling #<?php echo htmlspecialchars($order_id); ?></h2>
    
    <h3>Bestelgegevens</h3>
    <p><strong>Klant Username:</strong> <?php echo ($orderDetails['client_username']); ?></p>
    <p><strong>Medewerker Username:</strong> <?php echo htmlspecialchars($orderDetails['personnel_username']); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars(getStatusText($orderDetails['status'])); ?></p>  
    <p><strong>Adres:</strong> <?php echo htmlspecialchars($orderDetails['address']); ?></p>
    <p><strong>Datum:</strong> <?php echo htmlspecialchars($orderDetails['datetime']); ?></p>
    
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
                            <td>" . htmlspecialchars($product['product_name']) . "</td>
                            <td>" . htmlspecialchars($product['quantity']) . "</td>
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

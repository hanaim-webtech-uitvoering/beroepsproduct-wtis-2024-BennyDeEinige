<?php
require_once 'functies/statusInText.php'; // Zorg ervoor dat de statusInText functie wordt geladen

function getUserOrders($username) {
    // Maak verbinding met de database
    $db = maakVerbinding();

    // Query om de bestellingen van de specifieke gebruiker op te halen
    $query = "
        SELECT 
            order_id,
            datetime,
            status,
            address
        FROM 
            pizza_order
        WHERE 
            client_username = :username
        ORDER BY 
            datetime DESC
    ";

    $stmt = $db->prepare($query);
    $stmt->execute([':username' => $username]);


    // Haal de resultaten op
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Controleer of er resultaten zijn
    if (empty($result)) {
        error_log("Geen bestellingen gevonden voor gebruiker: $username");
    }

    // Zet de status om naar tekst voor elke bestelling
    foreach ($result as &$order) {
        $order['status'] = getStatusText($order['status']); // Zet status om naar tekst
    }

    return $result;
}
?>

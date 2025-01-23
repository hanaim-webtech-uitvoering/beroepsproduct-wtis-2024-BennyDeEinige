<?php
function verwerkBestelling($clientUsername, $personnelUsername, $orderDatetime, $status, $address, $winkelmand) {
    // Verbind met de database
    $db = maakVerbinding();

    if (empty($clientUsername) || empty($personnelUsername) || empty($orderDatetime) || empty($status) || empty($address)) {
        return "Alle velden zijn vereist.";
    }

    try {
        // Stap 1: Voeg de bestelling toe aan pizza_order
        $query = "INSERT INTO pizza_order (client_username, personnel_username, datetime, status, address) 
                  VALUES (:clientUsername, :personnelUsername, :orderDatetime, :status, :address)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':clientUsername', $clientUsername, PDO::PARAM_STR);
        $stmt->bindParam(':personnelUsername', $personnelUsername, PDO::PARAM_STR);
        $stmt->bindParam(':orderDatetime', $orderDatetime, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->execute();
        

        // Haal het gegenereerde order_id op
        $orderId = $db->lastInsertId();

        // Stap 2: Voeg producten toe aan pizza_order_product
        foreach ($winkelmand as $item) {
            $query = "INSERT INTO pizza_order_product (product_name, quantity, product_price) 
                      VALUES (:product_name, :quantity, :product_price)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':product_name', $item['naam']);
            $stmt->bindParam(':quantity', $item['aantal'], PDO::PARAM_INT);
            $stmt->bindParam(':product_price', $item['prijs']);
            $stmt->execute();
        }

        return true;  // Succesvolle bestelling
    } catch (PDOException $e) {
        return "Fout tijdens het verwerken van de bestelling: " . $e->getMessage();
    }
}

?>

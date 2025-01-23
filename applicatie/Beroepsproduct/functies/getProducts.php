<?php
function getProducts() {
    $conn = maakVerbinding();
    $sql = "
        SELECT * 
        FROM product
        ORDER BY type_id, name";

    $stmt = $conn->query($sql);
    // Controleer of de query goed is uitgevoerd
    if ($stmt) {
        $producten = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $producten;
    } else {
        // Foutmelding als de query niet uitgevoerd kan worden
        echo "Fout bij ophalen producten.";
        exit;
    }

}

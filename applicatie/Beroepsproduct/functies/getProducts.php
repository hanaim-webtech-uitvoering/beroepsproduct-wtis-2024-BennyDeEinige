<?php
function getProducts() {
    $conn = maakVerbinding();
    $sql = "
        SELECT * 
        FROM product
        ORDER BY type_id, name"; // Sorteer eerst per type_id, dan per naam

    try {
        $stmt = $conn->query($sql);
        $producten = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $producten;
    } catch (PDOException $e) {
        echo "Fout bij ophalen producten: " . $e->getMessage();
        exit;
    }
}

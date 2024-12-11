<?php
require_once 'functies/db_connectie.php';

function bestellingen()
{
    $db = maakVerbinding(); // Maak verbinding met de database

    // SQL-query om de meest recente bestellingen op te halen
    // We nemen aan dat je de juiste velden hebt in de `Pizza_order`-tabel
    $query = 'SELECT order_id, client_username, client_name, personnel_username, datetime, status, address 
              FROM Pizza_order 
              ORDER BY datetime DESC';

    // Bereid en voer de query uit
    $data = $db->prepare($query);
    $data->execute();

    // Zet de resultaten in een array
    $recentOrders = array();
    while ($row = $data->fetch()) {
        $orderId          = $row['order_id'];
        $clientUsername   = $row['client_username'];
        $clientName       = $row['client_name'];
        $personnelUsername = $row['personnel_username'];
        $orderDatetime    = $row['datetime'];
        $status           = $row['status'];
        $address          = $row['address'];

        // Voeg de gegevens toe aan het resultatenarray
        $recentOrders[] = array(
            'order_id' => $orderId,
            'client_username' => $clientUsername,
            'client_name' => $clientName,
            'personnel_username' => $personnelUsername,
            'datetime' => $orderDatetime,
            'status' => $status,
            'address' => $address
        );
    }

    // Retourneer de gevonden bestellingen
    return $recentOrders;
}
?>

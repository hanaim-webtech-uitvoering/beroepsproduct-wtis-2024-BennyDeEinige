<?php
require_once 'functies/db_connectie.php'; // Laad de bestand met database connectie functie

function bestellingen()
{
    $db = maakVerbinding(); // Maak verbinding met de database

    // SQL-query om de meest recente bestellingen op te halen
    // De query haalt gegevens op van de `Pizza_order` tabel, gesorteerd op de datum
    $query = 'SELECT order_id, client_username, client_name, personnel_username, datetime, status, address 
              FROM Pizza_order 
              ORDER BY datetime DESC';

    // Bereid en voer de query uit
    // De query wordt voorbereid en uitgevoerd met de databaseverbinding
    $data = $db->prepare($query);
    $data->execute();

    // Zet de resultaten in een array
    // Hier slaan we de resultaten van de query op in een array
    $recentOrders = array();

    // Terwijl we de gegevens uit de query ophalen, worden ze in de array geplaatst
    // De loop doorloopt elke rij van de queryresultaten
    while ($row = $data->fetch()) {
        // Haal specifieke waarden uit de huidige rij van de database
        // Haal de gegevens van de bestelling op, zoals het order_id, klantnaam, datum etc.
        $orderId          = $row['order_id'];
        $clientUsername   = $row['client_username'];
        $clientName       = $row['client_name'];
        $personnelUsername = $row['personnel_username'];
        $orderDatetime    = $row['datetime'];
        $status           = $row['status'];
        $address          = $row['address'];

        // Voeg de gegevens toe aan het resultatenarray
        // De verkregen gegevens worden opgeslagen in het $recentOrders array
        $recentOrders[] = array(
            'order_id' => $orderId,  // Sla het order_id op
            'client_username' => $clientUsername, // Sla de gebruikersnaam van de klant op
            'client_name' => $clientName, // Sla de naam van de klant op
            'personnel_username' => $personnelUsername, // Sla de gebruikersnaam van het personeel op
            'datetime' => $orderDatetime, // Sla de datum van de bestelling op
            'status' => $status, // Sla de status van de bestelling op
            'address' => $address // Sla het adres van de klant op
        );
    }

    // Retourneer de gevonden bestellingen
    // De verzamelde bestellingen worden teruggegeven als een array
    return $recentOrders;
}
?>

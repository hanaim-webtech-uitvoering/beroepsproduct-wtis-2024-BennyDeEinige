<?php
require_once 'functies/db_connectie.php';

// Verwerk toevoegen aan winkelmand
function voegToeAanWinkelmand(&$winkelmand, $productNaam, $productPrijs, $productAantal)
{
    $productBestaat = false;
    foreach ($winkelmand as &$item) {
        if ($item['naam'] === $productNaam) {
            $productBestaat = true;
            $item['aantal'] += intval($productAantal);
            break;
        }
    }
    if (!$productBestaat) {
        $winkelmand[] = [
            'id' => count($winkelmand),
            'naam' => $productNaam,
            'prijs' => floatval($productPrijs),
            'aantal' => intval($productAantal)
        ];
    }
}

// Verwijder een product uit de winkelmand
function verwijderUitWinkelmand(&$winkelmand, $index)
{
    if (isset($winkelmand[$index])) {
        unset($winkelmand[$index]);
        $winkelmand = array_values($winkelmand); // Herindexeren
    }
}

// Verwerk bestelling
function verwerkBestelling($db, $clientData)
{
    // Haal klantgegevens op om te controleren of de gebruiker bestaat
    $clientUsername = $clientData['username'];
    $klantGegevens = haalKlantgegevens($db, $clientUsername);

    // Als de klant niet bestaat, geef dan een foutmelding terug
    if (!$klantGegevens) {
        echo "De opgegeven klant bestaat niet.";
        return false;
    }

    // Stel de variabelen in voor de bestelling
    $clientName = $clientData['username'];  // Of een andere naam als nodig
    $personnelUsername = 'sahar'; // Vaste medewerker
    $orderDatetime = date('Y-m-d H:i:s');
    $status = '3'; // Status voorbeeld
    $address = $clientData['address'];

    // SQL-query voor het invoegen van de bestelling
    $query = "INSERT INTO pizza_order (client_username, client_name, personnel_username, datetime, status, address) 
              VALUES (:client_username, :client_name, :personnel_username, :datetime, :status, :address)";
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':client_username' => $clientUsername,
        ':client_name' => $clientName,
        ':personnel_username' => $personnelUsername,
        ':datetime' => $orderDatetime,
        ':status' => $status,
        ':address' => $address
    ]);

    // Probeer de bestelling in te voeren
    if ($stmt->rowCount()) {
        // Haal het laatst ingevoegde order_id op
        $order_id = $db->lastInsertId();

        // Voeg de producten toe aan de Pizza_Order_Product tabel
        foreach ($_SESSION['winkelmand'] as $item) {
            $query = "INSERT INTO Pizza_Order_Product (order_id, product_name, quantity) 
                      VALUES (:order_id, :product_name, :quantity)";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':order_id' => $order_id,
                ':product_name' => $item['naam'],
                ':quantity' => $item['aantal']
            ]);
        }

        return true;
    }

    return false;
}

// Haal klantgegevens op
function haalKlantgegevens($db, $clientUsername)
{
    $query = "SELECT username, address FROM users WHERE username = :clientUsername";
    $stmt = $db->prepare($query);
    $stmt->execute([':clientUsername' => $clientUsername]);

    if (!$stmt->execute()) {
        return null;
    }
    return $stmt->fetch(PDO::FETCH_ASSOC); // Dit retourneert de klantgegevens
}

// Bereken totaal
function berekenTotaal($winkelmand)
{
    return array_reduce($winkelmand, function ($carry, $item) {
        return $carry + ($item['prijs'] * $item['aantal']);
    }, 0);
}

// Functie om redirect-URL te bepalen
function bepaalRedirect($role)
{
    return match ($role) {
        'Personnel' => 'bestellingsOverzicht.php',
        'Client' => 'profiel.php',
        default => 'index.php',
    };
}
?>

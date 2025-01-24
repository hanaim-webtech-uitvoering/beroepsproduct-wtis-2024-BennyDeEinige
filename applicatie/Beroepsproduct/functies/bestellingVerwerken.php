<?php
require_once 'functies/db_connectie.php'; // Laad de bestand met de database connectie functie

// Verwerk toevoegen aan winkelmand
// Deze functie voegt een product toe aan de winkelmand of verhoogt het aantal als het product al bestaat
function voegToeAanWinkelmand(&$winkelmand, $productNaam, $productPrijs, $productAantal)
{
    $productBestaat = false;

    // Controleer of het product al in de winkelmand zit
    foreach ($winkelmand as &$item) {
        if ($item['naam'] === $productNaam) {
            $productBestaat = true;
            $item['aantal'] += intval($productAantal); // Verhoog het aantal van het product in de winkelmand
            break;
        }
    }

    // Als het product nog niet in de winkelmand zit, voeg het dan toe
    if (!$productBestaat) {
        $winkelmand[] = [
            'id' => count($winkelmand), // Stel een uniek id in voor het product
            'naam' => $productNaam, // Sla de naam van het product op
            'prijs' => floatval($productPrijs), // Sla de prijs van het product op
            'aantal' => intval($productAantal) // Sla het aantal van het product op
        ];
    }
}

// Verwijder een product uit de winkelmand
// Deze functie verwijdert een product op basis van zijn index uit de winkelmand
function verwijderUitWinkelmand(&$winkelmand, $index)
{
    if (isset($winkelmand[$index])) {
        unset($winkelmand[$index]); // Verwijder het product op de opgegeven index
        $winkelmand = array_values($winkelmand); // Herindexeer de winkelmand na het verwijderen van een item
    }
}

// Verwerk bestelling
// Deze functie verwerkt de bestelling door gegevens in de database op te slaan
function verwerkBestelling($db, $clientData)
{
    // Haal klantgegevens op om te controleren of de gebruiker bestaat
    $clientUsername = $clientData['username'];
    $klantGegevens = haalKlantgegevens($db, $clientUsername);

    // Als de klant niet bestaat, geef dan een foutmelding terug
    if (!$klantGegevens) {
        echo "De opgegeven klant bestaat niet."; // Toon foutmelding als klant niet gevonden is
        return false;
    }

    // Stel de variabelen in voor de bestelling
    $clientName = $clientData['username'];  // Of een andere naam als nodig
    $personnelUsername = 'sahar'; // Vaste medewerker voor deze bestelling
    $orderDatetime = date('Y-m-d H:i:s'); // Stel de datum van de bestelling in
    $status = '3'; // Stel de status van de bestelling in (bijvoorbeeld 3 voor verwerkte bestellingen)
    $address = $clientData['address']; // Het adres van de klant

    // SQL-query voor het invoegen van de bestelling in de Pizza_order tabel
    $query = "INSERT INTO pizza_order (client_username, client_name, personnel_username, datetime, status, address) 
              VALUES (:client_username, :client_name, :personnel_username, :datetime, :status, :address)";
    $stmt = $db->prepare($query);
    
    // Voer de query uit met de gegevens voor de bestelling
    $stmt->execute([
        ':client_username' => $clientUsername,
        ':client_name' => $clientName,
        ':personnel_username' => $personnelUsername,
        ':datetime' => $orderDatetime,
        ':status' => $status,
        ':address' => $address
    ]);

    // Probeer de bestelling in te voeren en controleer of de query is uitgevoerd
    if ($stmt->rowCount()) {
        // Haal het laatst ingevoegde order_id op
        $order_id = $db->lastInsertId(); // Haal het ID van de net toegevoegde bestelling op

        // Voeg de producten toe aan de Pizza_Order_Product tabel
        foreach ($_SESSION['winkelmand'] as $item) {
            $query = "INSERT INTO Pizza_Order_Product (order_id, product_name, quantity) 
                      VALUES (:order_id, :product_name, :quantity)";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':order_id' => $order_id,
                ':product_name' => $item['naam'], // Haal de naam van het product uit de winkelmand
                ':quantity' => $item['aantal'] // Haal de hoeveelheid van het product uit de winkelmand
            ]);
        }

        return true; // Retourneer true als de bestelling succesvol is verwerkt
    }

    return false; // Retourneer false als de bestelling niet succesvol is
}

// Haal klantgegevens op
// Deze functie haalt de klantgegevens op, zoals de gebruikersnaam en het adres van de klant
function haalKlantgegevens($db, $clientUsername)
{
    // Query om de gegevens van de klant op te halen op basis van de gebruikersnaam
    $query = "SELECT username, address FROM users WHERE username = :clientUsername";
    $stmt = $db->prepare($query);

    // Voer de query uit met de klantgebruikersnaam
    $stmt->execute([':clientUsername' => $clientUsername]);

    if (!$stmt->execute()) {
        return null; 
    }
    // Retourneer de klantgegevens als een associatieve array
    return $stmt->fetch(PDO::FETCH_ASSOC); 
}

// Deze functie berekent het totaalbedrag van de producten in de winkelmand
function berekenTotaal($winkelmand)
{
    return array_reduce($winkelmand, function ($carry, $item) {
         // Vermenigvuldig de prijs met het aantal en voeg toe aan de carry
        return $carry + ($item['prijs'] * $item['aantal']);
    }, 0); 
}


// Deze functie bepaalt de redirect URL op basis van de rol van de gebruiker
function bepaalRedirect($role)
{
    return match ($role) {
        'Personnel' => 'bestellingsOverzicht.php', 
        'Client' => 'profiel.php', 
        default => 'index.php', 
    };
}
?>

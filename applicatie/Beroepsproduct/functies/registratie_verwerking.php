<?php
require_once('functies/db_connectie.php');

function registreerGebruiker($data)
{
    // Verbind met de database
    $db = maakVerbinding();

    // Valideer invoer
    if (empty($data['first_name']) || empty($data['last_name']) || empty($data['address']) || empty($data['username']) || empty($data['password'])) {
        return "Alle velden zijn verplicht. Vul alles in.";
    }

    if (strlen($data['password']) < 5) {
        return "Het wachtwoord moet minimaal 5 tekens lang zijn.";
    }

    // Controleer of de gebruikersnaam al bestaat met een query
    $checkQuery = "SELECT username FROM Users WHERE username = ?";
    $checkStmt = $db->prepare($checkQuery);
    
    // Voer de query uit en controleer of het goed ging
    $checkResult = $checkStmt->execute([$data['username']]);
    if (!$checkResult) {
        return "Er is een probleem met de gebruikersnaamcontrole. Probeer het later opnieuw.";
    }

    // Als er een resultaat is, bestaat de gebruikersnaam al
    if ($checkStmt->rowCount() > 0) {
        return "Deze gebruikersnaam is al in gebruik.";
    }

    // Versleutel het wachtwoord
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    $role = 'Client'; // Standaard rol is 'Client'

    // Voeg de nieuwe gebruiker toe
    $query = "INSERT INTO Users (first_name, last_name, address, username, password, role) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    
    // Voer de insert-query uit en controleer of deze succesvol was
    $insertResult = $stmt->execute([$data['first_name'], $data['last_name'], $data['address'], $data['username'], $hashedPassword, $role]);
    
    if (!$insertResult) {
        return "Er is een probleem met het registreren van de gebruiker. Probeer het later opnieuw.";
    }

    return true; // Succesvolle registratie
}
?>

<?php
function registreerGebruiker($first_name, $last_name, $address, $username, $password)
{
    // Verbind met de database
    $db = maakVerbinding();

    // Versleutel het wachtwoord
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Stel de rol in als 'Client' (standaardrol)
    $role = 'Client'; // Dit is de gewenste standaard rol

    try {
        // Controleer of de gebruikersnaam al bestaat
        $query = "SELECT * FROM dbo.Users WHERE username = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            // Als de gebruikersnaam al bestaat
            return "Deze gebruikersnaam is al in gebruik!";
        } else {
            // Voeg de nieuwe gebruiker toe met de rol 'Client'
            $query = "INSERT INTO dbo.Users (first_name, last_name, address, username, password, role) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->execute([$first_name, $last_name, $address, $username, $hashedPassword, $role]);

            return true;  // Succesvolle registratie
        }
    } catch (PDOException $e) {
        return "Er is een fout opgetreden: " . $e->getMessage();
    }
}
?>

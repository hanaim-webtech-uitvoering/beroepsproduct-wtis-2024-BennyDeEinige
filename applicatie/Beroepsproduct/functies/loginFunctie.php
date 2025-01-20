<?php
function loginUser($usern, $passw)
{
    $db = maakVerbinding();
    $username = htmlspecialchars(trim($usern)); // Sanitize input
    $password = htmlspecialchars(trim($passw)); // Sanitize input

    try {
        // Select query voorbereiden (prepared statement)
        $sql = 'SELECT username, password, role FROM users WHERE username = :username';
        $query = $db->prepare($sql);
        $query->execute([':username' => $username]);

        // Gebruiker ophalen
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Controleer het wachtwoord met password_verify()
            if (password_verify($password, $row['password'])) {
                // Controleer de rol van de gebruiker
                if ($row['role'] === 'Client') {
                    // Sessie starten en gebruiker opslaan
                    session_start();
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['role'] = $row['role'];
                    return true; // Login succesvol
                } else {
                    return 'Toegang geweigerd. Alleen klanten kunnen hier inloggen.';
                }
            } else {
                return 'Wachtwoord is incorrect.';
            }
        } else {
            return 'Gebruikersnaam niet gevonden.';
        }
    } catch (PDOException $e) {
        return 'Databasefout: ' . $e->getMessage(); //foutmeldign
    }
}
?>

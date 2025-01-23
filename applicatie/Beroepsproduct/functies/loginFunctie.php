<?php
function loginUser($usern, $passw)
{
    $db = maakVerbinding();
    $username = htmlspecialchars(trim($usern)); // Sanitize input
    $password = htmlspecialchars(trim($passw)); 

    // Select query voorbereiden (prepared statement)
    $sql = 'SELECT username, password, role FROM users WHERE username = :username';
    $query = $db->prepare($sql);
    $executeResult = $query->execute([':username' => $username]);

    // Controleer of de query goed is uitgevoerd
    if ($executeResult) {
        // Gebruiker ophalen
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Controleer het wachtwoord met password_verify()
            if (password_verify($password, $row['password'])) {
                // Controleer de rol van de gebruiker (dit is voor Clienten)
                if ($row['role'] === 'Client') {
                    // Start de sessie en bewaar de gebruikersinformatie
                    session_start();
                    // Dit voorkomt dat een 'hacker' de sessie-ID van een ingelogde gebruiker kan overnemen.
                    session_regenerate_id(true);
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['role'] = $row['role'];
                    return true; // Login succesvol
                } else {
                    return 'Toegang geweigerd. Alleen klanten kunnen hier inloggen.';
                }
            }
        }
    }

    // Foutmelding als de query niet is uitgevoerd of login mislukt
    return 'Er is een probleem met het inloggen. Probeer het later opnieuw.';
}
?>
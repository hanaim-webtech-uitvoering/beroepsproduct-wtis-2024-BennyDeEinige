<?php
function loginMedewerker($usern, $passw)
{
    $db = maakVerbinding();
    $username = htmlspecialchars(trim($usern)); // Sanitize input
    $password = htmlspecialchars(trim($passw)); // Sanitize input

    // Query voorbereiden
    $sql = 'SELECT username, password, role FROM users WHERE username = :username';
    $query = $db->prepare($sql);
    $executeResult = $query->execute([':username' => $username]);

    // Controleer of de query goed is uitgevoerd
    if ($executeResult) {
        // Gebruiker ophalen
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Controleer wachtwoord met password_verify()
            if (password_verify($password, $row['password'])) {
                // Controleer de rol van de gebruiker
                if ($row['role'] === 'Personnel') {
                    // Sessie starten en gebruiker opslaan
                    session_start();
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['role'] = $row['role'];
                    return true; // Login succesvol
                } else {
                    return 'Toegang geweigerd. Alleen Medewerkers kunnen hier inloggen.';
                }
            } else {
                return 'Wachtwoord is onjuist.';
            }
        } else {
            return 'Gebruiker niet gevonden.';
        }
    }

    // Foutmelding als de query niet is uitgevoerd
    return 'Er is een probleem met het inloggen. Probeer het later opnieuw.';
}

?>

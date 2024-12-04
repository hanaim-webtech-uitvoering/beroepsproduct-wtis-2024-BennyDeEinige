<?php
// Zorg ervoor dat de verbinding met de database correct is
require_once 'functies/db_connectie.php';

try {
    // Maak verbinding met de database
    $db = maakVerbinding();

    // Haal alle gebruikers op met hun wachtwoord
    $sql = 'SELECT username, password FROM users';
    $query = $db->prepare($sql);
    $query->execute();

    // Loop door alle gebruikers en hash hun wachtwoord
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        // Haal het huidige wachtwoord op
        $plainPassword = $row['password'];

        // Hash het wachtwoord met password_hash()
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        // Update de gebruikers tabel met het gehashte wachtwoord
        $updateSql = 'UPDATE users SET password = :password WHERE username = :username';
        $updateQuery = $db->prepare($updateSql);
        $updateQuery->execute([':password' => $hashedPassword, ':username' => $row['username']]);

        echo 'Wachtwoord voor gebruiker ' . $row['username'] . ' is geüpdatet naar gehashte versie.<br>';
    }

} catch (PDOException $e) {
    // Foutafhandeling
    echo 'Databasefout: ' . $e->getMessage();
}
?>

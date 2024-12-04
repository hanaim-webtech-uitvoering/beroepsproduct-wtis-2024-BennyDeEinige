<?php
require_once 'functies/db_connectie.php';

try {
    $db = maakVerbinding();

    // Gebruik de juiste kolomnaam voor de primaire sleutel
    $sql = 'SELECT username, password FROM users'; // Hier user_id in plaats van id
    $query = $db->prepare($sql);
    $query->execute();

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $plainPassword = $row['password'];

        // Controleer of het wachtwoord nog niet gehasht is
        if (!password_get_info($plainPassword)['algoName']) {
            // Hash het wachtwoord
            $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
            // Update het wachtwoord in de database
            $updateSql = 'UPDATE users SET password = :password WHERE users = :users'; // Hier user_id in plaats van id
            $updateQuery = $db->prepare($updateSql);
            $updateQuery->execute([':password' => $hashedPassword, ':users' => $row['users']]);

            echo 'Wachtwoord voor gebruiker ' . $row['username'] . ' is geüpdatet naar gehashte versie.<br>';
        } else {
            echo 'Wachtwoord voor gebruiker ' . $row['username'] . ' is al gehasht. Geen wijziging nodig.<br>';
        }
    }

} catch (PDOException $e) {
    echo 'Databasefout: ' . $e->getMessage();
}
?>

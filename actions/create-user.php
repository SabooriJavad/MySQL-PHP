<?php

// Inkluderar databaskoppling och startar session (via db.php)
require 'db.php';
try {



    // Gör så att PDO kastar exceptions vid fel
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fångar eventuella fel vid anslutning till databasen
} catch (PDOException $e) {
    // Skriver ut felmeddelande om anslutningen misslyckas
    echo "connection failed: " . $e->getMessage();
}

// Kontrollerar om formuläret skickats med POST-metoden
// och att både username och password finns med
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {

    // Hämtar användarnamn från formuläret
    $username = $_POST['username'];

    // Hämtar lösenord från formuläret (klartext)
    $password = $_POST['password'];

    // Hashar lösenordet på ett säkert sätt
    // PASSWORD_DEFAULT använder den rekommenderade algoritmen
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Förbereder ett SQL-statement för att lägga till en ny användare
    // Platshållare (?) skyddar mot SQL-injektion
    $stmt = $pdo->prepare(
        "INSERT INTO users (username, password) VALUES (?, ?)"
    );

    // Kör SQL-frågan med användarnamn och hashat lösenord
    $stmt->execute([
        $username,
        $hash
    ]);

    // Efter registrering skickas användaren till login-sidan
    header('Location: login.php');

    // Avslutar scriptet för att förhindra vidare körning
    exit();

    // Alternativ feedback (utkommenterad)
    // echo '<script>alert("User created successfully")</script>';
}
?>





<form action="create-user.php" method="POST">
    <h1>Cretae User</h1>
    <input type="text" name="username" placeholder="Username"><br><br>
    <input type="password" name="password" placeholder="Password"><br><br>
    <button type="submit">Register</button><br>
    <h6>Har du redan konto?</h6>
    <p><button type="button" onclick="window.location.href='login.php'">Logga in</button></p>
</form>
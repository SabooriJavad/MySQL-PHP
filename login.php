<?php
// Inkluderar databaskoppling och startar session
require 'db.php';

// Kontrollerar om formuläret skickats med POST-metoden
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Förbereder SQL-fråga för att hämta användare baserat på användarnamn
    // ? används som platshållare för att skydda mot SQL-injektion
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");

    // Kör frågan med användarnamnet från formuläret
    $stmt->execute([$_POST['username']]);

    // Hämtar användaren som en associativ array
    $user = $stmt->fetch();

    // Kontrollerar:
    // 1. Att användaren finns
    // 2. Att angivet lösenord matchar hashat lösenord i databasen
    if ($user && password_verify($_POST['password'], $user['password'])) {

        // Sparar användarens ID i sessionen (användaren är nu inloggad)
        $_SESSION['user_id'] = $user['id'];

        // Skickar användaren till flödet
        header("Location: profile.php");
        exit; // Stoppar vidare körning

    } else {
        // Felmeddelande om inloggningen misslyckas
        $error = "Fel användarnamn eller lösenord";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Logga in</title>
</head>

<body>

    <h1>Logga in</h1>

    <!-- Visar felmeddelande om inloggning misslyckas -->
    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <!-- Inloggningsformulär -->
    <form method="POST">


        <input name="username" placeholder="Användarnamn" required><br><br>


        <input type="password" name="password" placeholder="Lösenord" required><br><br>


        <button>Logga in</button>


        <button type="button" onclick="window.location.href='create-user.php'">
            Registrera
        </button>

    </form>

</body>

</html>
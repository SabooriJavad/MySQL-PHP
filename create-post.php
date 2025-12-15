<?php
// Inkluderar databaskoppling och startar session (via db.php)
require 'db.php';

// Kontrollerar om användaren är inloggad
// Om user_id inte finns i sessionen skickas användaren till login-sidan
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Omdirigering till inloggning
    exit; // Avslutar scriptet
}

// Kontrollerar om formuläret skickades med POST-metoden
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Förbereder ett SQL-statement för att lägga till ett nytt inlägg
    // ? används som platshållare för att skydda mot SQL-injektion
    $stmt = $pdo->prepare(
        "INSERT INTO posts (user_id, content) VALUES (?, ?)"
    );

    // Kör SQL-frågan med inloggad användares ID och inläggets innehåll
    $stmt->execute([
        $_SESSION['user_id'], // ID på inloggad användare
        $_POST['content']     // Texten i inlägget
    ]);

    // Efter att inlägget skapats skickas användaren tillbaka till flödet
    header("Location: feed.php");
    exit; // Stoppar vidare exekvering
}
?>

<!-- HTML-formulär för att skapa ett nytt inlägg -->
<form method="POST">
    <!-- Textfält där användaren skriver sitt inlägg -->
    <textarea name="content" placeholder="Skriv ett inlägg..."></textarea><br>

    <!-- Knapp för att skicka formuläret -->
    <button>Publicera</button>
</form>



<?php include 'footer.php' ?>
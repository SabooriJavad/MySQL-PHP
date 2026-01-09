<?php
// Inkluderar databaskoppling och startar session (via db.php)
require 'db.php';

// Kontrollerar om användaren är inloggad
// Om ingen user_id finns i sessionen skickas användaren till login-sidan
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Förbereder ett SQL-statement för att lägga till en ny kommentar
// post_id   = vilket inlägg kommentaren hör till
// user_id   = vilken användare som skriver kommentaren
// content   = själva kommentaren
// parent_id = används för svar på kommentarer (null om huvudkommentar)
$stmt = $pdo->prepare("
    INSERT INTO comments (post_id, user_id, content, parent_id)
    VALUES (?, ?, ?, ?)
");

// Kör SQL-frågan med data från formuläret och sessionen
$stmt->execute([
    $_POST['post_id'],        // ID på inlägget
    $_SESSION['user_id'],     // ID på inloggad användare
    $_POST['content'],        // Kommentartext
    $_POST['parent_id']       // ID på förälder-kommentar (eller null)
]);

// Efter att kommentaren sparats skickas användaren tillbaka till flödet
header("Location: feed.php");

// Avslutar scriptet för att förhindra vidare körning
exit;

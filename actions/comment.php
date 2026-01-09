<?php
// Inkluderar databaskopplingen (och oftast även session_start())
require 'db.php';

// Kontrollerar om användaren är inloggad
// Om user_id inte finns i sessionen avslutas scriptet direkt
if (!isset($_SESSION['user_id'])) {
    exit;
}

// Förbereder ett SQL-statement för att lägga till en kommentar
// ? används som platshållare för att skydda mot SQL-injektion
$stmt = $pdo->prepare(
    "INSERT INTO comments (user_id, post_id, content) VALUES (?,?,?)"
);

// Kör SQL-frågan och skickar in värdena som en array
// $_SESSION['user_id'] = inloggad användares ID
// $_POST['post_id'] = ID på inlägget som kommenteras
// $_POST['content'] = själva kommentaren
$stmt->execute([
    $_SESSION['user_id'],
    $_POST['post_id'],
    $_POST['content']
]);

$stmt = $pdo->prepare("INSERT IGNORE INTO likes (user_id, post_id) VALUES (?,?)");
$stmt->execute([$_SESSION['user_id'], $_POST['post_id']]);


// Skickar användaren tillbaka till flödet efter att kommentaren sparats
header("Location: feed.php");

// Avslutar scriptet så inget mer körs efter redirect
exit;


?>
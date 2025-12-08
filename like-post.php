<?php
require 'db.php';


//Kontrollera inloggning
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
//Spara kommentaren i databasen
$stmt = $pdo->prepare("INSERT IGNORE INTO likes (user_id, post_id) VALUES (?,?)");
$stmt->execute([$_SESSION['user_id'], $_POST['post_id']]);

//Omdirigera tillbaka till feeden
header("Location: feed.php");
exit;

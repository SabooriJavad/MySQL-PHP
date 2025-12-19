<?php
require 'db.php';
if (session_status() == PHP_SESSION_NONE)
    session_start();

$comment_id = $_POST['comment_id'];

// Ta bort
$pdo->prepare("DELETE FROM comments WHERE id=? AND user_id=?")
    ->execute([$comment_id, $_SESSION['user_id']]);

header("Location:feed.php");
exit;

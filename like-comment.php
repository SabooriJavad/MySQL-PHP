<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$comment_id = $_POST['comment_id'];

// Kolla om redan gillat
$stmt = $pdo->prepare("SELECT id FROM likes WHERE comment_id=? AND user_id=?");
$stmt->execute([$comment_id, $user_id]);

if ($stmt->rowCount()) {
    $pdo->prepare("DELETE FROM likes WHERE comment_id=? AND user_id=?")->execute([$comment_id, $user_id]);
} else {
    $pdo->prepare("INSERT INTO likes (comment_id, user_id) VALUES (?, ?)")->execute([$comment_id, $user_id]);
}

header("Location: feed.php");
exit;

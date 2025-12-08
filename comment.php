<?php
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    exit;
}

$stmt = $pdo->prepare("INSERT INTO comments (user_id, post_id, content) VALUES (?,?,?)");
$stmt->execute([$_SESSION['user_id'], $_POST['post_id'], $_POST['content']]);

header("Location: feed.php");
exit;

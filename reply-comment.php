<?php
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO comments (post_id, user_id, content, parent_id)
    VALUES (?, ?, ?, ?)
");

$stmt->execute([
    $_POST['post_id'],
    $_SESSION['user_id'],
    $_POST['content'],
    $_POST['parent_id']
]);

header("Location: feed.php");
exit;

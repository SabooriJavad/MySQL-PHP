<?php
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $_POST['content']]);
    header("Location: feed.php");
    exit;
}
?>

<form method="POST">
    <textarea name="content" placeholder="Skriv ett inlägg..."></textarea><br>
    <button>Publicera</button>
</form>
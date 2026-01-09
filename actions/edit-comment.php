<?php
require 'db.php';
if (session_status() == PHP_SESSION_NONE)
    session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = $_POST['comment_id'];
    $content = $_POST['content'];

    // Redigera svar
    $pdo->prepare("UPDATE comments SET content=? WHERE id=? AND user_id=?")
        ->execute([$content, $comment_id, $_SESSION['user_id']]);

    header("Location: feed.php");
    exit;
}

$comment_id = $_GET['comment_id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

if (!$comment_id || !$user_id) {
    header("Location: feed.php");
    exit;
}

// Hämta kommentaren
$stmt = $pdo->prepare("SELECT * FROM comments WHERE id=? AND user_id=?");
$stmt->execute([$comment_id, $user_id]);
$comment = $stmt->fetch();

if (!$comment) {
    echo "Kommentaren finns inte eller tillhör inte dig.";
    exit;
}
?>
<h2>Redigera kommentar</h2>
<form action="edit-comment.php" method="POST">
    <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
    <textarea name="content" required><?= htmlspecialchars($comment['content']) ?></textarea><br>
    <button type="submit">Spara ändringar</button>
</form>
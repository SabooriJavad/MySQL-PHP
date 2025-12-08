<?php
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Hämta inlägg + användarnamn
$posts = $pdo->query("
    SELECT posts.*, users.username 
    FROM posts
    JOIN users ON posts.user_id = users.id
    ORDER BY posts.created_at DESC
")->fetchAll();

// Hämta likes räknat per inlägg
$likesStmt = $pdo->query("
    SELECT post_id, COUNT(*) AS like_count 
    FROM likes 
    GROUP BY post_id
");
$likes = [];
foreach ($likesStmt as $row) {
    $likes[$row['post_id']] = $row['like_count'];
}

// Hämta kommentarer
$commentsStmt = $pdo->query("
    SELECT comments.*, users.username 
    FROM comments
    JOIN users ON comments.user_id = users.id
    ORDER BY comments.created_at ASC
");
$comments = [];
foreach ($commentsStmt as $c) {
    $comments[$c['post_id']][] = $c;

}

?>

<!-- Länk till skapa nytt inlägg -->
<div style="margin-bottom:20px; display:flex; justify-content:space-between; align-items:center;">
    <a href="create-post.php"
        style="padding:10px; background-color:#4CAF50; color:white; text-decoration:none; border-radius:5px;">Skapa nytt
        inlägg</a>
    <a href="login.php"
        style="padding:10px; background-color:#f44336; color:white; text-decoration:none; border-radius:5px;">Logga
        ut</a>
</div>

<?php foreach ($posts as $p): ?>
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:20px;">

        <!-- Inlägg -->
        <strong><?= $p['username'] ?></strong> skrev:<br>
        <p><?= $p['content'] ?></p>

        <!-- Likes -->
        <p>
            Likes: <?= $likes[$p['id']] ?? 0 ?>
        </p>

        <form action="like-post.php" method="POST">
            <input type="hidden" name="post_id" value="<?= $p['id'] ?>">
            <button type="submit">❤️</button>
        </form>

        <hr>

        <!-- Kommentarer -->
        <h4>Kommentarer:</h4>
        <?php if (!empty($comments[$p['id']])): ?>
            <?php foreach ($comments[$p['id']] as $c): ?>
                <div style="margin-left:15px; margin-bottom:5px;">
                    <b><?= $c['username'] ?></b>: <?= $c['content'] ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color:#777;">Inga kommentarer än...</p>
        <?php endif; ?>

        <!-- Kommentarsformulär -->
        <form action="comment.php" method="POST" style="margin-top:10px;">
            <input type="hidden" name="post_id" value="<?= $p['id'] ?>">
            <input type="text" name="content" placeholder="Skriv en kommentar..." required>
            <button type="submit">Kommentera</button>
        </form>

    </div>
<?php endforeach; ?>
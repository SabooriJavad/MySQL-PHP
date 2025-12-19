<?php
require 'db.php';

// Starta session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Hämta inloggad användare
$user_id = $_SESSION['user_id'] ?? null;

// Hämta alla inlägg med användarnamn
$posts = $pdo->query("
    SELECT posts.*, users.username 
    FROM posts
    JOIN users ON posts.user_id = users.id
    ORDER BY posts.created_at DESC
")->fetchAll();

// Hämta likes per inlägg
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

// Hämta likes per kommentar
$commentLikesStmt = $pdo->query("
    SELECT comment_id, COUNT(*) AS cnt
    FROM likes
    WHERE comment_id IS NOT NULL
    GROUP BY comment_id
");
$commentLikes = [];
foreach ($commentLikesStmt as $row) {
    $commentLikes[$row['comment_id']] = $row['cnt'];
}
?>

<div style="margin-bottom:20px; display:flex; justify-content:space-between; align-items:center;">
    <a href="create-post.php"
        style="padding:10px; background-color:#4CAF50; color:white; text-decoration:none; border-radius:5px;">
        Skapa nytt inlägg
    </a>
</div>

<?php foreach ($posts as $p): ?>
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:20px;">
        <!-- Inlägg -->
        <strong><?= htmlspecialchars($p['username']) ?></strong> skrev:<br>
        <p><?= htmlspecialchars($p['content']) ?></p>

        <!-- Likes på inlägg -->
        <p>Likes: <?= $likes[$p['id']] ?? 0 ?></p>
        <form action="like-post.php" method="POST">
            <input type="hidden" name="post_id" value="<?= $p['id'] ?>">
            <button type="submit">❤️</button>
        </form>

        <hr>

        <!-- Kommentarer -->
        <h4>Kommentarer:</h4>
        <?php if (!empty($comments[$p['id']])): ?>
            <?php foreach ($comments[$p['id']] as $c): ?>
                <?php if ($c['parent_id'] === null): ?>
                    <div style="margin-left:15px; margin-bottom:10px;">
                        <b><?= htmlspecialchars($c['username']) ?></b>: <?= htmlspecialchars($c['content']) ?><br>




                        <!-- Likes på kommentar -->
                        <?= $commentLikes[$c['id']] ?? 0 ?>
                        <form action="like-comment.php" method="POST" style="display:inline;">
                            <input type="hidden" name="comment_id" value="<?= $c['id'] ?>">
                            <button type="submit"> ❤️</button>
                        </form>
                        <!-- Redigera kommentar -->
                        <?php if ($c['user_id'] == $user_id): ?>
                            <form action="edit-comment.php" method="GET" style="display:inline;">
                                <input type="hidden" name="comment_id" value="<?= $c['id'] ?>">
                                <button type="submit">Redigera</button>
                            </form>
                        <?php endif; ?>

                        <!-- Ta bort kommentar -->
                        <?php if ($c['user_id'] == $user_id): ?>
                            <form action="delete-comment.php" method="POST" style="display:inline;">
                                <input type="hidden" name="comment_id" value="<?= $c['id'] ?>">
                                <button type="submit">X</button>
                            </form>
                        <?php endif; ?>
                        <!-- Svara på kommentar -->
                        <form action="reply-comment.php" method="POST" style="margin-top:5px;">
                            <input type="hidden" name="post_id" value="<?= $p['id'] ?>">
                            <input type="hidden" name="parent_id" value="<?= $c['id'] ?>">
                            <input type="text" name="content" placeholder="Svara..." required>
                            <button type="submit">Svara</button>
                        </form>
                        <!-- Visa svar på kommentaren -->
                        <?php foreach ($comments[$p['id']] as $r): ?>
                            <?php if ($r['parent_id'] == $c['id']): ?>
                                <div style="margin-left:30px; margin-top:5px;">
                                    ↳ <b><?= htmlspecialchars($r['username']) ?></b>: <?= htmlspecialchars($r['content']) ?>

                                    <!-- Svarets redigera / ta bort -->
                                    <?php if ($r['user_id'] == $user_id): ?>
                                        <form action="edit-comment.php" method="GET" style="display:inline;">
                                            <input type="hidden" name="comment_id" value="<?= $r['id'] ?>">
                                            <button type="submit">Redigera</button>
                                        </form>
                                        <form action="delete-comment.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="comment_id" value="<?= $r['id'] ?>">
                                            <button type="submit">X</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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

<?php include 'footer.php'; ?>
<?php

// Inkluderar databaskopplingen ($pdo) och startar sessionen
require 'db.php';

// Kontroll: är användaren inloggad?
// Om ingen user_id finns i sessionen skickas användaren till login-sidan
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Förbereder en SQL-fråga som hämtar den inloggade användarens information
$stmt = $pdo->prepare(
    "SELECT id, username, created_at FROM users WHERE id = ?"
);

// Kör SQL-frågan och skickar in användarens ID från sessionen
$stmt->execute([$_SESSION['user_id']]);

// Hämtar användaren från databasen (en rad)
$user = $stmt->fetch();

// Om ingen användare hittas stoppas skriptet
if (!$user) {
    die("Användare hittades inte.");
}

// Förbereder en SQL-fråga som hämtar alla inlägg som tillhör användaren
//Räknar hur många likes inlägget har
// Räknar hur många kommentarer inlägget har
// Endast inlägg som tillhör användaren
//Nyaste inlägg först
$posts = $pdo->prepare("
    SELECT 
        posts.id AS post_id,        
        posts.content,             
        posts.created_at,            

       
        (SELECT COUNT(*) 
         FROM likes 
         WHERE likes.post_id = posts.id) AS like_count,

        
        (SELECT COUNT(*) 
         FROM comments 
         WHERE comments.post_id = posts.id) AS comment_count

    FROM posts
    WHERE posts.user_id = ?         
    ORDER BY posts.created_at DESC   
");

// Kör SQL-frågan och skickar in användarens ID
$posts->execute([$_SESSION['user_id']]);

// Hämtar alla inlägg som en array
$posts = $posts->fetchAll();

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Profile - <?php echo htmlspecialchars($user['username']); ?></title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            max-width: 500px;
            margin: 0 auto;
        }

        header {
            background: #333;
            color: #fff;
            padding: 15px;
            display: flex;
            justify-content: space-evenly;
        }

        header a {
            color: #fff;
            text-decoration: none;
            margin-left: 10px;
        }

        .profile {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .post {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }

        .post:last-child {
            border-bottom: none;
        }
    </style>
</head>

<h1>
    Profile page
</h1>
<strong> <?php echo htmlspecialchars($user['username']); ?></strong>
<div class="profile">
    <p> <?php echo htmlspecialchars($user['created_at']); ?> </p>
</div>
<h2> Inlägg</h2>
<?php foreach ($posts as $post): ?>
    <div class="post">
        <p>
            <?php print nl2br(htmlspecialchars($post['content'])); ?>
        </p>
        <small> skapad :<?php print htmlspecialchars($post['created_at']); ?>
            ❤️
            <?php print $post['like_count']; ?><br />
            kommentarer:
            <?php print $post['comment_count']; ?> </small>
    </div>
<?php endforeach ?>

<body>

    <?php include 'footer.php' ?>


</body>

</html>
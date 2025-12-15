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
    ORDER BY posts.created_at DESC   -- 
");

// Kör SQL-frågan och skickar in användarens ID
$posts->execute([$_SESSION['user_id']]);

// Hämtar alla inlägg som en array
$posts = $posts->fetchAll();

?>


<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<title>Profile - <?php echo htmlspecialchars($user['username']); ?></title>
</head>
<header>
    <nav>
        <a href="profile.php">Min profil</a>
        <a href="feed.php">Flöde</a>
        <a href="logout.php">Logga ut</a>
        <a href="login.php">Logga in</a>
        <a href="create-user.php">Skapa konto</a>
    </nav>

</header>

<body>


    <h1>
        Profile page
    </h1>

</body>

</html>
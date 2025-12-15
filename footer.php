<?php
require 'db.php';

$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <footer class="footer">
        <nav>
            <a href="feed.php" class="nav-link">Home</a>

            <?php if (!$isLoggedIn): ?>
                <a href="login.php" class="nav-link">Log in</a>
                <a href="create-user.php" class="nav-link">Register</a>
            <?php else: ?>
                <a href="profile.php" class="nav-link">Profile</a>
                <a href="feed.php" class="nav-link">Feed</a>
                <a href="create-post.php" class="nav-link">Create</a>
                <a href="logout.php" class="nav">Logout</a>
            <?php endif; ?>
        </nav>
    </footer>



</body>

</html>
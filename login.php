<?php

require 'db.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        // Logga in användaren
        $_SESSION['user_id'] = $user['id'];
        header("Location: feed.php");
        exit;
    } else {
        $error = "Fel användarnamn eller lösenord";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

</head>

<body>

    <h1>Logga in</h1>


    <form method="POST">
        <input name="username" placeholder="Användarnamn" required><br><br>
        <input type="password" name="password" placeholder="Lösenord" required><br><br>
        <button>Logga in</button>
        <button type="button" onclick="window.location.href='create-user.php'">Register</button>

    </form>

</body>

</html>
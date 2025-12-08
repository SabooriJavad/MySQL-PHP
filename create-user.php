<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=microblock;charset=utf8", "root", "");

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "connection failed:" . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $hash = password_hash($password, PASSWORD_DEFAULT);


    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?,?)");
    $stmt->execute([$username, $hash]);

    header('Location:login.php');
    exit();

    // echo '<script>alert("User created successfully")</script>';


}


?>






<form action="create-user.php" method="POST">
    <h1>Cretae User</h1>
    <input type="text" name="username" placeholder="Username"><br><br>
    <input type="password" name="password" placeholder="Password"><br><br>
    <button type="submit">Register</button><br>
    <h6>Har du redan konto?</h6>
    <p><button type="button" onclick="window.location.href='login.php'">Logga in</button></p>
</form>
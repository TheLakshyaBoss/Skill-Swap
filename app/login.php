<?php
include "db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $pass = $_POST["password"];

    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows) {
        $stmt->bind_result($hash);
        $stmt->fetch();
        if (password_verify($pass, $hash)) {
            $_SESSION["email"] = $email;
            header("Location: home.php");
            exit();
        }
    }
    echo "<script>alert('Invalid email or password');</script>";
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link rel="stylesheet" href="../style.css">
</head>

<style>

body {
    background: #0d1117;
    color: #c9d1d9;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

</style>

<body>

<form method="POST">

    <h2>Login</h2>

    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password" required>
    <button>Login</button>

    <a href="signup.php">Don't have an account? Sign up</a>

</form>

</body>
</html>

<?php
include "db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['user_id'])) {
    $from_id = $_SESSION['user_id'];
    $to_id = $_POST['to_user_id'];
    $offered = $_POST['my_skill'];
    $wanted = $_POST['their_skill'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO requests (from_user_id, to_user_id, offered_skill, wanted_skill, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $from_id, $to_id, $offered, $wanted, $message);
    
    if ($stmt->execute()) {
        echo "Request sent!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

header("Location: swap-request.php");
?>
<?php
include "db.php";
session_start();

if (!isset($_SESSION['user_id'])) die("Not authorized");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $action = $_POST['action'];

    if (!in_array($action, ['accepted', 'rejected'])) {
        die("Invalid action");
    }

    $stmt = $conn->prepare("UPDATE requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $action, $id);

    if ($stmt->execute()) {
        echo "Request " . ucfirst($action);
    } else {
        echo "Failed to update";
    }
}

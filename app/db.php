<?php
$server = "localhost";
$username = "root";
$password = "";
$dbname = "skillswap";

// Create connection
$conn = new mysqli($server, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
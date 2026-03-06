<?php
require_once 'config.php';

$host = "localhost";
$user = "root";       // Default XAMPP username
$pass = "";           // Default XAMPP password (empty)
$dbname = "govflow_db"; // Replace this with your actual database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Set charset to utf8mb4 to handle special characters correctly
$conn->set_charset("utf8mb4");
?>
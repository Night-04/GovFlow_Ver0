<?php
session_start();
require_once('../includes/db_connect.php'); // Adjust path if your process folder is deep
header('Content-Type: application/json');

// Security Check: Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Select only the 'pending' requests
$sql = "SELECT request_id, first_name, surname, name_extension, department_name, request_code 
        FROM account_requests 
        WHERE status = 'pending' 
        ORDER BY request_id DESC";

$result = $conn->query($sql);
$requests = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
}

echo json_encode($requests);
$conn->close();
?>
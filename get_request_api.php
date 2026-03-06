<?php
// 1. Load the database connection
require 'includes/db_connect.php';

// 2. Set the header to JSON so the JavaScript can read it
header('Content-Type: application/json');

// 3. Fetch pending requests
// We JOIN with the departments table so we can show the Office Name instead of just an ID number
$sql = "SELECT r.*, d.department_name 
        FROM account_requests r 
        JOIN departments d ON r.department_id = d.department_id 
        WHERE r.status = 'pending'
        ORDER BY r.requested_at DESC";

$result = $conn->query($sql);
$requests = [];

if ($result) {
    while($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
}

// 4. Output the data
echo json_encode($requests);
?>
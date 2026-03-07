<?php
session_start();
require_once('../includes/db_connect.php');
header('Content-Type: application/json');

// 1. Fully secured session check
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'administrator') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// 2. The Query
// 2. The Query using an INNER JOIN
$sql = "SELECT 
            ar.request_id, 
            ar.first_name, 
            ar.middle_name, 
            ar.surname, 
            ar.name_extension, 
            ar.requested_role, 
            ar.request_code, 
            d.department_name 
        FROM account_requests ar
        INNER JOIN departments d ON ar.department_id = d.department_id
        WHERE ar.status = 'pending' 
        ORDER BY ar.request_id DESC";

$result = $conn->query($sql);

// 3. CACHING THE SQL ERROR
if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Database Error: ' . $conn->error]);
    exit();
}

$requests = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
}

echo json_encode($requests);
$conn->close();
?>
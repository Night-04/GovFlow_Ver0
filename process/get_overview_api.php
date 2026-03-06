<?php
session_start();
header('Content-Type: application/json');

// 1. Security Check & DB Connection
require_once '../includes/db_connect.php'; // Make sure this path is correct!

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

try {
    // 2. Fetch Departmental Document Load
    // Note: You will need to adjust these column/table names based on your actual database schema
    // This is a standard approach for a tracking system
    $sql = "SELECT 
                d.department_name as name, 
                COUNT(CASE WHEN doc.status = 'processing' THEN 1 END) as holding,
                COUNT(CASE WHEN doc.status = 'transmitted' THEN 1 END) as pending
            FROM departments d
            LEFT JOIN documents doc ON d.department_id = doc.current_department_id
            GROUP BY d.department_id";
            
    $result = $conn->query($sql);
    $departments = [];

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $departments[] = [
                'name' => $row['name'],
                'holding' => (int)$row['holding'],
                'pending' => (int)$row['pending']
            ];
        }
    } else {
        // Fallback dummy data so your UI doesn't break if the table is empty/doesn't exist yet
        $departments = [
            ['name' => 'Mayor\'s Office', 'holding' => 12, 'pending' => 4],
            ['name' => 'Budget Office', 'holding' => 25, 'pending' => 10],
            ['name' => 'Accounting Office', 'holding' => 5, 'pending' => 2],
            ['name' => 'BAC Office', 'holding' => 8, 'pending' => 0]
        ];
    }

    // 3. Send back the JSON response
    echo json_encode(['departments' => $departments]);

} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>
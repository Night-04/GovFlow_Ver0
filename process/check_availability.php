<?php
require '../includes/db_connect.php';
header('Content-Type: application/json');

if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check for pending or blocked requests
    $sql = "SELECT status FROM account_requests WHERE email = '$email' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $status = $row['status'];

        if ($status === 'pending') {
            echo json_encode(['available' => false, 'message' => 'There is already a pending request for this email.']);
        } elseif ($status === 'blocked') {
            echo json_encode(['available' => false, 'message' => 'This email has been blocked from creating an account.']);
        } else {
            // If rejected or approved (but somehow trying again), we let them through
            echo json_encode(['available' => true]);
        }
    } else {
        echo json_encode(['available' => true]);
    }
}
?>
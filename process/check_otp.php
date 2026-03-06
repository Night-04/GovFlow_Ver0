<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_code = $_POST['code'];

    // 1. Check if OTP exists in session
    if (!isset($_SESSION['otp_code']) || !isset($_SESSION['otp_expiry'])) {
        echo json_encode(['success' => false, 'message' => 'No session found.']);
        exit;
    }

    // 2. Check Expiration
    if (time() > $_SESSION['otp_expiry']) {
        unset($_SESSION['otp_code']);
        unset($_SESSION['otp_expiry']);
        echo json_encode(['success' => false, 'message' => 'Code expired.']);
        exit;
    }

    // 3. Verify Code
    if ($user_code == $_SESSION['otp_code']) {
        $_SESSION['is_verified'] = true; // Flag for the next page
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid code.']);
    }
}
?>
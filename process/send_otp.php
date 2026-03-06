<?php
session_start();
header('Content-Type: application/json');

require_once '../includes/db_connect.php';

// --- 1. Load PHPMailer correctly from the parent directory ---
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// The ../ tells PHP to go UP one folder from 'process' to 'GovFlow'
require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
        exit;
    }

    // --- 2. Generate 6-Digit OTP & Expiry ---
    $otp = rand(100000, 999999);
    $_SESSION['otp_code'] = $otp;
    $_SESSION['otp_expiry'] = time() + 120; // Current time + 120 seconds (2 mins)
    $_SESSION['temp_email'] = $email;

    // --- 3. Send the Email via Gmail SMTP ---
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = get_env('SMTP_HOST');
        $mail->SMTPAuth   = true;
        $mail->Username   = get_env('SMTP_USER'); // YOUR GMAIL
        $mail->Password   = get_env('SMTP_PASS');      // YOUR APP PASSWORD
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = get_env('SMTP_PORT');

        // Recipients
        $mail->setFrom( get_env('SMTP_USER'), 'GovFlow System');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your GovFlow Verification Code';
        $mail->Body    = "
            <div style='font-family: sans-serif; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;'>
                <h2 style='color: #1e3a8a;'>GovFlow Verification</h2>
                <p>You requested an account for the Municipality Document Tracking System.</p>
                <p>Your verification code is:</p>
                <h1 style='letter-spacing: 5px; color: #3b82f6;'>$otp</h1>
                <p style='color: #ef4444;'><strong>Note:</strong> This code will expire in 2 minutes.</p>
                <hr>
                <small>If you did not request this, please ignore this email.</small>
            </div>";

        $mail->send();
        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => "Mailer Error: {$mail->ErrorInfo}"]);
    }
}
?>
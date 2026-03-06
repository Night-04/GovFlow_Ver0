<?php
session_start();
require '../includes/db_connect.php';

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

if ($_SESSION['role'] !== 'administrator') {
    die("Unauthorized access.");
}

if (isset($_GET['id'])) {
    $request_id = (int)$_GET['id'];
    // Sanitize the reason/remarks
    $remarks = isset($_GET['remarks']) ? mysqli_real_escape_string($conn, $_GET['remarks']) : 'No specific reason provided.';

    // 1. Get user email before updating
    $query = $conn->query("SELECT email, first_name FROM account_requests WHERE request_id = $request_id");
    $req = $query->fetch_assoc();
    $email = $req['email'];
    $fname = $req['first_name'];

    // 2. Update status to 'rejected'
    $sql = "UPDATE account_requests SET status = 'rejected', remarks = '$remarks', reviewed_at = NOW() WHERE request_id = $request_id";

    if ($conn->query($sql)) {
        // 3. Email the user the bad news + the reason
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = get_env('SMTP_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = get_env('SMTP_USER');
            $mail->Password   = get_env('SMTP_PASS');  
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = get_env('SMTP_PORT');

            $mail->setFrom(get_env('SMTP_USER'), 'GovFlow System');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Update on your GovFlow Account Request';
            $mail->Body    = "Hello $fname,<br><br>
                              Your account request has been <b>denied</b> for the following reason:<br>
                              <blockquote style='background:#f1f5f9; padding:10px;'>$remarks</blockquote><br>
                              Please contact your Department Head or try registering again with the correct information.";
            $mail->send();
        } catch (Exception $e) { /* Log error */ }

        echo "<script>alert('Request rejected and user notified.'); window.location='../admin.php';</script>";
    }
}
?>
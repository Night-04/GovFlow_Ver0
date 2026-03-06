<?php
session_start();
require '../includes/db_connect.php';

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Verify Captcha
    if (!isset($_POST['captcha_input']) || $_POST['captcha_input'] != $_SESSION['captcha_answer']) {
        echo "<script>alert('Security check failed.'); window.history.back();</script>";
        exit();
    }

    // 2. Sanitize Inputs
    $fname = mysqli_real_escape_string($conn, $_POST['first_name']);
    $mname = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $lname = mysqli_real_escape_string($conn, $_POST['surname']);
    $ext   = mysqli_real_escape_string($conn, $_POST['name_extension']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $dept  = (int)$_POST['department_id'];
    $role  = mysqli_real_escape_string($conn, $_POST['requested_role']);

    // 3. Generate Request Code
    function generateRequestCode() {
        $letters = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
        $numbers = substr(str_shuffle("0123456789"), 0, 3);
        return $letters . $numbers;
    }
    $requestCode = generateRequestCode();

    // 4. Insert into Database
    $sql = "INSERT INTO account_requests 
            (first_name, middle_name, surname, name_extension, email, department_id, requested_role, status, request_code) 
            VALUES ('$fname', '$mname', '$lname', '$ext', '$email', $dept, '$role', 'pending', '$requestCode')";

    if ($conn->query($sql)) {
        // 5. Email the Request Code to user
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = get_env('SMTP_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = get_env('SMTP_USER');
            $mail->Password   = get_env('SMTP_PASS');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = get_env('SMTP_PORT');

            $mail->setFrom( get_env('SMTP_USER'), 'GovFlow System');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Your GovFlow Request Code';
            $mail->Body    = "Your account request has been received. <br> 
                              Verification Code: <b>$requestCode</b> <br> 
                              Present this to the Admin for approval.";
            $mail->send();
        } catch (Exception $e) { /* Log error if needed */ }

        unset($_SESSION['is_verified']);
        echo "<script>alert('Request submitted! Check your email for your Request Code.'); window.location='../login.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
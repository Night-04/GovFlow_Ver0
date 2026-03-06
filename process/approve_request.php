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

    // 1. Get the request details
    $request_query = $conn->query("SELECT * FROM account_requests WHERE request_id = $request_id AND status = 'pending'");
    
    if ($request_query->num_rows > 0) {
        $req = $request_query->fetch_assoc();

        // 2. Assign Variables from Request
        $fname = $req['first_name'];
        $mname = $req['middle_name'];
        $sname = $req['surname'];
        $ext   = $req['name_extension'];
        $email = $req['email'];
        $dept  = $req['department_id'];
        $role  = $req['requested_role'];

        // 3. Generate Temporary Password
        $tempPassword = "Gov-" . rand(100000, 999999);
        $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);

        // 4. Database Transaction (Insert User + Update Request)
        $conn->begin_transaction();

        try {
            // Insert into Users table
            $stmt = $conn->prepare("INSERT INTO users (name, middle_name, surname, name_extension, email, department_id, role, password_hash, account_status, must_change_password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active', 1)");
            $stmt->bind_param("sssssiss", $fname, $mname, $sname, $ext, $email, $dept, $role, $hashedPassword);
            $stmt->execute();

            // Update Request Status
            $conn->query("UPDATE account_requests SET status = 'approved', reviewed_at = NOW() WHERE request_id = $request_id");

            // 5. Send Email with Credentials
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = get_env('SMTP_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = get_env('SMTP_USER'); // Use your actual email
            $mail->Password   = get_env('SMTP_PASS');    // Use your actual key
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = get_env('SMTP_PORT');

            $mail->setFrom(get_env('SMTP_USER'), 'GovFlow System');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Account Approved - Your GovFlow Credentials';
            $mail->Body    = "Hello $fname, your account has been approved.<br><br>
                              Temporary Password: <b>$tempPassword</b><br>
                              Please login and change your password immediately.";
            $mail->send();

            $conn->commit();
            echo "<script>alert('Account approved and password emailed!'); window.location='../admin.php';</script>";
        } catch (Exception $e) {
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
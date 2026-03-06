<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['is_verified']) || $_SESSION['is_verified'] !== true) {
    header("Location: verify_email.php");
    exit();
}

// 2. Load Dependencies
require 'includes/db_connect.php';

// 3. Captcha Generation (Single Pass)
$words = [
    0 => "zero", 1 => "one", 2 => "two", 3 => "three", 4 => "four", 
    5 => "five", 6 => "six", 7 => "seven", 8 => "eight", 9 => "nine", 10 => "ten"
];

$num1 = rand(1, 10);
$num2 = rand(1, 10);

// Set the session answer immediately
$_SESSION['captcha_answer'] = $num1 + $num2;

// Randomly choose which one to turn into a word for the UI
$display1 = (rand(0, 1) == 1) ? $words[$num1] : $num1;
$display2 = (rand(0, 1) == 1) ? $words[$num2] : $num2;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Details | GovFlow</title>
    <link rel="stylesheet" href="css/login.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-card wide-card">
            <div class="login-header">
                <img src="assets/icons/lgu-logo.png" alt="LGU Logo" class="login-logo">
                <h1>Complete Your Profile</h1>
                <p>Providing details for <strong><?php echo $_SESSION['temp_email']; ?></strong></p>
            </div>

            <form action="process/submit_request.php" method="POST" class="registration-form">
                <div class="form-row">
                    <div class="input-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" required placeholder="Juan" maxlength="100">
                    </div>
                    <div class="input-group">
                        <label>Middle Name</label>
                        <input type="text" name="middle_name" placeholder="Manalastas" maxlength="100">
                    </div>
                </div>

                <div class="form-row">
                    <div class="input-group">
                        <label>Surname</label>
                        <input type="text" name="surname" required placeholder="Dela Cruz" maxlength="100">
                    </div>
                    <div class="input-group">
                        <label>Extension</label>
                        <input type="text" name="name_extension" placeholder="Jr., III, etc." maxlength="10">
                    </div>
                </div>

                <div class="input-group">
                    <label>Department</label>
                    <select name="department_id" required>
                        <option value="">Select Department...</option>
                        <?php
                        $depts = $conn->query("SELECT department_id, department_name FROM departments");
                        while($row = $depts->fetch_assoc()) {
                            echo "<option value='{$row['department_id']}'>{$row['department_name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="input-group">
                    <label>Requested Role</label>
                    <select name="requested_role" required>
                        <option value="requestor">Requestor (Tracking Only)</option>
                        <option value="processor">Processor (Dept. Staff)</option>
                    </select>
                </div>

                <div class="captcha-container" id="captcha-box">
                    <label>Security Check: What is <strong><?php echo "$display1 + $display2"; ?></strong>?</label>
                    <div class="input-wrapper">
                        <i class="ph ph-shield-check"></i>
                        <input type="number" id="captcha_input" name="captcha_input" placeholder="Answer" required autocomplete="off">
                    </div>
                    <span id="captcha-error" class="error-msg" style="display:none; color: #ef4444; font-size: 0.8rem; margin-top: 5px;"></span>
                </div>

                <input type="hidden" name="email" value="<?php echo $_SESSION['temp_email']; ?>">

                <button type="submit" class="login-btn">Send Account Request</button>
            </form>
        </div>
    </div>
    
    <script>
        // Select the form using its class name
        const registrationForm = document.querySelector('.registration-form');

        if (registrationForm) {
            registrationForm.addEventListener('submit', function() {
                // Find the submit button inside this form
                const btn = this.querySelector('.login-btn');
                
                // Provide visual feedback so the user doesn't click twice
                btn.innerText = "Processing Request...";
                btn.style.opacity = "0.7";
                btn.style.pointerEvents = "none";
            });
        }

        document.querySelector('.registration-form').addEventListener('submit', async function(e) {
            e.preventDefault(); // Stop the page from reloading

            const captchaInput = document.getElementById('captcha_input');
            const captchaBox = document.getElementById('captcha-box');
            const errorMsg = document.getElementById('captcha-error');
            const btn = this.querySelector('.login-btn');

            // 1. Reset UI
            captchaInput.style.border = "";
            errorMsg.style.display = "none";

            // 2. Quick AJAX check for Captcha
            const formData = new FormData(this);
            
            btn.innerText = "Verifying...";
            btn.disabled = true;

            try {
                const response = await fetch('process/verify_captcha_only.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    // CAPTCHA PASSED - Now actually submit the full form
                    btn.innerText = "Sending Request...";
                    this.submit(); 
                } else {
                    // CAPTCHA FAILED - Shake and show Red
                    captchaInput.style.border = "2px solid #ef4444";
                    errorMsg.innerText = "Incorrect answer. Please try again.";
                    errorMsg.style.display = "block";
                    
                    // Trigger the shake animation
                    captchaBox.classList.add('shake');
                    setTimeout(() => captchaBox.classList.remove('shake'), 500);

                    btn.innerText = "Send Account Request";
                    btn.disabled = false;
                }
            } catch (err) {
                alert("System error. Please try again.");
                btn.disabled = false;
            }
        });
    </script>
</body>

</html>
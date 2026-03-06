<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification | GovFlow</title>
    <link rel="stylesheet" href="css/login.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="login-page">

<div class="login-container">
    <div class="login-card" id="verification-step">
        <div class="login-header">
            <img src="assets/icons/lgu-logo.png" alt="LGU Logo" class="login-logo">
            <h1>Email Verification</h1>
            <p>Enter your official email to receive a verification code.</p>
        </div>
        
        <div id="email-input-area">
            <div class="input-group">
                <label>Work Email</label>
                <div class="input-wrapper" id="email-wrapper">
                    <i class="ph ph-envelope"></i>
                    <input type="email" id="user-email" placeholder="name@bulacan.gov.ph" required>
                </div>
                <span id="email-error-msg" class="error-msg" style="display:none; color: #ef4444; font-size: 0.8rem; margin-top: 5px;"></span>
            </div>
            <button onclick="sendCode()" id="btn-send" class="login-btn">Send Code</button>
        </div>

        <div id="code-input-area" style="display:none; margin-top:20px;">
            <p style="font-size: 0.9rem; margin-bottom: 15px; color: #64748b;">
                A 6-digit code was sent to <strong id="display-email"></strong>
            </p>
            <div class="input-group">
                <div class="input-wrapper" id="code-wrapper">
                    <input type="text" id="verify-code" maxlength="6" 
                        placeholder="0 0 0 0 0 0" 
                        style="text-align:center; font-size: 1.5rem; letter-spacing: 8px;">
                </div>
                <span id="code-error-msg" class="error-msg" style="display:none; color: #ef4444; font-size: 0.8rem; margin-top: 5px; text-align: center; display: block;"></span>
            </div>
            <button onclick="verifyCode()" id="btn-verify" class="login-btn">Verify & Continue</button>
            <button onclick="location.reload()" class="tool-btn" style="margin-top:10px; font-size: 0.8rem;">Change Email</button>
        </div>
        
        <div class="login-footer">
            <p>Already verified? <a href="login.php">Back to Login</a></p>
        </div>
    </div>
</div>

<script>
    async function sendCode() {
        const emailInput = document.getElementById('user-email');
        const emailWrapper = document.getElementById('email-wrapper');
        const errorMsg = document.getElementById('email-error-msg');
        const btn = document.getElementById('btn-send');
        const email = emailInput.value;

        // Reset UI
        emailInput.style.borderColor = ""; 
        errorMsg.style.display = "none";

        if(!email.includes('@')) {
            alert("Please enter a valid email address.");
            return;
        }

        btn.innerText = "Checking...";
        btn.disabled = true;

        try {
            // STEP 1: Check if email is available
            const checkRes = await fetch('process/check_availability.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'email=' + encodeURIComponent(email)
            });
            const availability = await checkRes.json();

            if (!availability.available) {
                // STOP! Show error UI
                emailInput.style.border = "2px solid #ef4444";
                errorMsg.innerText = availability.message;
                errorMsg.style.display = "block";
                
                btn.innerText = "Send Code";
                btn.disabled = false;
                return; // Exit the function
            }

            // STEP 2: If available, send the OTP
            btn.innerText = "Sending OTP...";
            const otpRes = await fetch('process/send_otp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'email=' + encodeURIComponent(email)
            });
            const otpData = await otpRes.json();

            if(otpData.success) {
                document.getElementById('display-email').innerText = email;
                document.getElementById('email-input-area').style.display = 'none';
                document.getElementById('code-input-area').style.display = 'flex';
            } else {
                alert(otpData.message || "Error sending code.");
                btn.innerText = "Send Code";
                btn.disabled = false;
            }

        } catch (err) {
            console.error(err);
            alert("A system error occurred.");
            btn.disabled = false;
        }
    }

    function verifyCode() {
        const codeInput = document.getElementById('verify-code');
        const errorMsg = document.getElementById('code-error-msg');
        const btn = document.getElementById('btn-verify');
        const code = codeInput.value;

        // Reset UI before checking
        codeInput.style.border = "";
        errorMsg.style.display = "none";
        btn.innerText = "Verifying...";
        btn.disabled = true;

        fetch('process/check_otp.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'code=' + encodeURIComponent(code)
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Success! Send them to the detailed registration form
                window.location.href = 'register_details.php';
            } else {
                // FAILED: Show the red border and the specific error message
                codeInput.style.border = "2px solid #ef4444";
                errorMsg.innerText = data.message || "Invalid code. Please try again.";
                errorMsg.style.display = "block";
                
                // Shake effect (Optional but cool for UX)
                codeInput.classList.add('shake');
                setTimeout(() => codeInput.classList.remove('shake'), 500);

                btn.innerText = "Verify & Continue";
                btn.disabled = false;
            }
        })
        .catch(err => {
            console.error(err);
            btn.disabled = false;
            btn.innerText = "Verify & Continue";
        });
    }
</script>

</body>
</html>
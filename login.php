<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | GovFlow MS</title>
    <link rel="stylesheet" href="css/login.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="login-page">

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <img src="assets/icons/lgu-logo.png" alt="LGU Logo" class="login-logo">
            <h1>GovFlow</h1>
            <p>Municipal Document Tracking System</p>
        </div>

        <form action="authenticate.php" method="POST" class="login-form">
            <div class="input-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <i class="ph ph-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="name@municipality.gov.ph" required>
                </div>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="ph ph-lock"></i>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember"> Remember me
                </label>
                <a href="forgot-password.php" class="forgot-link">Forgot Password?</a>
            </div>

            <button type="submit" class="login-btn">Sign In</button>
        </form>

        <div class="login-footer">
            <p>New employee? <a href="verify_email.php">Request an Account</a></p>
        </div>
    </div>
</div>

</body>
</html>
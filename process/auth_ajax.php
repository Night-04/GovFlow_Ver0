function sendCode() {
    const email = document.getElementById('user-email').value;
    // AJAX call to a PHP script that uses mail() or PHPMailer
    fetch('send_otp.php', {
        method: 'POST',
        body: JSON.stringify({ email: email })
    }).then(() => {
        document.getElementById('email-input-area').style.display = 'none';
        document.getElementById('code-input-area').style.display = 'block';
    });
}

function verifyCode() {
    const code = document.getElementById('verify-code').value;
    fetch('check_otp.php', {
        method: 'POST',
        body: JSON.stringify({ code: code })
    }).then(res => res.json()).then(data => {
        if(data.success) {
            window.location = 'register_details.php'; // Proceed to the big form
        } else {
            alert('Invalid Code');
        }
    });
}
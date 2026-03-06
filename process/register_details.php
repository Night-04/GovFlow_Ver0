<?php
// Simple Math Captcha setup
$num1 = rand(1, 10);
$num2 = rand(1, 10);
$_SESSION['captcha_answer'] = $num1 + $num2;
?>

<form action="submit_request.php" method="POST">
    <div class="input-group">
        <label>Requested Role</label>
        <select name="requested_role" required>
            <option value="requestor">Requestor</option>
            <option value="processor">Processor</option>
            </select>
    </div>

    <div class="captcha-box">
        <label>Security Check: What is <?php echo "$num1 + $num2"; ?>?</label>
        <input type="number" name="captcha_input" required placeholder="Enter answer">
    </div>

    <button type="submit" class="login-btn">Submit Request to Admin</button>
</form>
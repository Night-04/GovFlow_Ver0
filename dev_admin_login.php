<?php
session_start();

// Manually inject Admin credentials into your browser's session
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'administrator';
$_SESSION['first_name'] = 'System';
$_SESSION['last_name'] = 'Admin';

echo "<h2>Developer Override Active!</h2>";
echo "<p>You are now logged in as an Administrator.</p>";
echo "<a href='admin.php'>Click here to go to the Admin Dashboard</a>";
?>
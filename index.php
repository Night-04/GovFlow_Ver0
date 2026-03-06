<?php
session_start();

// 1. Check if the user is NOT logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. If they ARE logged in, check their role to send them to the right place
// We assume you stored 'role' in the session during login
if ($_SESSION['role'] === 'administrator') {
    header("Location: admin.php");
    exit();
} else {
    // This handles requestors and processors
    header("Location: dashboard.php");
    exit();
}
?>
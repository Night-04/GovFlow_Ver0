<?php
session_start();
header('Content-Type: application/json');

if (isset($_POST['captcha_input'])) {
    if ($_POST['captcha_input'] == $_SESSION['captcha_answer']) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
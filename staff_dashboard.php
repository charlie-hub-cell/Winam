<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit;
}

// Redirect staff users to the web form
header("Location: web_form.html");
exit;
?>

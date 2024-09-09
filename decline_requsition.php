<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $requisition_id = $_GET['id'];

    // Update requisition status to declined in the database
    // Implement your database update logic here

    header("Location: admin_dashboard.php");
    exit;
} else {
    header("Location: admin_dashboard.php");
    exit;
}
?>

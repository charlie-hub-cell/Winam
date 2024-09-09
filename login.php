<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Database connection parameters
$servername = "sdb-o.hosting.stackcp.net";
$username = "requisition-31393776db";
$password = "fu7aqjx0q8";
$dbname = "requisition-31393776db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare and execute SQL statement
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_password, $role);
    $stmt->fetch();
    $stmt->close();

    // Debugging: Log role value
    error_log("Role fetched: " . $role);

    // Verify password
    if (password_verify($password, $hashed_password)) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $id;
        $_SESSION['role'] = $role;

        // Debugging: Check role
        error_log("Role after verification: " . $role);

        if ($role === 'admin') {
            header("Location: admin_dashboard.php");
            exit; // Ensure no further code execution after redirection
        } elseif ($role === 'accountant') {
            header("Location: accountant_dashboard.php");
            exit; // Ensure no further code execution after redirection
        } elseif ($role === 'staff') {
            header("Location: webform.html");
            exit; // Ensure no further code execution after redirection
        } else {
            error_log("Unhandled role: " . $role); // Log unhandled role
        }
    } else {
        $error = "Invalid username or password";
    }
}

$conn->close();

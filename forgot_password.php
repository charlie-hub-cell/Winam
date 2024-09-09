<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $token = bin2hex(random_bytes(50)); // Generate a random token

    // Update the user record with the reset token
    $stmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
    $stmt->bind_param("ss", $token, $email);

    if ($stmt->execute()) {
        // In a real application, you would send this link via email
        echo "Password reset link: http://example.com/reset_password.php?token=$token";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="forgot_password.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

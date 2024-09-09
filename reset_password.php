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
    $token = $_POST["token"];
    $new_password = password_hash($_POST["new_password"], PASSWORD_BCRYPT); // Hash the new password

    // Update the user record with the new password and remove the reset token
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
    $stmt->bind_param("ss", $new_password, $token);

    if ($stmt->execute()) {
        echo "Password reset successfully.";
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
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="reset_password.php" method="post">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>" required>
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
        <br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

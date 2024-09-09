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

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO submissions (purpose, amount, date, requested_by, status, disbursement_method, mobile_number, bank_account) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdssssss", $purpose, $amount, $date, $requested_by, $status, $disbursement_method, $mobile_number, $bank_account);
    
    // Set parameters and execute
    $purpose = $_POST["purpose"];
    $amount = $_POST["amount"];
    $date = $_POST["date"];
    $requested_by = $_POST["requested_by"];
    $status = 'pending'; // Initial status is always 'pending'
    $disbursement_method = $_POST["disbursement_method"];
    $mobile_number = $_POST["mobile_number"];
    $bank_account = $_POST["bank_account"];
    
    // Check if the statement executed successfully
    if ($stmt->execute()) {
        echo "Submission successful";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

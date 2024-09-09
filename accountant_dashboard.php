<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'accountant') {
    header("Location: login.php");
    exit;
}

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

// Check if form is submitted and update status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    
    // Update status in database for accountant dashboard
    $update_query = "UPDATE submissions SET status='$status' WHERE id='$id'";
    if ($conn->query($update_query) === TRUE) {
        echo "Status updated successfully";
    } else {
        echo "Error updating status: " . $conn->error;
    }

    // Update status in database for staff web form
    $staff_update_query = "UPDATE submissions SET status='$status' WHERE id='$id'";
    if ($conn->query($staff_update_query) === TRUE) {
        echo "Staff status updated successfully";
    } else {
        echo "Error updating staff status: " . $conn->error;
    }
}

// Fetch all requisitions
$result = $conn->query("SELECT * FROM submissions");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountant Dashboard</title>
    <style>
        /* Reset default browser styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        /* Page title styles */
        h2 {
            margin-bottom: 20px;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* Form styles */
        form {
            display: inline-block;
        }

        /* Select styles */
        select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
        }

        /* Submit button styles */
        input[type="submit"] {
            padding: 8px 20px;
            border: none;
            border-radius: 4px;
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Logout link styles */
        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #f44336;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <h2>Accountant Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    <a href="logout.php">Logout</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Purpose</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Requested By</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['purpose']); ?></td>
                <td><?php echo htmlspecialchars($row['amount']); ?></td>
                <td><?php echo htmlspecialchars($row['date']); ?></td>
                <td><?php echo htmlspecialchars($row['requested_by']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <select name="status">
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="declined">Declined</option>
                        </select>
                        <input type="submit" value="Update">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

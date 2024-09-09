<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
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

// Fetch user information
$user_id = $_SESSION['user_id'];
$user_info_sql = "SELECT * FROM users WHERE id = $user_id";
$user_info_result = $conn->query($user_info_sql);
$user_info = $user_info_result->fetch_assoc();

// Fetch all requisitions
$sql = "SELECT * FROM submissions";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 1200px;
            margin: 20px;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .header h2 {
            color: #333;
            margin: 0;
        }

        .header img {
            height: 50px;
            margin-right: 20px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-info p {
            margin: 5px 0;
            color: #666;
        }

        p {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Admin Dashboard</h2>
            
            <img src="admin_icon.png" alt="Admin Icon">
         
        </div>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
        <a href="logout.php">Logout</a>
        <h3>Requisitions</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Purpose</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Requested By</th>
                    <th>Mobile Number</th>
                    <th>Disbursement Method</th>
                    <th>Bank Account</th>
                    <th>Status</th>
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
                    <td><?php echo htmlspecialchars($row['mobile_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['disbursement_method']); ?></td>
                    <td><?php echo htmlspecialchars($row['bank_account']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
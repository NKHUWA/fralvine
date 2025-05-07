<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "frallvine_chickens";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$order = null;

if (isset($_GET['id'])) {
    $order_id = intval($_GET['id']);

    // Update status to completed (1)
    $update_sql = "UPDATE orders SET status = 1 WHERE id = $order_id";
    if ($conn->query($update_sql) === TRUE) {
        // Fetch order details
        $sql = "SELECT * FROM orders WHERE id = $order_id";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $order = $result->fetch_assoc();
        }
    } else {
        echo "Error updating order status: " . $conn->error;
        $conn->close();
        exit();
    }
} else {
    echo "No order selected.";
    $conn->close();
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Receipt - Frallvine Chickens</title>
    <style>
        body {
            font-family: Arial;
            margin: 40px;
        }
        .receipt-box {
            max-width: 600px;
            padding: 20px;
            border: 1px solid #eee;
            margin: auto;
            box-shadow: 0 0 10px #ccc;
        }
        h2 {
            text-align: center;
        }
        .receipt-details {
            margin-top: 20px;
        }
        .receipt-details table {
            width: 100%;
        }
        .receipt-details td {
            padding: 6px 0;
        }
        .print-btn, .back-btn {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-btn {
            background: #555;
            margin-left: 10px;
        }
        .print-btn:hover, .back-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

<div class="receipt-box">
    <h2>Frallvine Chickens</h2>
    <h3>Payment Receipt</h3>
    <hr>

    <?php if ($order): ?>
        <div class="receipt-details">
            <table>
                <tr><td><strong>Receipt No:</strong></td><td>#REC-<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></td></tr>
                <tr><td><strong>Customer Name:</strong></td><td><?php echo htmlspecialchars($order['customer_name']); ?></td></tr>
                <tr><td><strong>Product:</strong></td><td><?php echo htmlspecialchars($order['product']); ?></td></tr>
                <tr><td><strong>Quantity:</strong></td><td><?php echo $order['quantity']; ?></td></tr>
                <tr><td><strong>Total Paid:</strong></td><td>ZMW <?php echo number_format($order['total_price'], 2); ?></td></tr>
                <tr><td><strong>Date:</strong></td><td><?php echo $order['created_at']; ?></td></tr>
                <tr><td><strong>Status:</strong></td><td><?php echo $order['status'] == 1 ? 'Paid (Completed)' : 'Pending'; ?></td></tr>
            </table>
        </div>
        <button class="print-btn" onclick="window.print()">Print Receipt</button>
        <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
    <?php else: ?>
        <p>Order not found.</p>
    <?php endif; ?>
</div>

</body>
</html>

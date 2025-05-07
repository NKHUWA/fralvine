<?php
include('db_connection.php'); // Include your DB connection here

// Fetch all orders from the database
$sql = "SELECT * FROM orders ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome to the Admin Dashboard</h1>
    <nav>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <h2>All Orders</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <ul>
            <?php while ($order = mysqli_fetch_assoc($result)): ?>
                <li>
                    <p>Product: <?php echo $order['product']; ?></p>
                    <p>Quantity: <?php echo $order['quantity']; ?></p>
                    <p>Total Price: ZMW <?php echo number_format($order['total_price'], 2); ?></p>
                    <p>Customer Name: <?php echo $order['name']; ?></p>
                    <p>Phone: <?php echo $order['phone']; ?></p>
                    <hr>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>

    <a href="index.html">Back to Home</a>
</body>
</html>

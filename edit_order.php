<?php
include 'db_connection.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "Order ID not provided.";
    exit;
}

$order_id = intval($_GET['id']);
$message = "";

// Fetch order details
$sql = "SELECT * FROM orders WHERE id = $order_id";
$result = $conn->query($sql);
$order = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $conn->real_escape_string($_POST['customer_name']);
    $product = $conn->real_escape_string($_POST['product']);
    $quantity = intval($_POST['quantity']);
    $total_price = floatval($_POST['total_price']);
    $airtel_code = $conn->real_escape_string($_POST['airtel_code']);

    $update_sql = "UPDATE orders SET 
        customer_name = '$customer_name',
        product = '$product',
        quantity = $quantity,
        total_price = $total_price,
        airtel_code = '$airtel_code'
        WHERE id = $order_id";

    if ($conn->query($update_sql)) {
        $message = "Order updated successfully.";
    } else {
        $message = "Error updating order: " . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Order</title>
    <style>
        body { font-family: Arial; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; margin-top: 50px; border-radius: 10px; }
        input[type="text"], input[type="number"] { width: 100%; padding: 10px; margin: 5px 0 15px 0; border: 1px solid #ccc; border-radius: 4px; }
        input[type="submit"] { background-color: #ffa500; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        input[type="submit"]:hover { background-color: #e88e00; }
        a { text-decoration: none; color: #ffa500; }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Order</h2>

    <?php if ($order): ?>
        <?php if ($message): ?><p><strong><?= htmlspecialchars($message) ?></strong></p><?php endif; ?>
        <form method="POST">
            <label>Customer Name</label>
            <input type="text" name="customer_name" value="<?= htmlspecialchars($order['customer_name']) ?>" required>

            <label>Product</label>
            <input type="text" name="product" value="<?= htmlspecialchars($order['product']) ?>" required>

            <label>Quantity</label>
            <input type="number" name="quantity" value="<?= htmlspecialchars($order['quantity']) ?>" required>

            <label>Total Price</label>
            <input type="number" step="0.01" name="total_price" value="<?= htmlspecialchars($order['total_price']) ?>" required>

            <label>Airtel Code</label>
            <input type="text" name="airtel_code" value="<?= htmlspecialchars($order['airtel_code']) ?>">

            <input type="submit" value="Update Order">
        </form>
        <p><a href="admin_dashboard.php">← Back to Dashboard</a></p>
    <?php else: ?>
        <p>Order not found.</p>
    <?php endif; ?>
</div>
</body>
</html>

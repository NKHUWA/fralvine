<?php
// Check if the order session exists
if (!isset($_SESSION['order'])) {
    // If no order details are found in the session, redirect to the order page
    header("Location: order.php");
    exit();
}

// Collect order details from the session
$order = $_SESSION['order'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Fralvine Chickens</title>
    <link rel="stylesheet" href="assets/styles/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Order Confirmation</h1>
            <nav>
                <ul>
                    <li><a href="index.html">Home</a></li> <!-- This button will take the user to the home page -->
                    <li><a href="order.php">Place Another Order</a></li> <!-- Optional: Allow placing another order -->
                </ul>
            </nav>
        </div>
    </header>

    <section class="order-summary">
        <div class="container">
            <h2>Your Order</h2>
            <p>Product: <strong><?php echo ucfirst(str_replace('_', ' ', $order['product'])); ?></strong></p>
            <p>Quantity: <strong><?php echo $order['quantity']; ?></strong></p>
            <p>Price per unit: <strong>ZMW <?php echo number_format($order['price'], 2); ?></strong></p>
            <p>Total Price: <strong>ZMW <?php echo number_format($order['total_price'], 2); ?></strong></p>

            <h3>Customer Details</h3>
            <p>Name: <strong><?php echo $order['name']; ?></strong></p>
            <p>Phone: <strong><?php echo $order['phone']; ?></strong></p>

            <p>Thank you for your order! We will get back to you shortly.</p>
        </div>
    </section>
</body>
</html>

<?php
// After the confirmation is displayed, clear the session to avoid showing the same order again
unset($_SESSION['order']);
?>

<?php
include 'db_connection.php';

// Product list and prices
$products = [
    'broiler_live' => ['name' => 'Live Broiler Chicken', 'price' => 140.00, 'id' => 1],
    'broiler_dressed' => ['name' => 'Dressed Broiler Chicken', 'price' => 150.00, 'id' => 2],
    'village_live' => ['name' => 'Live Village Chicken', 'price' => 160.00, 'id' => 3],
    'village_dressed' => ['name' => 'Dressed Village Chicken', 'price' => 170.00, 'id' => 4],
];

// Get product from URL or default to broiler_live
$product_key = isset($_GET['product']) ? $_GET['product'] : 'broiler_live';

if (array_key_exists($product_key, $products)) {
    $product_name = $products[$product_key]['name'];
    $price_per_unit = $products[$product_key]['price'];
    $product_id = $products[$product_key]['id'];
} else {
    $product_name = "Unknown Product";
    $price_per_unit = 0.00;
    $product_id = null;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product = $product_name;
    $quantity = intval($_POST['quantity']);
    $total_price = $price_per_unit * $quantity;
    $customer_name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $airtel_code = trim($_POST['airtel_code']);

    // Insert into database
    $sql = "INSERT INTO orders (customer_name, product, quantity, total_price, order_date, airtel_code, product_id)
            VALUES (?, ?, ?, ?, NOW(), ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssidsi", $customer_name, $product, $quantity, $total_price, $airtel_code, $product_id);

    if ($stmt->execute()) {
        header("Location: thankyou.php");
        exit;
    } else {
        $message = "❌ Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order - Fralvine Chickens</title>
    <link rel="stylesheet" href="assets/styles/style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #fff4e6; color: #333; }
        header { background: orange; color: white; padding: 1em; text-align: center; }
        .container { max-width: 600px; margin: auto; }
        .order-details { background: #fff; padding: 20px; border: 2px solid #ffa500; margin-top: 20px; border-radius: 8px; }
        input, button { width: 100%; padding: 10px; margin: 10px 0; }
        button { background: #ffa500; color: white; border: none; cursor: pointer; }
        .follow-up { font-size: 0.9em; color: #555; background: #fff3cd; border: 1px solid #ffeeba; padding: 10px; margin-top: 10px; border-radius: 5px; }
        footer { text-align: center; padding: 1em; margin-top: 20px; background: orange; color: white; }
        .message { background: #e0ffe0; border: 1px solid green; padding: 10px; margin-top: 10px; color: green; }
    </style>
</head>
<body>

<header>
    <div class="container">
        <h1>Place Your Order</h1>
    </div>
</header>

<div class="container">
    <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>

    <section class="order-details">
        <h2>Order Summary</h2>
        <p><strong>Product:</strong> <?php echo $product_name; ?></p>
        <p><strong>Price per Unit:</strong> ZMW <?php echo number_format($price_per_unit, 2); ?></p>

        <form action="" method="POST">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="phone">Your Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1" required>

            <label for="airtel_code">Airtel Money Payment Code:</label>
            <input type="text" id="airtel_code" name="airtel_code" placeholder="Enter Airtel verification code" required>

            <button type="submit">Submit Order</button>

            <div class="follow-up">
                📞 For delivery updates or inquiries, please call: <strong>+260 770 817024</strong>
            </div>
        </form>

        <p><strong>Total Price:</strong> ZMW <span id="totalPrice"><?php echo number_format($price_per_unit, 2); ?></span></p>
    </section>
</div>

<footer>
    <p>&copy; 2025 Fralvine Family. All rights reserved. | Chadiza, Zambia</p>
</footer>

<script>
    const unitPrice = <?php echo $price_per_unit; ?>;
    const quantityInput = document.getElementById('quantity');
    const totalPriceSpan = document.getElementById('totalPrice');

    function updateTotal() {
        const qty = parseInt(quantityInput.value) || 1;
        const total = (unitPrice * qty).toFixed(2);
        totalPriceSpan.textContent = total;
    }

    quantityInput.addEventListener('input', updateTotal);
    window.addEventListener('DOMContentLoaded', updateTotal);
</script>

</body>
</html>

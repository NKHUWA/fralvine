<?php
include 'db_connection.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid order ID.");
}

$order_id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM orders WHERE id = $order_id");

if (!$result || $result->num_rows === 0) {
    die("Order not found.");
}

$order = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - Order #<?= $order['id'] ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            padding: 40px;
            color: #333;
        }
        .receipt-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .receipt-header {
            text-align: center;
            border-bottom: 2px solid #ffa500;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .receipt-header h2 {
            margin: 0;
            font-size: 28px;
            color: #ffa500;
        }
        .receipt-header small {
            color: #555;
            font-weight: 500;
        }
        .info {
            line-height: 1.8;
        }
        .info div {
            margin-bottom: 10px;
            font-size: 16px;
        }
        .info label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }
        .thank-you {
            text-align: center;
            margin-top: 30px;
            font-style: italic;
            color: #666;
        }
        .btn-print {
            display: block;
            width: 100%;
            margin-top: 25px;
            padding: 12px;
            background: #ffa500;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-print:hover {
            background: #e08e00;
        }
        @media print {
            .btn-print {
                display: none;
            }
            body {
                background: white;
            }
        }
    </style>
    <script>
        function printReceipt() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h2>🧾 Fralvine Chickens</h2>
            <small>Official Sales Receipt</small>
        </div>

        <div class="info">
            <div><label>Receipt #:</label> <?= $order['id'] ?></div>
            <div><label>Customer Name:</label> <?= htmlspecialchars($order['customer_name']) ?></div>
            <div><label>Product:</label> <?= htmlspecialchars($order['product']) ?></div>
            <div><label>Quantity:</label> <?= $order['quantity'] ?></div>
            <div><label>Total Amount:</label> ZMW <?= number_format($order['total_price'], 2) ?></div>
            <div><label>Order Date:</label> <?= date('d M Y, H:i', strtotime($order['order_date'])) ?></div>
            <div><label>Airtel Code:</label> <?= htmlspecialchars($order['airtel_code']) ?></div>
        </div>

        <div class="thank-you">
            Thank you for your business. We look forward to serving you again!
        </div>

        <button class="btn-print" onclick="printReceipt()">🖨️ Print Receipt</button>
    </div>
</body>
</html>

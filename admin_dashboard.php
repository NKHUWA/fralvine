<?php
include 'db_connection.php';
session_start();

// Redirect if admin is not logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $conn->query("DELETE FROM orders WHERE id = $delete_id");
    $_SESSION['message'] = 'Order deleted successfully';
    header("Location: admin_dashboard.php");
    exit;
}

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Get total records
$total_result = $conn->query("SELECT COUNT(*) as total FROM orders");
$total_orders = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_orders / $limit);

// Fetch paginated orders
$sql = "SELECT * FROM orders ORDER BY order_date DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
$orders = ($result && $result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];

$conn->close();

// Message
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">	
    <title>Admin Dashboard - Fralvine Chickens</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; }
        header, footer { background: #ffa500; color: white; padding: 20px; text-align: center; position: relative; }
        .container { width: 90%; max-width: 1000px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #ffa500; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn { padding: 8px 12px; background-color: #ffa500; color: white; border: none; border-radius: 4px; text-decoration: none; cursor: pointer; font-size: 14px; }
        .btn:hover { background-color: #e88e00; }
        .btn-danger { background-color: #dc3545; }
        .btn-danger:hover { background-color: #c82333; }
        .actions { display: flex; gap: 5px; }
        .top-right { position: absolute; top: 20px; right: 20px; }
        .message { padding: 10px; margin-bottom: 20px; border-radius: 4px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .pagination { margin-top: 20px; text-align: center; }
        .pagination a { margin: 0 5px; padding: 6px 10px; background: #ffa500; color: white; border-radius: 4px; text-decoration: none; }
        .pagination a.active { background: #e88e00; font-weight: bold; }
        .pagination a:hover { background: #e88e00; }
        @media print { .no-print { display: none; } }
    </style>
    <script>
        function printTable() {
            const printContents = document.getElementById('orders-table').outerHTML;
            const originalContents = document.body.innerHTML;
            document.body.innerHTML = 
                '<html><head><title>Print Orders</title><style>' +
                'table { width: 100%; border-collapse: collapse; }' +
                'th, td { padding: 8px; border: 1px solid #ddd; }' +
                'th { background-color: #ffa500; color: white; }' +
                '</style></head><body>' + printContents + '</body></html>';
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</head>
<body>

<header>
    <h1>Admin Dashboard - Fralvine Chickens</h1>
    <a href="logout.php" class="btn top-right">Logout 🔒</a>
</header>

<div class="container">
    <?php if ($message): ?>
        <div class="message success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <h2>All Orders</h2>
    <div class="no-print" style="float: right;">
        <button class="btn print-button" onclick="printTable()">🖨️ Print</button>
    </div>

    <?php if (count($orders) > 0): ?>
        <table id="orders-table">
            <thead>
                <tr>
                    <th>ID</th><th>Name</th><th>Product</th><th>Qty</th><th>Total (ZMW)</th><th>Date</th><th>Airtel Code</th><th class="no-print">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['customer_name']) ?></td>
                    <td><?= htmlspecialchars($order['product']) ?></td>
                    <td><?= $order['quantity'] ?></td>
                    <td><?= number_format($order['total_price'], 2) ?></td>
                    <td><?= $order['order_date'] ?></td>
                    <td><?= htmlspecialchars($order['airtel_code']) ?></td>
                    <td class="no-print actions">
                        <a class="btn" href="receipt.php?id=<?= $order['id'] ?>" target="_blank">🧾 Receipt</a>
                        <a class="btn btn-danger" href="admin_dashboard.php?delete=<?= $order['id'] ?>" onclick="return confirm('Are you sure you want to delete this order?');">🗑️ Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination no-print">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>

    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; <?= date('Y') ?> Fralvine Family | Chadiza, Zambia</p>
</footer>

</body>
</html>

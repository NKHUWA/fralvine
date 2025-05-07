<?php
include 'db_connection.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $order_id = intval($_GET['id']);

    $sql = "DELETE FROM orders WHERE id = $order_id";

    if ($conn->query($sql)) {
        header("Location: admin_dashboard.php?msg=deleted");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "No ID provided.";
}
$conn->close();
?>

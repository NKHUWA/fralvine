<?php
// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/styles/style.css">
</head>
<body>
    <h2>Welcome to Admin Dashboard</h2>
    <p>Hello, <?php echo $_SESSION['admin_username']; ?>!</p>
    
    <p><a href="process_logout.php">Logout</a></p>
</body>
</html>

<?php
// Destroy the session to log the admin out
session_destroy();

// Redirect to the login page
header("Location: admin_login.php");
exit();
?>

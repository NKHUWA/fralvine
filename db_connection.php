<?php

// Database connection variables
$servername = "localhost"; // Update if needed
$username = "root";        // Update if needed
$password = "";            // Update if needed (set password if applicable)
$dbname = "fralvine_db";   // Corrected database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

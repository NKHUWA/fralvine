<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "frallvine_chickens";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Sign Up</title>
    <link rel="stylesheet" href="assets/styles/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff3e0;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #e65100;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .signup-container {
            max-width: 400px;
            margin: 40px auto;
            padding: 30px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .signup-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #e65100;
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        form button {
            width: 100%;
            padding: 12px;
            background-color: #ff6f00;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #e65100;
        }

        footer {
            text-align: center;
            padding: 15px;
            background-color: #e65100;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

    </style>
</head>
<body>
    <header>
        <h1>Fralvine Chickens - User Registration</h1>
    </header>

    <div class="signup-container">
        <h2>Create Your Account</h2>
        <form action="user_signup.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Sign Up</button>
        </form>
    </div>

    <footer>
    <p>&copy; 2025 Fralvine Family. All rights reserved. | Contact us: +260 770 817024 | Chadiza, Zambia</p>
    </footer>
</body>
</html>

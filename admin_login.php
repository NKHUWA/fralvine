<?php
session_start();
include 'db_connection.php';

// Check if the admin is already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_dashboard.php");
    exit();
}

// Handle form submission
$error_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token if implemented
    // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    //     die("CSRF token validation failed");
    // }

    // Validate inputs
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $error_message = "Both username and password are required.";
    } else {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $entered_password = $_POST['password']; // Don't escape passwords before verification

        // Use prepared statement to prevent SQL injection
        $sql = "SELECT id, username, password FROM admin WHERE username = ?";
       $stmt = mysqli_prepare($conn, "SELECT admin_id, username, password FROM admin WHERE username=?");
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) === 1) {
                $admin = mysqli_fetch_assoc($result);

                if (password_verify($entered_password, $admin['password'])) {
                    // Regenerate session ID to prevent session fixation
                    session_regenerate_id(true);
                    
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    $_SESSION['last_activity'] = time();
                    
                    // Set a secure session cookie
                    setcookie(session_name(), session_id(), [
                        'expires' => time() + 3600, // 1 hour
                        'path' => '/',
                        'domain' => '', // your domain
                        'secure' => true, // if using HTTPS
                        'httponly' => true,
                        'samesite' => 'Strict'
                    ]);
                    
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    $error_message = "Invalid credentials."; // Generic message for security
                    error_log("Failed login attempt for username: $username");
                }
            } else {
                $error_message = "Invalid credentials."; // Generic message for security
                error_log("Failed login attempt for username: $username");
            }
            
            mysqli_stmt_close($stmt);
        } else {
            $error_message = "Database error. Please try again later.";
            error_log("Database error: " . mysqli_error($conn));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Fralvine Chickens</title>
    <style>
        :root {
            --primary-color: #ff6600;
            --secondary-color: #0077cc;
            --error-color: #dc3545;
            --text-color: #333;
            --light-gray: #f4f7fa;
            --white: #fff;
            --border-radius: 8px;
            --box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-gray);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: var(--text-color);
        }

        .home-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: var(--secondary-color);
            color: var(--white);
            padding: 10px 20px;
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .home-button:hover {
            background-color: #005fa3;
            transform: translateY(-2px);
        }

        .login-container {
            background-color: var(--white);
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 100%;
            max-width: 400px;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo {
            width: 80px;
            margin-bottom: 1rem;
        }

        h2 {
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.25rem;
            text-align: left;
        }

        label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
            color: var(--text-color);
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: var(--secondary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 119, 204, 0.2);
        }

        .btn {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 0.75rem;
            border: none;
            border-radius: var(--border-radius);
            width: 100%;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn:hover {
            background-color: #e55d00;
            transform: translateY(-2px);
        }

        .error-message {
            color: var(--error-color);
            background-color: #f8d7da;
            padding: 0.75rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.25rem;
            border: 1px solid #f5c6cb;
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .forgot-password {
            display: block;
            margin-top: 1rem;
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
                margin: 0 1rem;
            }
            
            .home-button {
                top: 15px;
                left: 15px;
                padding: 8px 15px;
            }
        }
    </style>
</head>
<body>

<!-- Home Button -->
<a class="home-button" href="index.html">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z"/>
    </svg>
    Home
</a>

<div class="login-container">
    <!-- You can add your logo here -->
    <!-- <img src="logo.png" alt="Fralvine Chickens" class="logo"> -->
    
    <h2>Admin Login</h2>

    <?php if (!empty($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <!-- Add CSRF token if needed -->
        <!-- <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>"> -->
        
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required autocomplete="username" autofocus>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
        </div>
        
        <button type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
            </svg>
            Login
        </button>
        
        <!-- Uncomment if you have password recovery -->
        <!-- <a href="forgot_password.php" class="forgot-password">Forgot password?</a> -->
    </form>
</div>

</body>
</html>
<?php
/**
 * Login Page
 * 
 * This page handles user authentication for the dashboard system.
 * It provides a secure login form with session management and
 * redirects users to the appropriate dashboard after successful login.
 * 
 * @author Dashboard System
 * @version 1.0
 * @since 2025-09-17
 */

// Start session for authentication
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Initialize error message
$error_message = '';

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error_message = 'Please enter both username and password';
    } else {
        // Simple authentication (in production, use proper password hashing and database)
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['user_id'] = 1;
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit();
        } else {
            $error_message = 'Invalid username or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - JONELTA Dashboard</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <img src="img/SS.png" alt="JONELTA Logo" class="login-logo">
                <p>UNIVERSITY OF PERPETUAL HELP SYSTEM JONELTA</p>
                <h1>Management Dashboard</h1>
                <p>Sign in to access the dashboard</p>
            </div>
            
            <?php if ($error_message): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                
                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                
                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In
                </button>
            </form>
            <!--
            <div class="login-footer">
                <p>Default credentials: admin / admin123</p>
            </div>
            -->
        </div>
    </div>
</body>
</html>

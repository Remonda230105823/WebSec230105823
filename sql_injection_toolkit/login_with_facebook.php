<?php
// Initialize session
session_start();

// Redirect to dashboard if already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

// Configuration
$fb_app_id = "your_facebook_app_id"; // Replace with your real App ID in production
$redirect_uri = "http://" . $_SERVER['HTTP_HOST'] . "/sql_injection_toolkit/facebook_callback.php";
$fb_login_url = "https://www.facebook.com/v18.0/dialog/oauth?client_id={$fb_app_id}&redirect_uri=" . urlencode($redirect_uri) . "&state=" . bin2hex(random_bytes(16)) . "&scope=email,public_profile";

// Handle traditional login (vulnerable to SQL injection for demonstration)
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // DELIBERATELY VULNERABLE CODE (for educational purposes only)
    // This is intentionally vulnerable to SQL injection
    $conn = new mysqli("localhost", "root", "", "websec_demo");
    
    if ($conn->connect_error) {
        $login_error = "Database connection failed: " . $conn->connect_error;
    } else {
        // WARNING: This query is intentionally vulnerable to SQL injection
        // DO NOT USE THIS IN PRODUCTION
        $query = "SELECT id, username FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($query);
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Set session variables
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['login_method'] = 'traditional';
            
            // Redirect to dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            $login_error = "Invalid username or password";
            
            // Add a hint for SQL injection (for educational purposes)
            if (strpos($username, "'") !== false || strpos($password, "'") !== false) {
                $login_error .= " (Hint: SQL injection might be possible)";
            }
        }
        
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SQL Injection Toolkit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 450px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            padding: 2rem;
            margin: 3rem auto;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .login-or {
            position: relative;
            text-align: center;
            margin: 1.5rem 0;
        }
        .login-or:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #dee2e6;
        }
        .login-or span {
            position: relative;
            background: white;
            padding: 0 1rem;
        }
        .facebook-btn {
            background-color: #3b5998;
            color: white;
        }
        .facebook-btn:hover {
            background-color: #2d4373;
            color: white;
        }
        .security-notice {
            font-size: 0.8rem;
            margin-top: 2rem;
            background-color: #fff3cd;
            border-radius: 5px;
            padding: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-logo">
                <i class="bi bi-shield-lock text-primary" style="font-size: 3rem;"></i>
                <h2>SQL Injection Toolkit</h2>
                <p class="text-muted">Educational Security Demo</p>
            </div>
            
            <?php if (!empty($login_error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle-fill me-2"></i><?php echo htmlspecialchars($login_error); ?>
                </div>
            <?php endif; ?>
            
            <!-- Facebook Login -->
            <div class="d-grid gap-2">
                <a href="<?php echo htmlspecialchars($fb_login_url); ?>" class="btn facebook-btn btn-lg">
                    <i class="bi bi-facebook me-2"></i>Login with Facebook (Secure)
                </a>
                <div class="small text-center text-muted">OAuth-based authentication - not vulnerable to SQL injection</div>
            </div>
            
            <div class="login-or">
                <span>OR</span>
            </div>
            
            <!-- Traditional Login (Vulnerable) -->
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login (Vulnerable)
                    </button>
                    <div class="small text-center text-danger">Traditional login - vulnerable to SQL injection</div>
                </div>
            </form>
            
            <div class="security-notice">
                <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>
                <strong>Educational Notice:</strong> This application is designed to demonstrate security vulnerabilities and OAuth authentication. The traditional login is deliberately vulnerable to SQL injection for educational purposes.
            </div>
            
            <div class="mt-3 text-center">
                <small>Test account: <code>admin</code> / <code>password123</code><br>
                Try SQL injection: <code>admin' --</code></small>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
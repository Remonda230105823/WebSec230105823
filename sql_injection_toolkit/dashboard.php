<?php
// Start the session
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login_with_facebook.php");
    exit;
}

// Function to safely output data
function safeEcho($data) {
    echo htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Handle logout
if (isset($_GET['logout'])) {
    // Clear all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login page
    header("Location: login_with_facebook.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Injection Toolkit - Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .profile {
            display: flex;
            align-items: center;
        }
        .profile-picture {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            object-fit: cover;
            border: 2px solid #4267B2;
        }
        .profile-info {
            line-height: 1.2;
        }
        .logout-btn {
            background-color: #4267B2;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
        }
        .logout-btn:hover {
            background-color: #365899;
        }
        .card {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .card h2 {
            margin-top: 0;
            color: #4267B2;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .card-content {
            display: flex;
            flex-wrap: wrap;
        }
        .card-item {
            flex: 1;
            min-width: 250px;
            margin: 10px;
        }
        .key {
            font-weight: bold;
            color: #555;
        }
        .value {
            margin-left: 10px;
            word-break: break-all;
        }
        .security-warning {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            border-left: 5px solid #ffeeba;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="profile">
                <?php if (!empty($_SESSION['profile_picture'])): ?>
                    <img class="profile-picture" src="<?php safeEcho($_SESSION['profile_picture']); ?>" alt="Profile Picture">
                <?php else: ?>
                    <img class="profile-picture" src="https://via.placeholder.com/50" alt="Default Profile">
                <?php endif; ?>
                
                <div class="profile-info">
                    <h3><?php safeEcho($_SESSION['username']); ?></h3>
                    <small>Logged in via <?php safeEcho($_SESSION['login_method']); ?></small>
                </div>
            </div>
            <a href="?logout=1" class="logout-btn">Logout</a>
        </div>
        
        <div class="card">
            <h2>Welcome to the SQL Injection Toolkit</h2>
            <p>This is a secure dashboard that displays your login information. Your personal data is protected here.</p>
            
            <div class="card-content">
                <div class="card-item">
                    <p><span class="key">Username:</span> <span class="value"><?php safeEcho($_SESSION['username']); ?></span></p>
                    <?php if (!empty($_SESSION['email'])): ?>
                        <p><span class="key">Email:</span> <span class="value"><?php safeEcho($_SESSION['email']); ?></span></p>
                    <?php endif; ?>
                    <p><span class="key">Login Method:</span> <span class="value"><?php safeEcho($_SESSION['login_method']); ?></span></p>
                    <?php if ($_SESSION['login_method'] === 'facebook'): ?>
                        <p><span class="key">Facebook User ID:</span> <span class="value"><?php safeEcho($_SESSION['fb_user_id']); ?></span></p>
                    <?php endif; ?>
                </div>
                
                <div class="card-item">
                    <p><span class="key">Login Time:</span> <span class="value"><?php echo date('Y-m-d H:i:s'); ?></span></p>
                    <p><span class="key">Session ID:</span> <span class="value"><?php echo session_id(); ?></span></p>
                    <p><span class="key">IP Address:</span> <span class="value"><?php safeEcho($_SERVER['REMOTE_ADDR']); ?></span></p>
                </div>
            </div>
        </div>
        
        <div class="card">
            <h2>Available Tools</h2>
            <p>The following tools are available for exploring SQL injection vulnerabilities:</p>
            <ul>
                <li><a href="vulnerability_demo.php">SQL Injection Vulnerability Demo</a></li>
                <li><a href="prevention_techniques.php">Prevention Techniques</a></li>
                <li><a href="secure_query_builder.php">Secure Query Builder</a></li>
            </ul>
        </div>
        
        <div class="security-warning">
            <strong>Educational Purpose Only:</strong> This toolkit is designed for educational purposes to demonstrate SQL injection vulnerabilities and protection methods. Do not use these techniques on systems without proper authorization.
        </div>
    </div>
</body>
</html> 
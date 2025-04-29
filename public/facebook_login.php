<?php
// Start session - required for OAuth flow
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facebook Login | Separate Authentication System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            max-width: 500px;
            margin: 80px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            width: 80px;
            margin-bottom: 15px;
        }
        .facebook-btn {
            background-color: #1877f2;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            padding: 12px 20px;
            width: 100%;
            cursor: pointer;
            margin: 20px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .facebook-btn:hover {
            background-color: #166fe5;
            color: white;
            text-decoration: none;
        }
        .facebook-icon {
            margin-right: 10px;
            font-size: 20px;
            font-weight: bold;
        }
        .note {
            background-color: #f7f7f7;
            border-left: 4px solid #1877f2;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
        }
        .security-info {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        h5 {
            color: #1877f2;
        }
        .instruction {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
            font-family: monospace;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <!-- Display errors if any -->
            <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
            <?php endif; ?>
            
            <div class="header">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" alt="Facebook Logo" class="logo">
                <h3>Facebook Login</h3>
                <p class="text-muted">Separate Authentication System</p>
            </div>
            
            <div class="note">
                <strong>Important:</strong> This Facebook login implementation is completely separate from the 
                SQL injection demonstration features. It uses OAuth 2.0 for secure authentication.
            </div>
            
            <a href="facebook_auth.php" class="facebook-btn">
                <span class="facebook-icon">f</span> Continue with Facebook
            </a>
            
            <p class="text-center text-muted">This will redirect you to Facebook to complete authentication</p>
            
            <div class="security-info">
                <h5>How This Works</h5>
                <p>This implementation demonstrates OAuth 2.0 authentication with Facebook:</p>
                <ol>
                    <li>When you click the button, you'll be redirected to Facebook</li>
                    <li>After authenticating with Facebook, you'll be returned to our callback URL</li>
                    <li>The callback verifies your identity and creates/updates your account</li>
                    <li>You're logged in securely without exposing your password to this site</li>
                </ol>
                
                <h5>Developer Notes</h5>
                <p>To use this in production:</p>
                <div class="instruction">
                    1. Create a Facebook App at developers.facebook.com<br>
                    2. Update the App ID and Secret in facebook_auth.php<br>
                    3. Configure Valid OAuth Redirect URIs in Facebook Developer Console<br>
                    4. Ensure your database has the facebook_id and facebook_token fields
                </div>
            </div>
            
            <div class="mt-4 text-center">
                <a href="/login" class="text-decoration-none">Return to regular login</a>
            </div>
        </div>
    </div>
</body>
</html> 
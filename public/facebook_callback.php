<?php
/**
 * Facebook Authentication Callback Handler
 * 
 * This script processes the OAuth 2.0 callback from Facebook.
 * It verifies the authentication, creates or updates user accounts,
 * and establishes a secure session.
 * 
 * This implementation is completely separate from the SQL injection
 * demonstration features of the main application.
 */

// Start session to access state parameter
session_start();

// Facebook Application Configuration - MUST match facebook_auth.php
$fb_app_id = '123456789012345'; // Replace with your actual Facebook App ID
$fb_app_secret = 'your_app_secret'; // Replace with your actual Facebook App Secret
$fb_redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/facebook_callback.php';

// Error handling - Facebook returns errors in the query string
if (isset($_GET['error'])) {
    $error_code = htmlspecialchars($_GET['error']);
    $error_reason = htmlspecialchars($_GET['error_reason'] ?? 'Unknown reason');
    $error_description = htmlspecialchars($_GET['error_description'] ?? 'No description provided');
    
    // Log the error
    error_log("Facebook OAuth Error: $error_code - $error_reason - $error_description");
    
    // Redirect with error message
    header("Location: /facebook_login.php?error=Authentication failed: $error_description");
    exit;
}

// CSRF protection - validate state parameter 
if (!isset($_GET['state']) || !isset($_SESSION['fb_state']) || $_GET['state'] !== $_SESSION['fb_state']) {
    // Possible CSRF attack - log it and redirect
    error_log("CSRF attack detected: Invalid state parameter in Facebook callback");
    header("Location: /facebook_login.php?error=Security validation failed");
    exit;
}

// Clear the state parameter from session as it's no longer needed
unset($_SESSION['fb_state']);

// Verify authorization code exists
if (!isset($_GET['code'])) {
    error_log("No authorization code received from Facebook");
    header("Location: /facebook_login.php?error=Missing authorization code");
    exit;
}

// Exchange authorization code for an access token
$token_url = "https://graph.facebook.com/v18.0/oauth/access_token";
$token_params = [
    'client_id' => $fb_app_id,
    'client_secret' => $fb_app_secret,
    'redirect_uri' => $fb_redirect_uri,
    'code' => $_GET['code']
];

// Initialize cURL session for token request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $token_url . '?' . http_build_query($token_params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$token_response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    $curl_error = curl_error($ch);
    curl_close($ch);
    error_log("Facebook token request failed: $curl_error");
    header("Location: /facebook_login.php?error=Connection to Facebook failed");
    exit;
}

curl_close($ch);
$token_data = json_decode($token_response, true);

// Verify we received an access token
if (!isset($token_data['access_token'])) {
    $error = json_encode($token_data);
    error_log("Failed to get access token from Facebook. Response: $error");
    header("Location: /facebook_login.php?error=Could not obtain access token");
    exit;
}

$access_token = $token_data['access_token'];

// Request user data from Facebook Graph API
$graph_url = "https://graph.facebook.com/v18.0/me";
$fields = "id,name,email,picture.type(large)";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$graph_url?fields=$fields&access_token=$access_token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$graph_response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    $curl_error = curl_error($ch);
    curl_close($ch);
    error_log("Facebook Graph API request failed: $curl_error");
    header("Location: /facebook_login.php?error=Could not retrieve user data");
    exit;
}

curl_close($ch);
$user_data = json_decode($graph_response, true);

// Verify user data was received
if (!isset($user_data['id'])) {
    $error = json_encode($user_data);
    error_log("Failed to get user data from Facebook. Response: $error");
    header("Location: /facebook_login.php?error=Invalid user data received");
    exit;
}

// Extract user information
$facebook_id = $user_data['id'];
$name = $user_data['name'] ?? 'Facebook User';
// Email may not be available if user didn't grant permission
$email = $user_data['email'] ?? "$facebook_id@facebook.com";
$profile_picture = isset($user_data['picture']['data']['url']) ? $user_data['picture']['data']['url'] : '';

// Database connection details - adjust these to match your configuration
$db_host = "localhost";
$db_user = "root";      // Replace with your database username
$db_pass = "";          // Replace with your database password
$db_name = "laravel";   // Replace with your database name

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Begin transaction for data consistency
    $pdo->beginTransaction();
    
    // Check if user exists by facebook_id first, then by email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE facebook_id = :facebook_id");
    $stmt->execute(['facebook_id' => $facebook_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // If not found by facebook_id, try email
    if (!$user) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    if ($user) {
        // User exists - update their Facebook information
        $stmt = $pdo->prepare("
            UPDATE users SET 
                facebook_id = :facebook_id, 
                facebook_token = :facebook_token, 
                login_method = 'facebook',
                profile_picture = :profile_picture,
                updated_at = NOW() 
            WHERE id = :user_id
        ");
        
        $stmt->execute([
            'facebook_id' => $facebook_id,
            'facebook_token' => $access_token,
            'profile_picture' => $profile_picture,
            'user_id' => $user['id']
        ]);
        
        $user_id = $user['id'];
    } else {
        // User doesn't exist - create new account
        $stmt = $pdo->prepare("
            INSERT INTO users (
                name, 
                email, 
                password, 
                facebook_id, 
                facebook_token, 
                login_method, 
                profile_picture,
                created_at, 
                updated_at
            ) VALUES (
                :name, 
                :email, 
                :password, 
                :facebook_id, 
                :facebook_token, 
                'facebook',
                :profile_picture,
                NOW(), 
                NOW()
            )
        ");
        
        // Generate random secure password
        $random_password = bin2hex(random_bytes(12));
        $hashed_password = password_hash($random_password, PASSWORD_DEFAULT);
        
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $hashed_password,
            'facebook_id' => $facebook_id,
            'facebook_token' => $access_token,
            'profile_picture' => $profile_picture
        ]);
        
        $user_id = $pdo->lastInsertId();
        
        // Check if the users table has roles - if so, assign Customer role
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM information_schema.columns 
            WHERE table_schema = :db_name
            AND table_name = 'model_has_roles'
        ");
        $stmt->execute(['db_name' => $db_name]);
        
        if ($stmt->fetchColumn() > 0) {
            // Get the Customer role ID
            $stmt = $pdo->prepare("SELECT id FROM roles WHERE name = 'Customer'");
            $stmt->execute();
            $role = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($role) {
                // Assign Customer role to the new user
                $stmt = $pdo->prepare("
                    INSERT INTO model_has_roles (role_id, model_id, model_type) 
                    VALUES (:role_id, :user_id, 'App\\Models\\User')
                ");
                $stmt->execute([
                    'role_id' => $role['id'],
                    'user_id' => $user_id
                ]);
            }
        }
    }
    
    // Commit transaction
    $pdo->commit();
    
    // Create session for the authenticated user
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    $_SESSION['login_method'] = 'facebook';
    $_SESSION['logged_in'] = true;
    $_SESSION['fb_user_id'] = $facebook_id;
    $_SESSION['profile_picture'] = $profile_picture;
    
    // For your security logs
    $log_message = sprintf(
        "[%s] User '%s' (ID: %s) logged in via Facebook (FB_ID: %s)", 
        date('Y-m-d H:i:s'),
        $name,
        $user_id,
        $facebook_id
    );
    
    // Write to log file
    file_put_contents('facebook_logins.log', $log_message . PHP_EOL, FILE_APPEND);
    
    // Redirect to home page or dashboard
    header("Location: /");
    exit;
    
} catch (PDOException $e) {
    // Roll back transaction if active
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // Log the error
    $error_message = "Database error in Facebook authentication: " . $e->getMessage();
    error_log($error_message);
    
    // Redirect with generic error (don't expose DB details to users)
    header("Location: /facebook_login.php?error=Database error occurred");
    exit;
} catch (Exception $e) {
    // Handle any other exceptions
    error_log("Unexpected error in Facebook authentication: " . $e->getMessage());
    header("Location: /facebook_login.php?error=An unexpected error occurred");
    exit;
}
?> 
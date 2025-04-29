<?php
/**
 * Facebook Authentication Initiation Script
 * 
 * This script initiates the OAuth 2.0 flow with Facebook.
 * It's completely separate from the SQL injection demonstration features.
 */

// Start session to maintain state
session_start();

// Clear any previous errors
if (isset($_SESSION['fb_error'])) {
    unset($_SESSION['fb_error']);
}

// Facebook Application Configuration
// IMPORTANT: Replace these with your actual Facebook Developer credentials
$fb_app_id = '123456789012345'; // Replace with your actual Facebook App ID
$fb_app_secret = 'your_app_secret'; // Replace with your actual Facebook App Secret

// Configure the redirect URI - this must match what you register in Facebook Developer Console
$fb_redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/facebook_callback.php';

// Generate a random state parameter to prevent CSRF attacks
$state = bin2hex(random_bytes(16));

// Store state in session for validation in the callback
$_SESSION['fb_state'] = $state;

// Build the Facebook login URL with appropriate permissions
$fb_login_url = "https://www.facebook.com/v18.0/dialog/oauth"
    . "?client_id=" . urlencode($fb_app_id)
    . "&redirect_uri=" . urlencode($fb_redirect_uri)
    . "&state=" . urlencode($state)
    . "&scope=email,public_profile"; // Minimum permissions needed

// Log the attempt (optional, for monitoring)
$log_file = fopen("facebook_attempts.log", "a");
if ($log_file) {
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    fwrite($log_file, "[$timestamp] IP: $ip | Agent: $user_agent\n");
    fclose($log_file);
}

// Redirect to Facebook for authentication
header("Location: " . $fb_login_url);
exit;
?> 
<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Clear Facebook session variables
if (isset($_SESSION['fb_user'])) {
    unset($_SESSION['fb_user']);
}

if (isset($_SESSION['fb_access_token'])) {
    unset($_SESSION['fb_access_token']);
}

if (isset($_SESSION['fb_state'])) {
    unset($_SESSION['fb_state']);
}

// In a real application, we might revoke the Facebook access token here
// $fb->delete('/me/permissions', [], $_SESSION['fb_access_token']);

// Redirect back to the login page
header('Location: facebook_login.php');
exit;
?> 
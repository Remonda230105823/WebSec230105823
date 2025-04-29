<?php
require_once 'common.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Simulated Facebook App credentials (would be in a secure config file in a real app)
$fb_app_id = '123456789012345';
$fb_app_secret = 'abcdef0123456789abcdef0123456789';
$fb_redirect_uri = 'http://localhost/sql_injection_toolkit/facebook_callback.php';

// Check if this is a simulation request
if (isset($_GET['simulate']) && $_GET['simulate'] === 'true') {
    // Generate a fake access token for demonstration
    $access_token = bin2hex(random_bytes(32));
    
    // Simulate user data that would come from Facebook
    $user_data = [
        'id' => '1000' . mt_rand(1000000, 9999999),
        'name' => 'Demo User',
        'email' => 'demo.user@example.com',
        'picture' => [
            'data' => [
                'url' => 'https://via.placeholder.com/150'
            ]
        ]
    ];
    
    // Store in session
    $_SESSION['fb_user'] = [
        'id' => $user_data['id'],
        'name' => $user_data['name'],
        'email' => $user_data['email'],
        'picture' => $user_data['picture']['data']['url']
    ];
    $_SESSION['fb_access_token'] = $access_token;
    
    // Redirect to success page
    header('Location: login_with_facebook.php');
    exit;
}

// Handle error responses
if (isset($_GET['error'])) {
    $error_message = "Facebook login error: " . htmlspecialchars($_GET['error_description'] ?? $_GET['error']);
    $_SESSION['fb_error'] = $error_message;
    header("Location: login_with_facebook.php");
    exit;
}

// Check for authorization code and state parameter
if (!isset($_GET['code']) || !isset($_GET['state'])) {
    $_SESSION['fb_error'] = "Invalid OAuth response";
    header("Location: login_with_facebook.php");
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

// Make the HTTP request to exchange code for token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $token_url . '?' . http_build_query($token_params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
$token_response = curl_exec($ch);

if (curl_errno($ch)) {
    $_SESSION['fb_error'] = "Token request failed: " . curl_error($ch);
    curl_close($ch);
    header("Location: login_with_facebook.php");
    exit;
}

curl_close($ch);
$token_data = json_decode($token_response, true);

if (!isset($token_data['access_token'])) {
    $_SESSION['fb_error'] = "Failed to get access token";
    header("Location: login_with_facebook.php");
    exit;
}

$access_token = $token_data['access_token'];

// Get user info from Facebook Graph API
$graph_url = "https://graph.facebook.com/v18.0/me?fields=id,name,email,picture.type(large)";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $graph_url . "&access_token=" . $access_token);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
$user_response = curl_exec($ch);

if (curl_errno($ch)) {
    $_SESSION['fb_error'] = "User data request failed: " . curl_error($ch);
    curl_close($ch);
    header("Location: login_with_facebook.php");
    exit;
}

curl_close($ch);
$user_data = json_decode($user_response, true);

if (!isset($user_data['id'])) {
    $_SESSION['fb_error'] = "Failed to get user data";
    header("Location: login_with_facebook.php");
    exit;
}

// Set session variables for the logged-in user
$_SESSION['logged_in'] = true;
$_SESSION['login_method'] = 'facebook';
$_SESSION['fb_user_id'] = $user_data['id'];
$_SESSION['username'] = $user_data['name'] ?? 'Facebook User';
$_SESSION['email'] = $user_data['email'] ?? '';
$_SESSION['profile_picture'] = $user_data['picture']['data']['url'] ?? '';
$_SESSION['access_token'] = $access_token;

// You could store the user in your database here if needed
// For this demo, we'll just use session data

// Log the successful login (for educational purposes)
$log_file = fopen("facebook_logins.log", "a");
fwrite($log_file, date('Y-m-d H:i:s') . " - User logged in: " . $_SESSION['username'] . " (ID: " . $_SESSION['fb_user_id'] . ")\n");
fclose($log_file);

// Redirect to dashboard
header("Location: dashboard.php");
exit;
?>

<div class="card">
    <div class="card-header bg-danger text-white">Authentication Error</div>
    <div class="card-body">
        <h4>Facebook Authentication Failed</h4>
        <p>The Facebook authentication process did not complete successfully. This could be due to:</p>
        <ul>
            <li>Missing or invalid parameters in the callback URL</li>
            <li>User denied the Facebook login permissions</li>
            <li>The 'state' parameter doesn't match (CSRF protection)</li>
            <li>Error in the Facebook API response</li>
        </ul>
        
        <div class="mt-3">
            <a href="login_with_facebook.php" class="btn btn-primary">Return to Login Page</a>
        </div>
    </div>
</div>

<?php
includeFooter();
?> 
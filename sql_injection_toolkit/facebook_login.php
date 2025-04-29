<?php
require_once 'common.php';

// Simulated Facebook App credentials (in a real app, these would be in .env file)
$fb_app_id = '123456789012345';
$fb_app_secret = 'abcdef0123456789abcdef0123456789';
$fb_redirect_uri = 'http://localhost/sql_injection_toolkit/facebook_callback.php';

// Step 1: Generate Facebook login URL
function getFacebookLoginUrl() {
    global $fb_app_id, $fb_redirect_uri;
    
    // Generate a random state parameter to prevent CSRF attacks
    $state = md5(uniqid(rand(), true));
    $_SESSION['fb_state'] = $state;
    
    // Build the Facebook OAuth URL
    $fb_login_url = "https://www.facebook.com/v12.0/dialog/oauth"
        . "?client_id=" . urlencode($fb_app_id)
        . "&redirect_uri=" . urlencode($fb_redirect_uri)
        . "&state=" . urlencode($state)
        . "&scope=email,public_profile";
    
    return $fb_login_url;
}

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is already logged in via Facebook
$fb_logged_in = isset($_SESSION['fb_user']);

// Start HTML output
includeHeader('Facebook Login Demo');
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Facebook Login Integration</h3>
            </div>
            <div class="card-body">
                <?php if ($fb_logged_in): ?>
                    <div class="alert alert-success">
                        <h4>Successfully Logged In with Facebook!</h4>
                        <p>Welcome, <?= htmlspecialchars($_SESSION['fb_user']['name']) ?>!</p>
                        <p>Email: <?= htmlspecialchars($_SESSION['fb_user']['email']) ?></p>
                        <p>Facebook ID: <?= htmlspecialchars($_SESSION['fb_user']['id']) ?></p>
                        <p>This is a simulation of a successful Facebook login.</p>
                    </div>
                    <a href="facebook_logout.php" class="btn btn-danger">Logout</a>
                <?php else: ?>
                    <p class="lead">This page demonstrates how to implement Facebook OAuth login.</p>
                    <p>Clicking the button below would normally redirect you to Facebook's authentication page.</p>
                    <p>For demo purposes, we'll simulate the entire OAuth flow.</p>
                    
                    <div class="d-grid gap-2 col-md-8 mx-auto">
                        <a href="facebook_callback.php?simulate=true" class="btn btn-lg btn-primary">
                            <i class="bi bi-facebook me-2"></i> Log in with Facebook
                        </a>
                    </div>
                    
                    <hr>
                    <div class="alert alert-info">
                        <h5>How Facebook OAuth Works:</h5>
                        <ol>
                            <li>User clicks "Log in with Facebook" button</li>
                            <li>User is redirected to Facebook with your app's ID</li>
                            <li>User authenticates and grants permissions on Facebook</li>
                            <li>Facebook redirects back to your callback URL with an authorization code</li>
                            <li>Your server exchanges this code for an access token</li>
                            <li>Your server uses the access token to fetch user profile data</li>
                            <li>Your app creates/updates the user record and starts a session</li>
                        </ol>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h3 class="mb-0">Implementation Notes</h3>
            </div>
            <div class="card-body">
                <p>To implement Facebook login in a real application, you would need:</p>
                <ol>
                    <li>Register an application on <a href="https://developers.facebook.com" target="_blank">Facebook Developer Portal</a></li>
                    <li>Get your App ID and App Secret</li>
                    <li>Configure Valid OAuth Redirect URIs</li>
                    <li>Install the Facebook SDK: <code>composer require facebook/graph-sdk</code></li>
                </ol>
                
                <h5 class="mt-3">Example Integration Code:</h5>
                <?php
                $vulnerableCode = "// Never store these in your code - use environment variables instead
\$fb_app_id = 'YOUR_APP_ID';
\$fb_app_secret = 'YOUR_APP_SECRET';

// Initialize the Facebook SDK
\$fb = new Facebook\\Facebook([
    'app_id' => \$fb_app_id,
    'app_secret' => \$fb_app_secret,
    'default_graph_version' => 'v12.0',
]);

// Get login helper
\$helper = \$fb->getRedirectLoginHelper();

// Get the login URL
\$permissions = ['email'];
\$login_url = \$helper->getLoginUrl('https://yourdomain.com/fb-callback.php', \$permissions);

echo '<a href=\"' . \$login_url . '\">Log in with Facebook!</a>';";

                $safeCode = "// In callback.php:
try {
    // Get access token
    \$helper = \$fb->getRedirectLoginHelper();
    \$accessToken = \$helper->getAccessToken();
    
    // Get user data
    \$response = \$fb->get('/me?fields=id,name,email', \$accessToken);
    \$user = \$response->getGraphUser();
    
    // In a real app: Check if user exists in your database
    // If not, create a new user record
    \$user_id = \$user->getId();
    \$name = \$user->getName();
    \$email = \$user->getEmail();
    
    // Set session variables
    \$_SESSION['fb_user_id'] = \$user_id;
    \$_SESSION['user_name'] = \$name;
    \$_SESSION['user_email'] = \$email;
    
    // Redirect to dashboard
    header('Location: dashboard.php');
} catch(Facebook\\Exceptions\\FacebookResponseException \$e) {
    // Graph API returned an error
    echo 'Graph error: ' . \$e->getMessage();
} catch(Facebook\\Exceptions\\FacebookSDKException \$e) {
    // SDK returned an error
    echo 'SDK error: ' . \$e->getMessage();
}";

                $explanation = "
                <p><strong>Security considerations:</strong></p>
                <ul>
                    <li>Always validate the state parameter to prevent CSRF attacks</li>
                    <li>Never store App Secret in client-side code</li>
                    <li>Use HTTPS for all OAuth redirects</li>
                    <li>Validate user data received from Facebook before using it</li>
                    <li>Generate a secure session after successful authentication</li>
                    <li>Consider storing the Facebook user ID rather than access tokens</li>
                </ul>
                ";
                
                displayCodeComparison($vulnerableCode, $safeCode, $explanation);
                ?>
                
                <div class="alert alert-warning mt-3">
                    <strong>Note:</strong> This demo doesn't actually connect to Facebook or modify your database.
                    It's a simulation to show how the integration would work.
                </div>
            </div>
        </div>
    </div>
</div>

<?php
includeFooter();
?> 
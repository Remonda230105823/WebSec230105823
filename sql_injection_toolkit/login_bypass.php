<?php
require_once 'common.php';

// Get form inputs
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$loginAttempted = !empty($_POST);

// Process login attempt
$loginSuccessful = false;
if ($loginAttempted) {
    $loginSuccessful = simulateLogin($username, $password);
}

// Start HTML output
includeHeader('Authentication Bypass');
?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Login Form</div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($username) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
        
        <?php if ($loginAttempted): ?>
            <div class="card mt-3">
                <div class="card-header"><?= $loginSuccessful ? 'Login Successful' : 'Login Failed' ?></div>
                <div class="card-body">
                    <?php if ($loginSuccessful): ?>
                        <div class="alert alert-success">
                            <strong>Authentication successful!</strong> 
                            <?php if ($username === 'admin' && $password === 'secretpassword'): ?>
                                You have logged in using valid credentials.
                            <?php else: ?>
                                <strong>SQL Injection detected!</strong> You have bypassed authentication.
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <strong>Authentication failed!</strong> Invalid username or password.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="col-md-6">
        <?php
        // SQL injection examples for authentication bypass
        $examples = [
            'Basic Authentication Bypass' => "' OR '1'='1",
            'Using Comments to Ignore Password' => "admin' -- -",
            'Another Comment Variant' => "admin'#",
            'Using OR with Always True' => "admin' OR 1=1 -- -",
            'Using LIKE Operator' => "ad%' OR '1'='1",
        ];
        
        displayInjectionExamples($examples, 'username');
        ?>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">How Authentication Bypass Works</div>
    <div class="card-body">
        <p>A login form typically works by checking if a username and password match what's stored in the database:</p>
        
        <?php
        $vulnerableCode = "// Vulnerable code
\$query = \"SELECT * FROM users 
           WHERE username='\" . \$username . \"' 
           AND password='\" . \$password . \"'\";
\$result = mysqli_query(\$connection, \$query);

if (mysqli_num_rows(\$result) > 0) {
    // User authenticated!
    \$_SESSION['user'] = mysqli_fetch_assoc(\$result);
    redirect('dashboard.php');
}";

        $safeCode = "// Safe code using prepared statements
\$query = \"SELECT * FROM users 
           WHERE username = ? AND password = ?\";
\$stmt = \$connection->prepare(\$query);
\$stmt->bind_param(\"ss\", \$username, \$password);
\$stmt->execute();
\$result = \$stmt->get_result();

if (\$result->num_rows > 0) {
    // User authenticated!
    \$_SESSION['user'] = \$result->fetch_assoc();
    redirect('dashboard.php');
}";

        $explanation = "
        <p><strong>The vulnerability:</strong> In the vulnerable code, user input is directly concatenated into the SQL query. This allows attackers to manipulate the query's logic.</p>
        
        <p><strong>How attacks work:</strong></p>
        <ul>
            <li><code>' OR '1'='1</code> - Creates a condition that's always true, making the WHERE clause match any row</li>
            <li><code>admin' -- -</code> - Enters admin as username and comments out the password check entirely</li>
        </ul>
        
        <p><strong>The solution:</strong> Always use prepared statements with parameter binding, which separates SQL code from data. This prevents attackers from changing the query structure.</p>
        ";
        
        displayCodeComparison($vulnerableCode, $safeCode, $explanation);
        ?>
    </div>
</div>

<?php
includeFooter();
?> 
<?php
require_once 'common.php';

// Get form inputs
$userid = isset($_GET['userid']) ? $_GET['userid'] : '1';
$searched = isset($_GET['submit']);

// Flag for time-based delay detection
$timeDelayDetected = false;

// Start timer to detect time-based attacks
$startTime = microtime(true);

// Create the vulnerable SQL query
$vulnerableSql = "SELECT * FROM users WHERE id = $userid";

// Check for SQL injection patterns
$injectionDetected = false;

// Boolean-based results - default to showing data
$showUserData = true;

// Check for time-based delays
if (stripos($userid, 'sleep') !== false || 
    stripos($userid, 'benchmark') !== false || 
    stripos($userid, 'pg_sleep') !== false ||
    stripos($userid, 'waitfor') !== false) {
    
    // Simulate a delay for educational purposes
    sleep(2);
    $timeDelayDetected = true;
}

// Check for boolean-based blind injection
if (stripos($userid, 'and') !== false) {
    // If the condition appears to be false (contains "=0" or "!=1" etc.)
    if (strpos($userid, '=0') !== false || 
        strpos($userid, '!=1') !== false || 
        strpos($userid, '<>1') !== false ||
        strpos($userid, 'is null') !== false) {
        
        $showUserData = false;
    }
}

// Display simulated query
$injectionDetected = simulateQuery($vulnerableSql);

// Create simulated user data
$userData = [
    'id' => 1,
    'username' => 'john_doe',
    'email' => 'john@example.com',
    'created_at' => '2023-01-15 10:30:45',
    'admin' => false,
    'active' => true
];

// Calculate execution time for time-based detection
$executionTime = round((microtime(true) - $startTime), 2);

// Start HTML output
includeHeader('Blind SQL Injection');
?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">User Profile Lookup</div>
            <div class="card-body">
                <form method="get" action="">
                    <div class="mb-3">
                        <label for="userid" class="form-label">User ID:</label>
                        <input type="text" class="form-control" id="userid" name="userid" 
                               value="<?= htmlspecialchars($userid) ?>">
                        <div class="form-text">Enter a user ID to view profile information.</div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">View User</button>
                </form>
            </div>
        </div>
        
        <?php if ($searched): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <?= ($showUserData) ? 'User Profile' : 'User Not Found' ?>
                    <?php if ($timeDelayDetected): ?>
                        <span class="badge bg-warning text-dark float-end">Slow Response: <?= $executionTime ?>s</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if ($showUserData): ?>
                        <dl class="row">
                            <dt class="col-sm-3">User ID:</dt>
                            <dd class="col-sm-9"><?= htmlspecialchars($userData['id']) ?></dd>
                            
                            <dt class="col-sm-3">Username:</dt>
                            <dd class="col-sm-9"><?= htmlspecialchars($userData['username']) ?></dd>
                            
                            <dt class="col-sm-3">Email:</dt>
                            <dd class="col-sm-9"><?= htmlspecialchars($userData['email']) ?></dd>
                            
                            <dt class="col-sm-3">Created:</dt>
                            <dd class="col-sm-9"><?= htmlspecialchars($userData['created_at']) ?></dd>
                            
                            <dt class="col-sm-3">Admin:</dt>
                            <dd class="col-sm-9"><?= $userData['admin'] ? 'Yes' : 'No' ?></dd>
                            
                            <dt class="col-sm-3">Active:</dt>
                            <dd class="col-sm-9"><?= $userData['active'] ? 'Yes' : 'No' ?></dd>
                        </dl>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            No user found with the specified ID.
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($injectionDetected): ?>
                        <div class="alert alert-danger mt-3">
                            <strong>SQL Injection Detected!</strong> 
                            <p>The application is vulnerable to blind SQL injection.</p>
                            <?php if ($timeDelayDetected): ?>
                                <p>Time-based blind injection detected. The query execution was delayed.</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="col-md-6">
        <?php
        // SQL injection examples
        $examples = [
            'Boolean-Based: True Condition' => "1 AND 1=1",
            'Boolean-Based: False Condition' => "1 AND 1=0",
            'Boolean-Based: Admin Check' => "1 AND (SELECT admin FROM users WHERE id=1)=1",
            'Time-Based: Sleep Function' => "1 AND (SELECT SLEEP(2))",
            'Time-Based: Database Version' => "1 AND (SELECT SLEEP(2) FROM DUAL WHERE DATABASE() LIKE 'ex%')"
        ];
        
        displayInjectionExamples($examples, 'userid');
        ?>
        
        <div class="card mt-3">
            <div class="card-header">What is Blind SQL Injection?</div>
            <div class="card-body">
                <p>Blind SQL injection occurs when an application is vulnerable to SQL injection, but its error messages or results don't directly reveal information about the database. There are two primary types:</p>
                
                <h5>Boolean-Based Blind SQL Injection</h5>
                <p>This technique relies on sending a conditional statement to the database that returns either true or false, changing the application's response:</p>
                <ul>
                    <li>If condition is true: normal page loads</li>
                    <li>If condition is false: different content or behavior</li>
                </ul>
                
                <h5>Time-Based Blind SQL Injection</h5>
                <p>This technique introduces a time delay in the database response when a condition is true:</p>
                <ul>
                    <li>If condition is true: response is delayed</li>
                    <li>If condition is false: response comes back immediately</li>
                </ul>
                
                <p>Both techniques allow attackers to extract information one bit at a time by asking a series of true/false questions.</p>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">How Blind SQL Injection Works</div>
    <div class="card-body">
        <?php
        $vulnerableCode = "// Vulnerable code
\$query = \"SELECT * FROM users WHERE id = \" . \$userid;
\$result = mysqli_query(\$connection, \$query);

if (mysqli_num_rows(\$result) > 0) {
    // Display user profile
    \$user = mysqli_fetch_assoc(\$result);
    display_user_profile(\$user);
} else {
    // Display not found message
    echo \"User not found\";
}";

        $safeCode = "// Safe code using prepared statements
\$query = \"SELECT * FROM users WHERE id = ?\";
\$stmt = \$connection->prepare(\$query);
\$stmt->bind_param(\"i\", \$userid);
\$stmt->execute();
\$result = \$stmt->get_result();

if (\$result->num_rows > 0) {
    // Display user profile
    \$user = \$result->fetch_assoc();
    display_user_profile(\$user);
} else {
    // Display not found message
    echo \"User not found\";
}";

        $explanation = "
        <p><strong>How attackers leverage blind injection:</strong></p>
        
        <p>With <strong>Boolean-based</strong> blind SQL injection, attackers send conditional SQL statements like:</p>
        <pre>1 AND (SELECT SUBSTRING(username,1,1) FROM users WHERE id=1)='a'</pre>
        <p>By systematically testing each character position and possible value, attackers can extract data character-by-character.</p>
        
        <p>With <strong>Time-based</strong> blind SQL injection, attackers use database functions that cause delays:</p>
        <pre>1 AND (SELECT SLEEP(2) FROM DUAL WHERE (SELECT SUBSTRING(password,1,1) FROM users WHERE id=1)='5')</pre>
        <p>If the condition is true, the database sleeps for the specified time, allowing attackers to infer information from response timing.</p>
        
        <p>These attacks are often <strong>automated with scripts</strong> that can extract entire database contents over time.</p>
        ";
        
        displayCodeComparison($vulnerableCode, $safeCode, $explanation);
        ?>
    </div>
</div>

<?php
includeFooter();
?> 
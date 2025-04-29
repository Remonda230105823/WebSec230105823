<?php
require_once 'common.php';

// Start HTML output
includeHeader('SQL Injection Prevention');
?>

<div class="card mb-4">
    <div class="card-header">Preventing SQL Injection</div>
    <div class="card-body">
        <p>SQL injection is one of the most common web application vulnerabilities, but it's also one of the easiest to prevent. This page outlines various prevention techniques with code examples.</p>
        
        <div class="alert alert-info">
            <strong>Good News:</strong> Modern frameworks like Laravel already implement these protections by default when you use their query builders and ORM systems correctly.
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">1. Prepared Statements with Parameterized Queries</div>
    <div class="card-body">
        <?php
        $vulnerableCode = "// Vulnerable code with string concatenation
\$username = \$_POST['username'];
\$query = \"SELECT * FROM users WHERE username = '\" . \$username . \"'\";
\$result = mysqli_query(\$connection, \$query);";

        $safeCode = "// Safe code with prepared statements
\$username = \$_POST['username'];

// Using MySQLi
\$stmt = \$connection->prepare(\"SELECT * FROM users WHERE username = ?\");
\$stmt->bind_param(\"s\", \$username);
\$stmt->execute();
\$result = \$stmt->get_result();

// Using PDO
\$stmt = \$pdo->prepare(\"SELECT * FROM users WHERE username = ?\");
\$stmt->execute([\$username]);
\$result = \$stmt->fetch();";

        $explanation = "
        <p><strong>How it works:</strong> Prepared statements separate the SQL command from the data. The query structure is defined first with placeholders, and then values are bound to the placeholders later. This prevents the SQL interpreter from treating input data as executable code.</p>
        
        <p><strong>Benefits:</strong></p>
        <ul>
            <li>Complete protection against SQL injection (when used correctly)</li>
            <li>Can improve performance with repeated queries</li>
            <li>Handles data type conversion automatically</li>
        </ul>
        ";
        
        displayCodeComparison($vulnerableCode, $safeCode, $explanation);
        ?>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">2. ORM (Object-Relational Mapping)</div>
    <div class="card-body">
        <?php
        $vulnerableCode = "// Vulnerable direct query approach
\$keywords = \$_GET['keywords'];
\$query = \"SELECT * FROM products WHERE name LIKE '%\" . \$keywords . \"%'\";
\$result = mysqli_query(\$connection, \$query);";

        $safeCode = "// Safe approach using Laravel's Eloquent ORM
\$keywords = \$request->input('keywords');

// Query Builder approach
\$products = DB::table('products')
    ->where('name', 'like', '%' . \$keywords . '%')
    ->get();

// Eloquent ORM approach
\$products = Product::where('name', 'like', '%' . \$keywords . '%')
    ->get();";

        $explanation = "
        <p><strong>How it works:</strong> Object-Relational Mapping (ORM) libraries like Laravel's Eloquent provide an abstraction layer for database operations. They automatically handle query building with proper escaping and parameterization.</p>
        
        <p><strong>Benefits:</strong></p>
        <ul>
            <li>Automatic SQL injection protection</li>
            <li>Cleaner, more readable code</li>
            <li>Easier database operations with less boilerplate</li>
            <li>Database abstraction (can switch database systems more easily)</li>
        </ul>
        ";
        
        displayCodeComparison($vulnerableCode, $safeCode, $explanation);
        ?>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">3. Input Validation</div>
    <div class="card-body">
        <?php
        $vulnerableCode = "// Vulnerable code with no validation
\$user_id = \$_GET['id'];
\$query = \"SELECT * FROM users WHERE id = \" . \$user_id;
\$result = mysqli_query(\$connection, \$query);";

        $safeCode = "// Safe code with input validation
\$user_id = \$_GET['id'];

// Validate that it's a positive integer
if (!filter_var(\$user_id, FILTER_VALIDATE_INT) || \$user_id <= 0) {
    die('Invalid user ID');
}

// Even with validation, still use prepared statements
\$stmt = \$connection->prepare(\"SELECT * FROM users WHERE id = ?\");
\$stmt->bind_param(\"i\", \$user_id);
\$stmt->execute();
\$result = \$stmt->get_result();

// In Laravel, validation is even simpler:
\$validated = \$request->validate([
    'id' => 'required|integer|min:1',
]);";

        $explanation = "
        <p><strong>How it works:</strong> Input validation checks that data conforms to expected formats before using it in operations. This adds a layer of protection by rejecting potentially harmful inputs before they reach the database.</p>
        
        <p><strong>Benefits:</strong></p>
        <ul>
            <li>Rejects malformed inputs that don't match expected patterns</li>
            <li>Adds a defense-in-depth layer alongside parameterized queries</li>
            <li>Improves application robustness and data integrity</li>
        </ul>
        
        <p><strong>Note:</strong> Input validation alone is NOT sufficient protection against SQL injection. Always use it in conjunction with prepared statements or an ORM.</p>
        ";
        
        displayCodeComparison($vulnerableCode, $safeCode, $explanation);
        ?>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">4. Stored Procedures</div>
    <div class="card-body">
        <?php
        $vulnerableCode = "// Vulnerable direct query
\$username = \$_POST['username'];
\$password = \$_POST['password'];

\$query = \"SELECT * FROM users 
           WHERE username = '\" . \$username . \"' 
           AND password = '\" . \$password . \"'\";
\$result = mysqli_query(\$connection, \$query);";

        $safeCode = "// Safe approach using stored procedures
\$username = \$_POST['username'];
\$password = \$_POST['password'];

// Define the stored procedure in your database:
/*
CREATE PROCEDURE authenticate_user(IN p_username VARCHAR(50), IN p_password VARCHAR(255))
BEGIN
    SELECT * FROM users WHERE username = p_username AND password = p_password;
END
*/

// Call the stored procedure with prepared statement
\$stmt = \$connection->prepare(\"CALL authenticate_user(?, ?)\");
\$stmt->bind_param(\"ss\", \$username, \$password);
\$stmt->execute();
\$result = \$stmt->get_result();";

        $explanation = "
        <p><strong>How it works:</strong> Stored procedures move SQL logic to the database server. When properly implemented, they can accept parameters safely without risk of SQL injection.</p>
        
        <p><strong>Benefits:</strong></p>
        <ul>
            <li>Can provide SQL injection protection (if implemented correctly)</li>
            <li>Centralizes database logic</li>
            <li>Can improve performance</li>
            <li>Reduces network traffic</li>
        </ul>
        
        <p><strong>Note:</strong> For stored procedures to be safe, you must still use parameterized calls to invoke them. Dynamic SQL within stored procedures can still be vulnerable if not properly parameterized.</p>
        ";
        
        displayCodeComparison($vulnerableCode, $safeCode, $explanation);
        ?>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">5. Least Privilege Principle</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card bg-danger text-white h-100">
                    <div class="card-header">Insecure Approach</div>
                    <div class="card-body">
                        <p>Using a database connection with full administrative privileges for web application queries:</p>
                        <pre class="bg-light text-dark p-2">// Database config with excessive privileges
$config = [
    'host' => 'localhost',
    'username' => 'root',  // Admin user
    'password' => 'password',
    'database' => 'myapp'
];</pre>
                        <p>This database user has permissions to:</p>
                        <ul>
                            <li>CREATE, ALTER, and DROP any table</li>
                            <li>Full read/write access to all data</li>
                            <li>Ability to execute system commands (in some configurations)</li>
                            <li>Access to all databases on the server</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-success text-white h-100">
                    <div class="card-header">Secure Approach</div>
                    <div class="card-body">
                        <p>Creating a restricted database user with only the necessary permissions:</p>
                        <pre class="bg-light text-dark p-2">// Database config with limited privileges
$config = [
    'host' => 'localhost',
    'username' => 'myapp_user',  // Restricted user
    'password' => 'password',
    'database' => 'myapp'
];</pre>
                        <p>Creating a restricted user in MySQL:</p>
                        <pre class="bg-light text-dark p-2">CREATE USER 'myapp_user'@'localhost' IDENTIFIED BY 'password';
GRANT SELECT, INSERT, UPDATE, DELETE ON myapp.* TO 'myapp_user'@'localhost';
FLUSH PRIVILEGES;</pre>
                        <p>This database user can only:</p>
                        <ul>
                            <li>Read and modify data in specified tables</li>
                            <li>Cannot create or drop tables/databases</li>
                            <li>Cannot execute system commands</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <p><strong>How it works:</strong> The principle of least privilege means giving a user account only the permissions necessary to perform its function and nothing more. This limits the potential damage from successful SQL injection attacks.</p>
                
                <p><strong>Benefits:</strong></p>
                <ul>
                    <li>Restricts what an attacker can do even if SQL injection occurs</li>
                    <li>Prevents database structure manipulation (DROP TABLE, etc.)</li>
                    <li>Protects other databases on the same server</li>
                    <li>Can prevent certain types of data exfiltration</li>
                </ul>
                
                <p><strong>Implementation in Laravel:</strong> In your .env file, configure a database user with restricted permissions rather than using root or admin accounts.</p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">Additional Protection Measures</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header">Web Application Firewall (WAF)</div>
                    <div class="card-body">
                        <p>WAFs can detect and block common SQL injection patterns before they reach your application.</p>
                        <p><strong>Examples:</strong> ModSecurity, Cloudflare WAF, AWS WAF</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header">Escaping User Input</div>
                    <div class="card-body">
                        <p>Not as secure as prepared statements, but can be used as a fallback:</p>
                        <pre>$username = mysqli_real_escape_string($connection, $username);</pre>
                        <p><strong>Note:</strong> This should not be your primary defense.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header">Error Handling</div>
                    <div class="card-body">
                        <p>Proper error handling prevents leaking database information:</p>
                        <pre>// Don't expose SQL errors to users
try {
    // Database operations
} catch (Exception $e) {
    // Log the error for administrators
    error_log($e->getMessage());
    // Show generic error to users
    echo "An error occurred";
}</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
includeFooter();
?> 
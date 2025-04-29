<?php
require_once 'common.php';

// Get query parameters
$keywords = isset($_GET['keywords']) ? $_GET['keywords'] : '';
$searched = isset($_GET['keywords']);

// Outputs and preparations
$vulnerableSql = '';
$injectionDetected = false;
$results = [];

if ($searched) {
    // Create the vulnerable query
    $vulnerableSql = "SELECT id, name, price, description FROM products WHERE name LIKE '%$keywords%'";
    
    // Check for SQL injection
    $injectionDetected = simulateQuery($vulnerableSql);
    
    // Determine what results to show based on the injection
    if ($injectionDetected) {
        // If we detect UNION with users table
        if (stripos($keywords, 'UNION') !== false && stripos($keywords, 'users') !== false) {
            // First show normal products
            $results = getFakeProducts();
            
            // Then append user data (simulating a successful UNION attack)
            $users = getFakeUsers();
            foreach ($users as $user) {
                $results[] = [
                    'id' => 'EXPOSED: ' . $user['id'],
                    'name' => 'EXPOSED: ' . $user['username'],
                    'price' => 'EXPOSED: ' . $user['email'],
                    'description' => 'EXPOSED: ' . $user['password'] . ' (Role: ' . $user['role'] . ')'
                ];
            }
        }
        // If we detect UNION with table information
        elseif (stripos($keywords, 'UNION') !== false && (
                stripos($keywords, 'information_schema') !== false || 
                stripos($keywords, 'table_name') !== false)) {
            // First show normal products
            $results = getFakeProducts();
            
            // Then append table information (simulating table data extraction)
            $tables = getFakeTables();
            foreach ($tables as $table) {
                $results[] = [
                    'id' => 'EXPOSED: Table',
                    'name' => 'EXPOSED: ' . $table['table_name'],
                    'price' => 'EXPOSED: ' . $table['num_rows'] . ' rows',
                    'description' => 'EXPOSED: Created ' . $table['created_at']
                ];
            }
        }
        // If we detect UNION with database information
        elseif (stripos($keywords, 'UNION') !== false && (
                stripos($keywords, 'version') !== false || 
                stripos($keywords, 'database') !== false)) {
            // First show normal products
            $results = getFakeProducts();
            
            // Then append database information
            $dbInfo = getFakeDatabaseInfo();
            foreach ($dbInfo as $info) {
                $results[] = [
                    'id' => 'EXPOSED: Info',
                    'name' => 'EXPOSED: ' . $info['name'],
                    'price' => 'EXPOSED: ' . $info['value'],
                    'description' => 'EXPOSED: System information leaked'
                ];
            }
        }
        // Generic SQL injection (non-specific UNION attack)
        else {
            $results = getFakeProducts();
            
            // Add a generic "successful injection" row
            $results[] = [
                'id' => 'EXPOSED: SQLi',
                'name' => 'EXPOSED: SQL Injection Successful',
                'price' => 'EXPOSED: Data Access',
                'description' => 'The injection was detected but specific data was not targeted'
            ];
        }
    } else {
        // For normal searches, filter products based on keywords
        $allProducts = getFakeProducts();
        foreach ($allProducts as $product) {
            if (empty($keywords) || stripos($product['name'], $keywords) !== false) {
                $results[] = $product;
            }
        }
    }
}

// Start HTML output
includeHeader('UNION-Based Attacks');
?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Product Search</div>
            <div class="card-body">
                <form method="get" action="">
                    <div class="mb-3">
                        <label for="keywords" class="form-label">Search Products:</label>
                        <input type="text" class="form-control" id="keywords" name="keywords" 
                               value="<?= htmlspecialchars($keywords) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
        
        <?php if ($searched && !empty($results)): ?>
            <?php 
            $headers = ['ID', 'Product Name', 'Price', 'Description'];
            $injectionClass = $injectionDetected ? 'table-danger' : '';
            displayResultsTable($headers, $results, $injectionClass);
            ?>
        <?php elseif ($searched): ?>
            <div class="alert alert-info mt-3">No products found matching your search.</div>
        <?php endif; ?>
    </div>
    
    <div class="col-md-6">
        <?php
        // UNION attack examples
        $examples = [
            'Basic UNION Attack' => "' UNION SELECT 1,2,3,4 -- -",
            'Extract User Data' => "' UNION SELECT id, username, email, password FROM users -- -",
            'Database Information' => "' UNION SELECT 1, database(), version(), user() -- -",
            'Table Information' => "' UNION SELECT 1, table_name, 3, 4 FROM information_schema.tables WHERE table_schema = database() -- -",
            'Column Information' => "' UNION SELECT 1, column_name, data_type, 4 FROM information_schema.columns WHERE table_name = 'users' -- -"
        ];
        
        displayInjectionExamples($examples, 'keywords');
        ?>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">How UNION-Based Attacks Work</div>
    <div class="card-body">
        <p>UNION-based SQL injection is one of the most powerful techniques for extracting information from a database:</p>
        
        <?php
        $vulnerableCode = "// Vulnerable code
\$query = \"SELECT id, name, price, description 
           FROM products 
           WHERE name LIKE '%\" . \$keywords . \"%'\";
\$result = mysqli_query(\$connection, \$query);";

        $safeCode = "// Safe code using prepared statements
\$query = \"SELECT id, name, price, description 
           FROM products 
           WHERE name LIKE ?\";
\$stmt = \$connection->prepare(\$query);
\$param = \"%\" . \$keywords . \"%\";
\$stmt->bind_param(\"s\", \$param);
\$stmt->execute();
\$result = \$stmt->get_result();";

        $explanation = "
        <p><strong>What is a UNION attack?</strong> The UNION SQL operator combines results from two or more SELECT statements. Attackers can use it to append results from other tables to the original query.</p>
        
        <p><strong>Requirements for a UNION attack:</strong></p>
        <ul>
            <li>Both queries must return the same number of columns</li>
            <li>The data types in each column must be compatible</li>
            <li>The attacker must know (or guess) table and column names</li>
        </ul>
        
        <p><strong>Typical attack flow:</strong></p>
        <ol>
            <li>Determine number of columns (using <code>ORDER BY</code> or <code>UNION SELECT 1,2,3...</code>)</li>
            <li>Identify which columns are displayed in the results</li>
            <li>Extract data by placing it in the visible columns</li>
        </ol>
        
        <p><strong>The solution:</strong> Prepared statements prevent UNION attacks by maintaining the structure of the original query.</p>
        ";
        
        displayCodeComparison($vulnerableCode, $safeCode, $explanation);
        ?>
    </div>
</div>

<?php
includeFooter();
?> 
<?php
require_once 'common.php';

// Get query parameters
$product_id = isset($_GET['id']) ? $_GET['id'] : '1';
$searched = isset($_GET['id']);

// Create the vulnerable SQL query
$vulnerableSql = "SELECT * FROM products WHERE id = $product_id";

// Check for SQL injection
$injectionDetected = simulateQuery($vulnerableSql);

// Determine what to display based on the product ID and injection status
$product = null;
$showMultipleProducts = false;

if ($injectionDetected) {
    if (stripos($product_id, 'or') !== false || 
        stripos($product_id, '1=1') !== false ||
        stripos($product_id, '=') !== false) {
        // This simulates retrieving all products due to injection
        $showMultipleProducts = true;
        $products = getFakeProducts();
    } else {
        // For other injections, show a default product
        $product = getFakeProducts()[0];
    }
} else {
    // Normal case: try to find the requested product by ID
    $validId = is_numeric($product_id) && $product_id > 0 && $product_id <= 5;
    if ($validId) {
        $product = getFakeProducts()[$product_id - 1];
    }
}

// Start HTML output
includeHeader('Basic SQL Injection');
?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Product Details</div>
            <div class="card-body">
                <form method="get" action="">
                    <div class="mb-3">
                        <label for="id" class="form-label">Product ID:</label>
                        <input type="text" class="form-control" id="id" name="id" 
                               value="<?= htmlspecialchars($product_id) ?>">
                        <div class="form-text">Enter a product ID (1-5) to view details.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">View Product</button>
                </form>
            </div>
        </div>
        
        <?php if ($searched): ?>
            <?php if ($showMultipleProducts): ?>
                <div class="alert alert-danger mt-3">
                    <strong>SQL Injection Detected!</strong> Your injection forced the query to return all products.
                </div>
                <?php 
                $headers = ['ID', 'Product Name', 'Price', 'Description'];
                $rows = array_map(function($p) {
                    return [
                        $p['id'],
                        $p['name'],
                        $p['price'],
                        $p['description']
                    ];
                }, $products);
                displayResultsTable($headers, $rows, 'table-danger');
                ?>
            <?php elseif ($product): ?>
                <div class="card mt-3">
                    <div class="card-header">Product Information</div>
                    <div class="card-body">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="text-muted">Product ID: <?= htmlspecialchars($product['id']) ?></p>
                        <p class="fs-4">$<?= number_format($product['price'], 2) ?></p>
                        <p><?= htmlspecialchars($product['description']) ?></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning mt-3">
                    No product found with the specified ID.
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <div class="col-md-6">
        <?php
        // Basic SQL injection examples
        $examples = [
            'Return All Products' => "1 OR 1=1",
            'Always True Condition' => "1 OR 'x'='x'",
            'Numeric Manipulation' => "0 OR id > 0",
            'Comment Out Rest of Query' => "1; -- -",
            'Multiple Statements' => "1; DROP TABLE users; -- -"
        ];
        
        displayInjectionExamples($examples, 'id');
        ?>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">How Basic SQL Injection Works</div>
    <div class="card-body">
        <?php
        $vulnerableCode = "// Vulnerable code - directly concatenating input
\$product_id = \$_GET['id'];
\$query = \"SELECT * FROM products WHERE id = \" . \$product_id;
\$result = mysqli_query(\$connection, \$query);";

        $safeCode = "// Safe code using prepared statements
\$product_id = \$_GET['id'];
\$query = \"SELECT * FROM products WHERE id = ?\";
\$stmt = \$connection->prepare(\$query);
\$stmt->bind_param(\"i\", \$product_id);
\$stmt->execute();
\$result = \$stmt->get_result();";

        $explanation = "
        <p><strong>The basic vulnerability:</strong> SQL injection occurs when user input is concatenated directly into a SQL query string without proper sanitization or parameterization.</p>
        
        <p><strong>Key attack techniques:</strong></p>
        <ul>
            <li><strong>Changing query logic:</strong> Adding <code>OR 1=1</code> creates an always-true condition, bypassing filters</li>
            <li><strong>Adding comments:</strong> Using <code>-- -</code> or <code>#</code> to comment out the rest of the query</li>
            <li><strong>Stacked queries:</strong> Adding a semicolon <code>;</code> and then a second SQL statement (if multiple statements are allowed)</li>
        </ul>
        
        <p><strong>Examples of basic injections:</strong></p>
        <table class=\"table table-bordered\">
            <thead class=\"table-dark\">
                <tr>
                    <th>Original Query</th>
                    <th>User Input</th>
                    <th>Resulting Query</th>
                    <th>Effect</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>SELECT * FROM products WHERE id = [input]</code></td>
                    <td><code>1 OR 1=1</code></td>
                    <td><code>SELECT * FROM products WHERE id = 1 OR 1=1</code></td>
                    <td>Returns all products</td>
                </tr>
                <tr>
                    <td><code>SELECT * FROM users WHERE username='[input]' AND password='pass'</code></td>
                    <td><code>admin' -- -</code></td>
                    <td><code>SELECT * FROM users WHERE username='admin' -- -' AND password='pass'</code></td>
                    <td>Logs in as admin without password</td>
                </tr>
            </tbody>
        </table>
        
        <p><strong>The solution:</strong> Always use parameterized statements or prepared statements, which separate the query structure from user data.</p>
        ";
        
        displayCodeComparison($vulnerableCode, $safeCode, $explanation);
        ?>
    </div>
</div>

<?php
includeFooter();
?> 
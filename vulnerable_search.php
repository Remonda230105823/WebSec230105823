<?php
// IMPORTANT: This script is for educational purposes only
// It demonstrates a vulnerable SQL query that is susceptible to SQL injection
// DO NOT use this code in a production environment

// Note: This script creates a standalone demonstration without modifying your existing files
// It doesn't actually connect to your database, just simulates the vulnerability

// Simulate a database connection
function simulateDatabaseQuery($sql) {
    // This function simulates executing a query and returns dummy results
    // In a real scenario, this would actually execute against your database
    
    echo "<div class='alert alert-danger'>
        <strong>Vulnerable Query:</strong> 
        <pre>" . htmlspecialchars($sql) . "</pre>
    </div>";
    
    // Check if the query contains SQL injection attempts
    if (stripos($sql, "union") !== false || 
        stripos($sql, "or 1=1") !== false || 
        stripos($sql, "or \"1\"=\"1") !== false || 
        stripos($sql, "or '1'='1") !== false) {
        
        // Return simulated data breach results
        return [
            ['id' => 1, 'name' => 'Laptop XPS15', 'price' => 1299.99, 'description' => 'High-end laptop with 15-inch display'],
            ['id' => 'EXPOSED', 'name' => 'admin@example.com', 'price' => 'EXPOSED: $2y$10$X5hG7...', 'description' => 'SQL Injection Successful! User data leaked'],
            ['id' => 'EXPOSED', 'name' => 'user1@example.com', 'price' => 'EXPOSED: $2y$10$J3hF9...', 'description' => 'SQL Injection Successful! User data leaked'],
        ];
    } else {
        // Return normal product results
        return [
            ['id' => 1, 'name' => 'Laptop XPS15', 'price' => 1299.99, 'description' => 'High-end laptop with 15-inch display'],
            ['id' => 2, 'name' => 'Smartphone Galaxy S21', 'price' => 799.99, 'description' => 'Latest smartphone with 5G capability'],
            ['id' => 3, 'name' => 'Wireless Headphones', 'price' => 149.99, 'description' => 'Noise-cancelling wireless headphones'],
        ];
    }
}

// Get the search keyword (unfiltered - this is the vulnerability!)
$keywords = isset($_GET['keywords']) ? $_GET['keywords'] : '';

// Build a vulnerable SQL query (DO NOT DO THIS IN REAL CODE!)
$vulnerable_sql = "SELECT * FROM products WHERE name LIKE '%" . $keywords . "%'";

// The safe way would be:
// $safe_sql = "SELECT * FROM products WHERE name LIKE ?";
// $params = ['%' . $keywords . '%']; 
// And then use prepared statements with the parameters

// Execute our simulated query
$results = simulateDatabaseQuery($vulnerable_sql);

// Display the page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulnerable Search Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; }
        .container { max-width: 900px; }
        pre { background-color: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; }
        .alert-danger pre { background-color: rgba(255,255,255,0.5); }
    </style>
</head>
<body>
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h1>SQL Injection Demonstration</h1>
                <div class="alert alert-warning">
                    <strong>Warning:</strong> This is an educational demonstration only. This page intentionally contains a SQL injection vulnerability.
                </div>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3>Product Search</h3>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="">
                            <div class="mb-3">
                                <label for="keywords" class="form-label">Search Products:</label>
                                <input type="text" class="form-control" id="keywords" name="keywords" 
                                       value="<?= htmlspecialchars($keywords) ?>" 
                                       placeholder="Enter product name or try SQL injection">
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3>Try These SQL Injection Examples</h3>
                    </div>
                    <div class="card-body">
                        <p>Copy and paste these examples into the search box above:</p>
                        
                        <div class="mb-3">
                            <h5>Basic Injection:</h5>
                            <pre>' OR '1'='1</pre>
                            <button class="btn btn-sm btn-secondary try-injection" 
                                    data-value="' OR '1'='1">Try This</button>
                        </div>
                        
                        <div class="mb-3">
                            <h5>UNION Attack:</h5>
                            <pre>' UNION SELECT id, email as name, password as price, name as description FROM users WHERE '1'='1</pre>
                            <button class="btn btn-sm btn-secondary try-injection" 
                                    data-value="' UNION SELECT id, email as name, password as price, name as description FROM users WHERE '1'='1">Try This</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3>Search Results</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($results as $row): ?>
                                    <tr <?= (strpos($row['name'], 'EXPOSED') !== false) ? 'class="table-danger"' : '' ?>>
                                        <td><?= htmlspecialchars($row['id']) ?></td>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['price']) ?></td>
                                        <td><?= htmlspecialchars($row['description']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3>How to Fix SQL Injection</h3>
                    </div>
                    <div class="card-body">
                        <p>To prevent SQL injection, always use:</p>
                        <ol>
                            <li><strong>Prepared Statements</strong> with parameterized queries</li>
                            <li><strong>Input Validation</strong> to verify data is in the expected format</li>
                            <li><strong>ORM Libraries</strong> like Laravel's Eloquent which handle escaping</li>
                            <li><strong>Least Privilege Principle</strong> for database users</li>
                        </ol>
                        
                        <h5>Safe Code Example:</h5>
<pre>// Laravel Eloquent (Safe)
$products = Product::where('name', 'like', '%' . $request->keywords . '%')->get();

// Using Query Builder with prepared statements (Safe)
$products = DB::table('products')
    ->where('name', 'like', '%' . $request->keywords . '%')
    ->get();</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Script to prefill the form with example injections
        document.querySelectorAll('.try-injection').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('keywords').value = this.getAttribute('data-value');
                // Don't auto-submit to prevent accidental injection
            });
        });
    </script>
</body>
</html> 
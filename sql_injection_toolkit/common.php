<?php
/**
 * Common functions for SQL injection demonstrations
 * 
 * IMPORTANT: All functions in this file are for educational purposes only
 * These simulations don't connect to your actual database
 */

// Include this at the top of each demonstration file
function includeHeader($title) {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . htmlspecialchars($title) . ' - SQL Injection Demo</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { padding: 20px; }
            .container { max-width: 900px; }
            pre { background-color: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; }
            .alert-danger pre { background-color: rgba(255,255,255,0.5); }
            .navigation { margin-bottom: 20px; }
            .card { margin-bottom: 20px; }
            .results-table { margin-top: 20px; }
            .code-block { font-family: monospace; background-color: #f5f5f5; padding: 15px; border-radius: 5px; margin-bottom: 15px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="navigation">
                <a href="index.html" class="btn btn-outline-secondary">&larr; Back to Index</a>
            </div>
            <h1>' . htmlspecialchars($title) . '</h1>
            <div class="alert alert-danger mb-4">
                <strong>Educational Purposes Only!</strong> This demonstration shows SQL injection vulnerabilities for learning purposes. Do not use these techniques on systems without permission.
            </div>';
}

// Include this at the bottom of each demonstration file
function includeFooter() {
    echo '
            <div class="card mt-4">
                <div class="card-header">Important Note</div>
                <div class="card-body">
                    <p>This demonstration simulates SQL injection without affecting your real database. No actual database is being accessed.</p>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Script to prefill form fields with example payloads
            document.querySelectorAll(".try-injection").forEach(button => {
                button.addEventListener("click", function() {
                    const targetInput = document.getElementById(this.getAttribute("data-target"));
                    if (targetInput) {
                        targetInput.value = this.getAttribute("data-value");
                    }
                });
            });
        </script>
    </body>
    </html>';
}

// Display vulnerable query
function displayVulnerableQuery($query) {
    echo '<div class="alert alert-danger">
        <strong>Vulnerable Query:</strong> 
        <pre>' . htmlspecialchars($query) . '</pre>
    </div>';
}

// Display code comparison (vulnerable vs safe)
function displayCodeComparison($vulnerableCode, $safeCode, $explanation = '') {
    echo '<div class="row">
        <div class="col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-header">Vulnerable Code</div>
                <div class="card-body">
                    <pre class="bg-light text-dark p-2 mb-0">' . htmlspecialchars($vulnerableCode) . '</pre>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-header">Safe Code</div>
                <div class="card-body">
                    <pre class="bg-light text-dark p-2 mb-0">' . htmlspecialchars($safeCode) . '</pre>
                </div>
            </div>
        </div>
    </div>';
    
    if (!empty($explanation)) {
        echo '<div class="card mb-4">
            <div class="card-body">
                <p class="card-text">' . $explanation . '</p>
            </div>
        </div>';
    }
}

// Simulate database query and return fake results
function simulateQuery($sql, $injectionDetected = false) {
    // Check common SQL injection patterns
    $injectionPatterns = [
        'union', 'select', '1=1', 'or 1', 'or true', '--', '#',
        'sleep', 'benchmark', 'waitfor', 'delay', 'version()', 'database()',
        'information_schema', 'table_name', 'column_name'
    ];
    
    if (!$injectionDetected) {
        foreach ($injectionPatterns as $pattern) {
            if (stripos($sql, $pattern) !== false) {
                $injectionDetected = true;
                break;
            }
        }
    }
    
    // Display the query
    displayVulnerableQuery($sql);
    
    return $injectionDetected;
}

// Simulate login attempt
function simulateLogin($username, $password) {
    // Create the vulnerable SQL query that would be used
    $vulnerableSql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    
    // Check for SQL injection in username or password
    $injectionDetected = simulateQuery($vulnerableSql);
    
    // Admin credentials for "legitimate" login
    $validAdmin = ($username === 'admin' && $password === 'secretpassword');
    
    // Successful login if valid credentials OR if injection detected
    return $validAdmin || $injectionDetected;
}

// Display results table
function displayResultsTable($headers, $rows, $injectionClass = '') {
    echo '<div class="results-table">
        <h3>Query Results</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>';
                    
    foreach ($headers as $header) {
        echo '<th>' . htmlspecialchars($header) . '</th>';
    }
    
    echo '</tr>
        </thead>
        <tbody>';
        
    foreach ($rows as $row) {
        echo '<tr class="' . $injectionClass . '">';
        foreach ($row as $cell) {
            echo '<td>' . htmlspecialchars($cell) . '</td>';
        }
        echo '</tr>';
    }
    
    echo '</tbody>
        </table>
    </div>';
}

// Function to display SQL injection examples
function displayInjectionExamples($examples, $targetInputId) {
    echo '<div class="card mb-4">
        <div class="card-header">SQL Injection Examples</div>
        <div class="card-body">
            <p>Try these examples:</p>
            <div class="list-group">';
            
    foreach ($examples as $title => $payload) {
        echo '<div class="list-group-item">
            <h5>' . htmlspecialchars($title) . '</h5>
            <pre>' . htmlspecialchars($payload) . '</pre>
            <button class="btn btn-sm btn-secondary try-injection" 
                    data-target="' . $targetInputId . '" 
                    data-value="' . htmlspecialchars($payload) . '">
                Try This
            </button>
        </div>';
    }
    
    echo '</div>
        </div>
    </div>';
}

// Function to simulate products in database
function getFakeProducts() {
    return [
        ['id' => 1, 'name' => 'Laptop XPS15', 'price' => 1299.99, 'description' => 'High-end laptop with 15-inch display'],
        ['id' => 2, 'name' => 'Smartphone Galaxy S21', 'price' => 799.99, 'description' => 'Latest smartphone with 5G capability'],
        ['id' => 3, 'name' => 'Wireless Headphones', 'price' => 149.99, 'description' => 'Noise-cancelling wireless headphones'],
        ['id' => 4, 'name' => 'Smart Watch Series 7', 'price' => 399.99, 'description' => 'Fitness tracking and notifications'],
        ['id' => 5, 'name' => 'Tablet Pro 12.9', 'price' => 999.99, 'description' => 'Professional tablet with stylus support']
    ];
}

// Function to simulate users in database
function getFakeUsers() {
    return [
        ['id' => 1, 'username' => 'admin', 'email' => 'admin@example.com', 'password' => '$2y$10$X5hG7...', 'role' => 'administrator'],
        ['id' => 2, 'username' => 'john', 'email' => 'john@example.com', 'password' => '$2y$10$J3hF9...', 'role' => 'customer'],
        ['id' => 3, 'username' => 'sarah', 'email' => 'sarah@example.com', 'password' => '$2y$10$L7gH2...', 'role' => 'customer'],
        ['id' => 4, 'username' => 'manager', 'email' => 'manager@example.com', 'password' => '$2y$10$P2jK5...', 'role' => 'manager']
    ];
}

// Function to simulate database tables
function getFakeTables() {
    return [
        ['table_name' => 'users', 'num_rows' => 4, 'created_at' => '2023-01-15'],
        ['table_name' => 'products', 'num_rows' => 5, 'created_at' => '2023-01-15'],
        ['table_name' => 'orders', 'num_rows' => 12, 'created_at' => '2023-01-15'],
        ['table_name' => 'order_items', 'num_rows' => 25, 'created_at' => '2023-01-15'],
        ['table_name' => 'categories', 'num_rows' => 8, 'created_at' => '2023-01-15']
    ];
}

// Function to get database information
function getFakeDatabaseInfo() {
    return [
        ['name' => 'Database Name', 'value' => 'example_db'],
        ['name' => 'Database Version', 'value' => 'MySQL 8.0.26'],
        ['name' => 'Database User', 'value' => 'db_user@localhost'],
        ['name' => 'OS Version', 'value' => 'Linux 5.4.0-42-generic']
    ];
} 
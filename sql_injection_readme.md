# SQL Injection Demonstration

This demonstration shows SQL injection vulnerabilities for **educational purposes only**. These files do not modify your existing application or database in any way.

## Files Included

1. **sql_injection_demo.html** - A static HTML page that explains SQL injection concepts
2. **vulnerable_search.php** - A PHP script that simulates a vulnerable search function

## How to Use

1. Place these files in your web server's document root (e.g., in your Laravel public directory)
2. Access them through your browser:
   - http://localhost/sql_injection_demo.html
   - http://localhost/vulnerable_search.php

## Important Notes

- These demonstrations are completely isolated from your actual database
- No changes are made to your existing code or database
- The demonstrations simulate what SQL injection might look like if your code was vulnerable
- Your Laravel application likely already uses parameterized queries, which protect against SQL injection

## Security Recommendations

1. **Use Parameterized Queries**: Never concatenate user inputs directly into SQL queries
   ```php
   // BAD (vulnerable):
   $query = "SELECT * FROM users WHERE username = '" . $username . "'";
   
   // GOOD (safe):
   $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
   $stmt->execute([$username]);
   ```

2. **Use Laravel's Query Builder or Eloquent**: These automatically handle parameter binding
   ```php
   // Using Eloquent (safe):
   $user = User::where('username', $request->username)->first();
   
   // Using Query Builder (safe):
   $user = DB::table('users')->where('username', $request->username)->first();
   ```

3. **Input Validation**: Always validate and sanitize user inputs
   ```php
   $this->validate($request, [
       'username' => 'required|alpha_num|max:255',
   ]);
   ```

4. **Apply Least Privilege Principle**: Database users should only have the permissions they need

## Disclaimer

These demonstrations are for educational purposes only. SQL injection is a serious security vulnerability, and attempting SQL injection attacks on systems without permission is illegal and unethical. 
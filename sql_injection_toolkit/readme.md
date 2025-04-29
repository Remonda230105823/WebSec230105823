# SQL Injection Toolkit

This toolkit is designed for educational purposes to help understand SQL injection vulnerabilities, along with demonstrations of secure authentication methods like OAuth.

## Features

### SQL Injection Demonstrations
- Basic SQL Injection examples
- Authentication bypass vulnerabilities
- UNION-based attacks
- Blind SQL injection techniques
- Prevention methods and best practices

### Facebook OAuth Integration
The toolkit now includes a Facebook login integration example that demonstrates:
- Secure OAuth 2.0 authentication flow
- Social login implementation
- Session management
- User data handling

## Facebook Login Files

The Facebook login integration consists of these files:

- `facebook_login.php` - Initiates the Facebook OAuth flow
- `facebook_callback.php` - Handles the callback from Facebook after authentication
- `facebook_logout.php` - Manages user logout
- `login_with_facebook.php` - Demo page with both traditional and Facebook login options

## Usage

Access the toolkit through your web server:
1. Place all files in your web root directory
2. Navigate to `http://localhost/sql_injection_toolkit/`
3. Explore the various demonstrations
4. Try the Facebook login integration to understand OAuth implementation

## Security Notice

This toolkit is for educational purposes only. The SQL injection examples simulate vulnerabilities in a controlled environment without affecting real databases.

The Facebook OAuth implementation uses simulated credentials and does not connect to actual Facebook services. In a production environment, you would need to:
1. Register a real Facebook App
2. Use proper App ID and App Secret 
3. Configure valid OAuth redirect URIs
4. Implement proper error handling and security measures

## Requirements

- PHP 7.0+
- Web server (Apache/Nginx)
- Modern web browser 
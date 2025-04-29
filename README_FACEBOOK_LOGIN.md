# Facebook Login Integration Guide

This guide explains how to integrate and use the Facebook login functionality in your application.

## Overview

The Facebook login integration allows users to authenticate using their Facebook accounts via OAuth 2.0. This implementation is completely separate from the SQL injection demonstration features and provides a secure authentication method.

## Files Included

1. `public/facebook_login.php` - Main entry point for Facebook login
2. `public/facebook_auth.php` - Initiates the OAuth flow
3. `public/facebook_callback.php` - Processes Facebook's response
4. `public/facebook-login-link.php` - Reusable component for adding Facebook login to any page
5. `public/facebook-login-demo.php` - Example usage of the login link
6. `database/migrations/2023_12_10_000000_add_facebook_fields_to_users_table.php` - Migration to add Facebook fields to users table

## Setup Instructions

### 1. Database Setup

Run the migration to add the necessary fields to your users table:

```bash
php artisan migrate
```

This will add the following fields to your users table:
- `facebook_id` - Unique identifier for Facebook users
- `facebook_token` - For Facebook API access
- `login_method` - To track how the user authenticated
- `profile_picture` - URL to the user's Facebook profile picture

### 2. Create a Facebook App

1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Create a new app (choose "Consumer" type)
3. Add the "Facebook Login" product to your app
4. Configure the Facebook Login settings:
   - Valid OAuth Redirect URIs: `http://your-domain.com/facebook_callback.php`
   - Permissions: email, public_profile

### 3. Update Configuration

Edit `public/facebook_auth.php` and `public/facebook_callback.php` to update:

```php
$fb_app_id = 'YOUR_FACEBOOK_APP_ID';
$fb_app_secret = 'YOUR_FACEBOOK_APP_SECRET';
$fb_redirect_uri = 'http://your-domain.com/facebook_callback.php';
```

Also, update the database connection details in `public/facebook_callback.php` if needed:

```php
$db_host = "localhost";
$db_user = "YOUR_DB_USERNAME";
$db_pass = "YOUR_DB_PASSWORD";
$db_name = "YOUR_DB_NAME";
```

## Usage

### Adding the Login Button to Your Pages

You can include the Facebook login button in any page by adding:

```php
<?php include("facebook-login-link.php"); ?>
```

### Standalone Login Page

Direct users to `/facebook_login.php` for the full Facebook login experience.

### How It Works

1. User clicks the "Login with Facebook" button
2. They are redirected to Facebook for authentication
3. After authenticating, Facebook redirects back to your callback URL
4. The callback script:
   - Exchanges the authorization code for an access token
   - Retrieves the user's profile information
   - Creates a new user account or updates an existing one
   - Establishes a session for the authenticated user

## Security Features

- Uses state parameter to prevent CSRF attacks
- Validates all inputs and parameterizes database queries
- Error handling with appropriate logging
- Uses transactions for database operations
- Securely stores access tokens

## Troubleshooting

Check the following log files for debugging:
- `facebook_attempts.log` - Records login attempts
- `facebook_logins.log` - Records successful logins
- PHP error log - For any exceptions or error messages

## Separation from SQL Injection Demo

This Facebook login implementation uses secure coding practices and is intentionally separate from the SQL injection demonstration features. It serves as an example of proper security implementation as opposed to the vulnerable code in the SQL injection demos. 
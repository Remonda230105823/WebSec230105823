<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login with Facebook</title>
    <style>
        .fb-btn {
            background: #4267B2;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .fb-btn:hover {
            background: #365899;
        }
        .fb-icon {
            width: 20px;
            height: 20px;
        }
    </style>
</head>
<body>
    <div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
        <a href="{{ url('auth/facebook') }}" class="fb-btn">
            <svg class="fb-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><circle cx="16" cy="16" r="16" fill="#4267B2"/><path d="M22.675 16.001h-4.012v11.999h-4.998V16.001h-2.5v-4.001h2.5v-2.5c0-2.067 1.233-5.001 5.001-5.001l3.674.015v4.001h-2.667c-.4 0-.999.2-.999 1.001v2.484h3.666l-.334 4.001z" fill="#fff"/></svg>
            Login with Facebook
        </a>
    </div>
</body>
</html> 
<!DOCTYPE html>
<html>
<head>
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .email-content {
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }
        .email-footer {
            text-align: center;
            padding: 10px;
            background-color: #f1f1f1;
            font-size: 14px;
            color: #777777;
        }
        .reset-button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 10px;
        }
        .reset-button:hover {
            background-color: #45a049;
        }
        .small-text {
            font-size: 12px;
            color: #888888;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Password Reset Request</h1>
        </div>
        <div class="email-content">
            <p>Dear {{$name}},</p>
            <p>We received a request to reset your password for your account. You can reset your password by clicking the button below:</p>
            <p style="text-align: center;">
                <a href="{{ route('reset-password', $token) }}" class="reset-button" style="color:white;">Reset Password</a>
            </p>
            <p>If you did not request a password reset, please ignore this email or contact support if you have concerns.</p>
            <p class="small-text">This link will expire in 60 minutes.</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Your Application Name. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Reset</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background-color: #ffffff; padding: 30px; border: 1px solid #dee2e6; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #6c757d; border-radius: 0 0 5px 5px; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Hotel Management System</h2>
        </div>
        <div class="content">
            <h3>Password Reset Request</h3>
            <p>Hello,</p>
            <p>You are receiving this email because we received a password reset request for your account.</p>
            <p>Click the button below to reset your password:</p>
            <div style="text-align: center;">
                <a href="{{ url('/reset-password/'.$token.'?email='.$email) }}" class="btn">Reset Password</a>
            </div>
            <p>If you did not request a password reset, no further action is required.</p>
            <p>This password reset link will expire in 60 minutes.</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Hotel Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

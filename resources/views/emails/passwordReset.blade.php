<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body>
    <h2>Password Reset Request</h2>
    <p>Hello {{ $user->user_fname }},</p>
    <p>We received a request to reset your password. If you did not request a password reset, you can ignore this email.</p>
    <p>To reset your password, please click the link below:</p>
    
    <!-- The link includes the token -->
    <a href="{{ url('password/reset/' . $token) }}" style="background-color: #4CAF50; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">Reset Your Password</a>

    <p>If you have any issues or did not request a password reset, please contact our support team.</p>

    <p>Thank you,</p>
    <p>WOTG DM</p>
</body>
</html>

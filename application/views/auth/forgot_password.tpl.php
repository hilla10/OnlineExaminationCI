<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Reset Request</title>
    <style>
        /* Add some basic styling for the email */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h1 {
            color: #0056b3;
        }
        a {
            color: #0056b3;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Password Reset Request</h1>
        <p>Hello <?php echo htmlspecialchars($identity); ?>,</p>
        <p>You have requested to reset your password. Click the link below to reset it:</p>
        <p><a href="<?php echo htmlspecialchars($forgotten_password_code); ?>">Reset Password</a></p>
        <p>If you did not request this, please ignore this email.</p>
        <p>Best regards,<br>Your Company</p>
    </div>
</body>
</html>

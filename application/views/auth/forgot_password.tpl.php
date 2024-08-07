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
        <p>Dear <?= $identity ?>,</p>
        <p>We received a request to reset your password for your account. If you did not make this request, please ignore this email.</p>
        <p>To reset your password, click the link below or copy and paste it into your browser:</p>
        <p><a href="<?= base_url('auth/reset_password/' . $forgotten_password_code) ?>"><?= base_url('auth/reset_password/' . $forgotten_password_code) ?></a></p>
        <p>If you have any questions, feel free to contact our support team.</p>
        <p>Best regards,</p>
        <p>Entoto Polytechnic College</p>
    </div>
</body>
</html>

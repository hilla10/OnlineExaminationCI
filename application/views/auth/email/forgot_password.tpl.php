<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Request</title>
 
     <style>

        .container {
                max-width: 570px;
                margin-left: auto;
                margin-right: auto;
                font-family: Helvetica,Arial,sans-serif;
                height: 100%;
                line-height: 1.5;
                font-size: 16px;
        }
        .btn {
                padding: 10px 20px;
                color: #fff !important;
                width: 150px;
                margin: auto;
                display: inline-block;
                border-radius: 50px;
                font-size: 1.2rem;
                font-weight: 700;
                background:#282a35;
                text-decoration: none; 
                transition: background-color 0.5s ease, color 0.5s ease;
        }

        a {
                transition: background-color 0.5s ease, color 0.5s ease;
        }

        a:hover {
                background: rgba(255, 255, 255, 0.1);
                color: #000 !important;
        }


        .link {
                color: rgb(0, 84, 173);
                font-size: 1.1rem;
                text-decoration: none;
                transition: color .3s ease;
        }

        .link:hover {
               color: #72afd2 !important;
        }

     </style>
</head>
<body>
    <div class="container mt-4">
        <div>
            <div >
                <h2 >Dear <?= htmlspecialchars($identity) ?>,</h2>
                <h1 >Forgot your password?</h1>
                <p >Click the link below to reset your password.</p>
                <!-- Button to reset password -->
                <a href="<?= base_url('auth/reset_password/' . $forgotten_password_code) ?>" class="btn">
                    Reset Password
                </a>
                
                <p>If you have any questions, feel free to contact our support team:</p>
                <p>
                    <a href="mailto:entotopolytechniccollege72@gmail.com" class="link">Entoto Polytechnic College</a>
                </p>
                <p>If you didn't request a password reset, you can delete this email.</p>
                
                <p>Entoto Polytechnic College</p>
            </div>
        </div>
    </div>

   
</body>
</html>

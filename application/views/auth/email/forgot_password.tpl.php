<html>
<body>
	 <p>Dear <?= $identity ?>,</p>
        <p>We received a request to reset your password for your account. If you did not make this request, please ignore this email.</p>
        <p>To reset your password, click the link below or copy and paste it into your browser:</p>
        <p><?php echo sprintf(lang('email_forgot_password_subheading'), anchor("auth/reset_password/$forgotten_password_code", lang('email_forgot_password_link')));?></p>
        <p>If you have any questions, feel free to contact our support team.</p>
        <p>Best regards,</p>
        <p>Entoto Polytechnic College</p>
</body>
</html>
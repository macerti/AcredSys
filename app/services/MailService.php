<?php

class MailService
{
    private static $fromEmail = 'noreply@macerti.com';
    private static $fromName = 'ManagSys - Accreditation';

    /**
     * Send password reset email
     */
    public static function sendPasswordResetEmail(string $to, string $token, string $resetLink): bool
    {
        $subject = 'Password Reset Request - ManagSys';
        $htmlBody = self::getPasswordResetTemplate($to, $resetLink, $token);
        return self::send($to, $subject, $htmlBody);
    }

    /**
     * Core sending function using PHP mail()
     */
    private static function send(string $to, string $subject, string $htmlBody): bool
    {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . self::$fromName . " <" . self::$fromEmail . ">\r\n";
        $headers .= "Reply-To: " . self::$fromEmail . "\r\n";
        $headers .= "X-Mailer: ManagSys\r\n";

        $result = mail($to, $subject, $htmlBody, $headers);
        self::logEmail($to, $subject, $result);
        return $result;
    }

    /**
     * HTML Email Template
     */
    private static function getPasswordResetTemplate(string $email, string $resetLink, string $token): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; border: 1px solid #ddd; border-radius: 8px; }
        .header { background-color: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .button { display: inline-block; background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        .token { background-color: #f0f0f0; padding: 10px; border-radius: 5px; font-family: monospace; word-break: break-all; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header"><h1>Password Reset</h1></div>
        <div class="content">
            <p>Hello,</p>
            <p>You requested a password reset for <strong>$email</strong></p>
            <p><a href="$resetLink" class="button">Reset Password</a></p>
            <p>Or use this token:</p>
            <div class="token">$token</div>
            <p style="color:#999;font-size:12px">Link expires in 1 hour</p>
        </div>
        <div class="footer">
  <p>&copy; <?php echo date("Y"); ?> Macerti</p>
</div>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Log email attempts
     */
    private static function logEmail(string $to, string $subject, bool $success): void
    {
        $logDir = __DIR__ . '/../../logs';
        @mkdir($logDir, 0755, true);
        $logFile = $logDir . '/mail.log';
        $timestamp = date('Y-m-d H:i:s');
        $status = $success ? 'SUCCESS' : 'FAILED';
        file_put_contents($logFile, "[$timestamp] $status - To: $to - Subject: $subject\n", FILE_APPEND);
    }
}

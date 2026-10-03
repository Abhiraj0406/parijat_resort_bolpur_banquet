<?php
// includes/email.php – Simple SMTP email wrapper using PHPMailer

// Require manual PHPMailer files since Composer is not used
require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_smtp_email(string $to, string $subject, string $htmlBody): bool {
    // Load SMTP settings from config constants
    $smtpHost = defined('SMTP_HOST') ? SMTP_HOST : 'smtp.example.com';
    $smtpUser = defined('SMTP_USER') ? SMTP_USER : 'user@example.com';
    $smtpPass = defined('SMTP_PASS') ? SMTP_PASS : 'secret';
    $smtpPort = defined('SMTP_PORT') ? SMTP_PORT : 587; // TLS
    $smtpSecure = defined('SMTP_SECURE') ? SMTP_SECURE : 'tls'; // or 'ssl'
    $fromEmail = defined('FROM_EMAIL') ? FROM_EMAIL : 'no-reply@example.com';
    $fromName  = defined('FROM_NAME')  ? FROM_NAME  : 'Parijat Resort';

    // Avoid blocking network delays when SMTP is configured with placeholder values
    if (empty($smtpPass) || $smtpPass === 'your_email_app_password_here' || $smtpHost === 'smtp.example.com') {
        return false;
    }

    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->Timeout    = 3;
        $mail->isSMTP();
        $mail->Host       = $smtpHost;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUser;
        $mail->Password   = $smtpPass;
        $mail->SMTPSecure = $smtpSecure;
        $mail->Port       = $smtpPort;
        $mail->CharSet    = 'UTF-8';

        // Recipients
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;
        $mail->AltBody = strip_tags($htmlBody);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('SMTP Email could not be sent. PHPMailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}
?>

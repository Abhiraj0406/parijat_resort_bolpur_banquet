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
    $smtpHost = defined('SMTP_HOST') ? SMTP_HOST : 'smtp.gmail.com';
    $smtpUser = defined('SMTP_USER') ? SMTP_USER : 'parijatresort21@gmail.com';
    $smtpPass = defined('SMTP_PASS') ? SMTP_PASS : '';
    $smtpPort = defined('SMTP_PORT') ? SMTP_PORT : 587; // TLS
    $smtpSecure = defined('SMTP_SECURE') ? SMTP_SECURE : 'tls'; // or 'ssl'
    $fromEmail = defined('FROM_EMAIL') ? FROM_EMAIL : 'no-reply@parijatresortbolpur.com';
    $fromName  = defined('FROM_NAME')  ? FROM_NAME  : 'Parijat Resort Bolpur';

    // If SMTP credentials configured, attempt PHPMailer first
    if (!empty($smtpPass) && $smtpPass !== 'your_email_app_password_here' && $smtpHost !== 'smtp.example.com') {
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->Timeout    = 8;
            $mail->isSMTP();
            $mail->Host       = $smtpHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtpUser;
            $mail->Password   = $smtpPass;
            $mail->SMTPSecure = $smtpSecure;
            $mail->Port       = $smtpPort;
            $mail->CharSet    = 'UTF-8';
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

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
        }
    }

    // Native PHP mail() fallback
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . $fromName . " <" . $fromEmail . ">\r\n";
    $headers .= "Reply-To: " . $fromEmail . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    $mailSent = @mail($to, $subject, $htmlBody, $headers);
    if (!$mailSent) {
        error_log("Fallback mail() recorded for $to with subject: $subject");
    }
    return $mailSent;
}
?>

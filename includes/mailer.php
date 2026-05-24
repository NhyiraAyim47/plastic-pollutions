<?php
// includes/mailer.php  –  PHPMailer OTP & notification sender
// Requires: vendor/PHPMailer (install via composer or manual download)

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Send OTP verification email
 *
 * @param string $toEmail   Recipient email address
 * @param string $toName    Recipient full name
 * @param string $otpCode   6-digit OTP
 * @return array ['success' => bool, 'message' => string]
 */
function sendOTPEmail(string $toEmail, string $toName, string $otpCode): array {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        // Recipients
        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($toEmail, $toName);
        $mail->addReplyTo(SMTP_FROM, SMTP_FROM_NAME);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Verification Code – ' . SITE_NAME;
        $mail->Body    = getOTPEmailBody($toName, $otpCode);
        $mail->AltBody = "Hello $toName,\n\nYour OTP verification code is: $otpCode\n\nThis code expires in 10 minutes.\n\nDo not share this code with anyone.\n\n– " . SITE_NAME;

        $mail->send();
        return ['success' => true, 'message' => 'OTP sent successfully.'];
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return ['success' => false, 'message' => 'Could not send OTP email. ' . $mail->ErrorInfo];
    }
}

/**
 * Send welcome email after successful verification
 */
function sendWelcomeEmail(string $toEmail, string $toName, string $puId): array {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($toEmail, $toName);

        $mail->isHTML(true);
        $mail->Subject = "Welcome to " . SITE_NAME . " – Account Verified!";
        $mail->Body    = getWelcomeEmailBody($toName, $puId);
        $mail->AltBody = "Welcome $toName!\n\nYour account has been verified. Your PU ID is: $puId\n\n– " . SITE_NAME;

        $mail->send();
        return ['success' => true];
    } catch (Exception $e) {
        error_log('Welcome email error: ' . $mail->ErrorInfo);
        return ['success' => false];
    }
}

/**
 * Send contact form notification to admin
 */
function sendContactNotification(string $name, string $email, string $subject, string $message): array {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress(SMTP_FROM, 'PlasticPollutions Admin');
        $mail->addReplyTo($email, $name);

        $mail->isHTML(true);
        $mail->Subject = "New Contact Message: " . htmlspecialchars($subject);
        $mail->Body    = "<h2>New Contact Message</h2>
            <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
            <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
            <p><strong>Subject:</strong> " . htmlspecialchars($subject) . "</p>
            <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>";

        $mail->send();
        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false];
    }
}

// -------------------------------------------------------
// HTML Email Templates
// -------------------------------------------------------

function getOTPEmailBody(string $name, string $otp): string {
    return <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#0a4d2f;font-family:Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center" style="padding:40px 20px;">
        <table width="600" style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.3);">
          <!-- Header -->
          <tr>
            <td style="background:linear-gradient(135deg,#0a4d2f,#1a8a52);padding:40px;text-align:center;">
              <h1 style="color:#fff;margin:0;font-size:28px;letter-spacing:2px;">🌿 PLASTIC POLLUTIONS</h1>
              <p style="color:#a8e6c3;margin:8px 0 0;">Pentecost University Environmental Action Group</p>
            </td>
          </tr>
          <!-- Body -->
          <tr>
            <td style="padding:40px;">
              <h2 style="color:#0a4d2f;margin:0 0 16px;">Email Verification</h2>
              <p style="color:#444;font-size:16px;line-height:1.6;">Hello <strong>{$name}</strong>,</p>
              <p style="color:#444;font-size:15px;line-height:1.6;">
                Thank you for registering with PlasticPollutions. Please use the one-time passcode below to verify your email address:
              </p>
              <!-- OTP Box -->
              <div style="text-align:center;margin:32px 0;">
                <div style="display:inline-block;background:linear-gradient(135deg,#0a4d2f,#1a8a52);border-radius:12px;padding:24px 48px;">
                  <span style="font-size:48px;font-weight:900;color:#fff;letter-spacing:12px;">{$otp}</span>
                </div>
              </div>
              <p style="color:#666;font-size:14px;text-align:center;">
                ⏱ This code expires in <strong>10 minutes</strong>.<br>
                🔒 Do not share this code with anyone.
              </p>
              <hr style="border:none;border-top:1px solid #e0e0e0;margin:32px 0;">
              <p style="color:#999;font-size:13px;">
                If you did not register on PlasticPollutions, please ignore this email. No action is required.
              </p>
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td style="background:#f5f5f5;padding:24px;text-align:center;">
              <p style="color:#aaa;font-size:12px;margin:0;">
                © 2026 PlasticPollutions – Pentecost University | Faculty of Engineering Science & Computing
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
HTML;
}

function getWelcomeEmailBody(string $name, string $puId): string {
    $siteUrl = SITE_URL;
    return <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#0a4d2f;font-family:Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center" style="padding:40px 20px;">
        <table width="600" style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.3);">
          <tr>
            <td style="background:linear-gradient(135deg,#0a4d2f,#1a8a52);padding:40px;text-align:center;">
              <h1 style="color:#fff;margin:0;font-size:28px;">🌿 Welcome to PlasticPollutions!</h1>
            </td>
          </tr>
          <tr>
            <td style="padding:40px;">
              <h2 style="color:#0a4d2f;">Account Verified ✅</h2>
              <p style="color:#444;font-size:16px;">Hello <strong>{$name}</strong>,</p>
              <p style="color:#444;font-size:15px;line-height:1.6;">
                Your account has been successfully verified. You are now a registered member of the PlasticPollutions community at Pentecost University.
              </p>
              <div style="background:#f0fdf4;border-left:4px solid #0a4d2f;padding:16px;border-radius:8px;margin:24px 0;">
                <p style="margin:0;color:#0a4d2f;font-size:14px;"><strong>Your Visitor ID:</strong></p>
                <p style="margin:8px 0 0;font-size:28px;font-weight:900;color:#1a8a52;letter-spacing:4px;">{$puId}</p>
              </div>
              <div style="text-align:center;margin:32px 0;">
                <a href="{$siteUrl}/auth/login.php"
                   style="background:linear-gradient(135deg,#0a4d2f,#1a8a52);color:#fff;text-decoration:none;padding:16px 40px;border-radius:50px;font-size:16px;font-weight:bold;">
                  Login to Your Dashboard →
                </a>
              </div>
            </td>
          </tr>
          <tr>
            <td style="background:#f5f5f5;padding:24px;text-align:center;">
              <p style="color:#aaa;font-size:12px;margin:0;">© 2026 PlasticPollutions – Pentecost University</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
HTML;
}

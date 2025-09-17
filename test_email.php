<?php
/**
 * Email Test Script
 * 
 * This script tests the email configuration without going through the registration process.
 * Run this script to verify your email settings are working correctly.
 */

require_once __DIR__ . '/includes/config.php';
$email_config = require_once __DIR__ . '/includes/email_config.php';

// Check if PHPMailer is available
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    include_once __DIR__ . '/vendor/autoload.php';
}

$phpmailer_available = class_exists('PHPMailer\\PHPMailer\\PHPMailer');

echo "<h1>Email Configuration Test</h1>";
echo "<h2>Configuration Status:</h2>";
echo "<ul>";
echo "<li>PHPMailer Available: " . ($phpmailer_available ? "✅ Yes" : "❌ No") . "</li>";
echo "<li>Use PHP Mail: " . ($email_config['use_php_mail'] ? "✅ Yes" : "❌ No") . "</li>";
echo "<li>SMTP Host: " . htmlspecialchars($email_config['smtp_host']) . "</li>";
echo "<li>SMTP Port: " . $email_config['smtp_port'] . "</li>";
echo "<li>SMTP Username: " . htmlspecialchars($email_config['smtp_username']) . "</li>";
echo "<li>From Email: " . htmlspecialchars($email_config['from_email']) . "</li>";
echo "</ul>";

// Test email sending
if (isset($_POST['test_email'])) {
    $test_email = $_POST['test_email'];
    $subject = "Test Email from Task App";
    $message = "This is a test email to verify your email configuration is working correctly.\n\nSent at: " . date('Y-m-d H:i:s');
    
    echo "<h2>Testing Email to: " . htmlspecialchars($test_email) . "</h2>";
    
    if ($phpmailer_available && !$email_config['use_php_mail']) {
        // Test with PHPMailer
        try {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            
            if ($email_config['debug']) {
                $mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
            }
            
            $mail->isSMTP();
            $mail->Host = $email_config['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = $email_config['smtp_username'];
            $mail->Password = $email_config['smtp_password'];
            
            if ($email_config['smtp_secure'] === 'ssl') {
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            }
            
            $mail->Port = $email_config['smtp_port'];
            $mail->setFrom($email_config['from_email'], $email_config['from_name']);
            $mail->addAddress($test_email);
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->isHTML(false);
            
            $mail->send();
            echo "<p style='color: green;'>✅ Email sent successfully using PHPMailer!</p>";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ PHPMailer Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        // Test with PHP mail()
        $headers = [
            'From: ' . $email_config['from_name'] . ' <' . $email_config['from_email'] . '>',
            'Reply-To: ' . $email_config['from_email'],
            'X-Mailer: PHP/' . phpversion(),
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8'
        ];
        
        if (mail($test_email, $subject, $message, implode("\r\n", $headers))) {
            echo "<p style='color: green;'>✅ Email sent successfully using PHP mail()!</p>";
        } else {
            echo "<p style='color: red;'>❌ PHP mail() failed. Check your server's mail configuration.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Email Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .form-group { margin: 20px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="email"] { padding: 8px; width: 300px; }
        button { padding: 10px 20px; background: #007cba; color: white; border: none; cursor: pointer; }
        button:hover { background: #005a87; }
        .instructions { background: #f0f8ff; padding: 20px; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="instructions">
        <h3>Instructions:</h3>
        <ol>
            <li>Update your email settings in <code>includes/email_config.php</code></li>
            <li>For Gmail: Enable 2FA and create an App Password</li>
            <li>Enter a test email address below and click "Send Test Email"</li>
            <li>Check your email inbox for the test message</li>
        </ol>
    </div>
    
    <form method="post">
        <div class="form-group">
            <label for="test_email">Test Email Address:</label>
            <input type="email" id="test_email" name="test_email" required placeholder="your-email@example.com">
        </div>
        <button type="submit">Send Test Email</button>
    </form>
    
    <p><a href="public/index.php">← Back to Registration Form</a></p>
</body>
</html>

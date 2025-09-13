<?php
/**
 * Mail handling script for user registration
 * Sends welcome emails using PHPMailer or fallback to mail()
 *
 * PHP Version 7.4+
 *
 * @category Mail
 * @package  TaskApp
 * @author   Developer <dev@example.com>
 * @license  https://opensource.org/licenses/MIT MIT
 * @version  GIT: $Id$
 * @link     https://github.com/example/taskapp
 */
require_once __DIR__ . '/../includes/config.php';

// If using PHPMailer, ensure composer autoload exists:
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    include_once __DIR__ . '/../vendor/autoload.php';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');

$errors = [];
if ($username === '' || mb_strlen($username) > 100) {
    $errors[] = 'Enter a valid name (max 100 chars).';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Enter a valid email address.';
}

if (!empty($errors)) {
    foreach ($errors as $err) {
        echo "<p style='color:red;'>".htmlspecialchars($err)."</p>";
    }
    echo '<p><a href="index.php">Go back</a></p>';
    exit;
}

// Insert user (prepared statement)
$stmt = $conn->prepare("INSERT INTO users (username, email) VALUES (?, ?)");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param('ss', $username, $email);
if (!$stmt->execute()) {
    // handle duplicate email (unique constraint)
    if ($conn->errno === 1062) {
        echo "<p>Email already registered.</p><p><a href='index.php'>Back</a></p>";
    } else {
        echo "<p>Database error: " . htmlspecialchars($conn->error) . "</p>";
    }
    $stmt->close();
    exit;
}
$stmt->close();

// Prepare email content
$subject = "Welcome to Task App!";
$bodyPlain = "Hello {$username},\n\nWelcome to our Task App. " .
            "We're glad to have you!";

// Try PHPMailer if available
if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
    try {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();

        // SMTP settings — replace with your provider's settings
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = 'MAILTRAP_USER';
        $mail->Password = 'MAILTRAP_PASS';
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 2525;

        $mail->setFrom('no-reply@taskapp.com', 'Task App');
        $mail->addAddress($email, $username);
        $mail->Subject = $subject;
        $mail->Body = $bodyPlain;
        $mail->send();

        $successMsg = "✅ Registered and welcome email sent to " .
                     htmlspecialchars($email) . ".";
        echo "<p>{$successMsg}</p>";
        echo "<p><a href='users.php'>View users</a></p>";
    } catch (Exception $e) {
        // fallback to mail()
        if (mail($email, $subject, $bodyPlain, "From: no-reply@taskapp.com")) {
            $fallbackMsg = "✅ Registered — email sent with PHP mail() " .
                          "(PHPMailer failed: " .
                          htmlspecialchars($e->getMessage()) . ").";
            echo "<p>{$fallbackMsg}</p>";
            echo "<p><a href='users.php'>View users</a></p>";
        } else {
            $errorMsg = "Registered but failed to send email " .
                       "(PHPMailer error: " .
                       htmlspecialchars($e->getMessage()) . ").";
            echo "<p>{$errorMsg}</p>";
            echo "<p><a href='users.php'>View users</a></p>";
        }
    }
} else {
    // PHPMailer not installed — use mail() as fallback
    if (mail($email, $subject, $bodyPlain, "From: no-reply@taskapp.com")) {
        echo "<p>✅ Registered — welcome email sent (using mail()).</p>";
    } else {
        $configMsg = "Registered but failed to send email. " .
                    "Configure SMTP or install PHPMailer for " .
                    "reliable delivery.";
        echo "<p>{$configMsg}</p>";
    }
    echo "<p><a href='users.php'>View users</a></p>";
}
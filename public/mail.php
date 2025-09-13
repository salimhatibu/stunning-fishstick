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
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Task App - Registration Error</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }

            .container {
                background: white;
                border-radius: 20px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                padding: 40px;
                width: 100%;
                max-width: 500px;
                animation: slideUp 0.6s ease-out;
                text-align: center;
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .error-icon {
                font-size: 4rem;
                color: #dc3545;
                margin-bottom: 20px;
            }

            h1 {
                color: #dc3545;
                font-size: 2rem;
                margin-bottom: 20px;
            }

            .error-list {
                background: #f8d7da;
                border: 1px solid #f5c6cb;
                border-radius: 10px;
                padding: 20px;
                margin-bottom: 30px;
                text-align: left;
            }

            .error-list ul {
                list-style: none;
                padding: 0;
            }

            .error-list li {
                color: #721c24;
                padding: 8px 0;
                border-bottom: 1px solid #f5c6cb;
            }

            .error-list li:last-child {
                border-bottom: none;
            }

            .btn {
                display: inline-block;
                padding: 12px 25px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                text-decoration: none;
                border-radius: 8px;
                font-weight: 600;
                transition: all 0.3s ease;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                font-size: 0.9rem;
            }

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="error-icon">⚠️</div>
            <h1>Registration Failed</h1>
            <div class="error-list">
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?php echo htmlspecialchars($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <a href="index.php" class="btn">Try Again</a>
        </div>
    </body>
    </html>
    <?php
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
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Task App - Email Already Registered</title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                }

                .container {
                    background: white;
                    border-radius: 20px;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                    padding: 40px;
                    width: 100%;
                    max-width: 500px;
                    animation: slideUp 0.6s ease-out;
                    text-align: center;
                }

                @keyframes slideUp {
                    from {
                        opacity: 0;
                        transform: translateY(30px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .warning-icon {
                    font-size: 4rem;
                    color: #ffc107;
                    margin-bottom: 20px;
                }

                h1 {
                    color: #856404;
                    font-size: 2rem;
                    margin-bottom: 20px;
                }

                .message {
                    background: #fff3cd;
                    border: 1px solid #ffeaa7;
                    border-radius: 10px;
                    padding: 20px;
                    margin-bottom: 30px;
                    color: #856404;
                }

                .btn {
                    display: inline-block;
                    padding: 12px 25px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    text-decoration: none;
                    border-radius: 8px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    font-size: 0.9rem;
                    margin: 0 10px;
                }

                .btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
                }

                .btn-secondary {
                    background: #6c757d;
                }

                .btn-secondary:hover {
                    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="warning-icon">⚠️</div>
                <h1>Email Already Registered</h1>
                <div class="message">
                    <p>The email address <strong><?php echo htmlspecialchars($email); ?></strong> is already registered in our system.</p>
                    <p>Please use a different email address or try logging in.</p>
                </div>
                <a href="index.php" class="btn">Try Different Email</a>
                <a href="users.php" class="btn btn-secondary">View Users</a>
            </div>
        </body>
        </html>
        <?php
    } else {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Task App - Database Error</title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                }

                .container {
                    background: white;
                    border-radius: 20px;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                    padding: 40px;
                    width: 100%;
                    max-width: 500px;
                    animation: slideUp 0.6s ease-out;
                    text-align: center;
                }

                @keyframes slideUp {
                    from {
                        opacity: 0;
                        transform: translateY(30px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .error-icon {
                    font-size: 4rem;
                    color: #dc3545;
                    margin-bottom: 20px;
                }

                h1 {
                    color: #dc3545;
                    font-size: 2rem;
                    margin-bottom: 20px;
                }

                .message {
                    background: #f8d7da;
                    border: 1px solid #f5c6cb;
                    border-radius: 10px;
                    padding: 20px;
                    margin-bottom: 30px;
                    color: #721c24;
                }

                .btn {
                    display: inline-block;
                    padding: 12px 25px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    text-decoration: none;
                    border-radius: 8px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    font-size: 0.9rem;
                }

                .btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="error-icon">❌</div>
                <h1>Database Error</h1>
                <div class="message">
                    <p>Sorry, there was an error processing your registration.</p>
                    <p><strong>Error:</strong> <?php echo htmlspecialchars($conn->error); ?></p>
                </div>
                <a href="index.php" class="btn">Try Again</a>
            </div>
        </body>
        </html>
        <?php
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
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Task App - Registration Successful</title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                }

                .container {
                    background: white;
                    border-radius: 20px;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                    padding: 40px;
                    width: 100%;
                    max-width: 500px;
                    animation: slideUp 0.6s ease-out;
                    text-align: center;
                }

                @keyframes slideUp {
                    from {
                        opacity: 0;
                        transform: translateY(30px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .success-icon {
                    font-size: 4rem;
                    color: #28a745;
                    margin-bottom: 20px;
                    animation: bounce 1s ease-in-out;
                }

                @keyframes bounce {
                    0%, 20%, 50%, 80%, 100% {
                        transform: translateY(0);
                    }
                    40% {
                        transform: translateY(-10px);
                    }
                    60% {
                        transform: translateY(-5px);
                    }
                }

                h1 {
                    color: #28a745;
                    font-size: 2rem;
                    margin-bottom: 20px;
                }

                .message {
                    background: #d4edda;
                    border: 1px solid #c3e6cb;
                    border-radius: 10px;
                    padding: 20px;
                    margin-bottom: 30px;
                    color: #155724;
                }

                .btn {
                    display: inline-block;
                    padding: 12px 25px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    text-decoration: none;
                    border-radius: 8px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    font-size: 0.9rem;
                    margin: 0 10px;
                }

                .btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
                }

                .btn-secondary {
                    background: #28a745;
                }

                .btn-secondary:hover {
                    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="success-icon">🎉</div>
                <h1>Registration Successful!</h1>
                <div class="message">
                    <p><?php echo $successMsg; ?></p>
                    <p>Welcome to our Task App community!</p>
                </div>
                <a href="users.php" class="btn">View All Users</a>
                <a href="index.php" class="btn btn-secondary">Register Another</a>
            </div>
        </body>
        </html>
        <?php
    } catch (Exception $e) {
        // fallback to mail()
        if (mail($email, $subject, $bodyPlain, "From: no-reply@taskapp.com")) {
            $fallbackMsg = "✅ Registered — email sent with PHP mail() " .
                          "(PHPMailer failed: " .
                          htmlspecialchars($e->getMessage()) . ").";
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Task App - Registration Successful (Fallback)</title>
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }

                    body {
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        min-height: 100vh;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        padding: 20px;
                    }

                    .container {
                        background: white;
                        border-radius: 20px;
                        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                        padding: 40px;
                        width: 100%;
                        max-width: 500px;
                        animation: slideUp 0.6s ease-out;
                        text-align: center;
                    }

                    @keyframes slideUp {
                        from {
                            opacity: 0;
                            transform: translateY(30px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }

                    .success-icon {
                        font-size: 4rem;
                        color: #28a745;
                        margin-bottom: 20px;
                    }

                    h1 {
                        color: #28a745;
                        font-size: 2rem;
                        margin-bottom: 20px;
                    }

                    .message {
                        background: #d4edda;
                        border: 1px solid #c3e6cb;
                        border-radius: 10px;
                        padding: 20px;
                        margin-bottom: 30px;
                        color: #155724;
                    }

                    .btn {
                        display: inline-block;
                        padding: 12px 25px;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        text-decoration: none;
                        border-radius: 8px;
                        font-weight: 600;
                        transition: all 0.3s ease;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                        font-size: 0.9rem;
                        margin: 0 10px;
                    }

                    .btn:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="success-icon">✅</div>
                    <h1>Registration Successful!</h1>
                    <div class="message">
                        <p><?php echo $fallbackMsg; ?></p>
                        <p>Welcome to our Task App community!</p>
                    </div>
                    <a href="users.php" class="btn">View All Users</a>
                </div>
            </body>
            </html>
            <?php
        } else {
            $errorMsg = "Registered but failed to send email " .
                       "(PHPMailer error: " .
                       htmlspecialchars($e->getMessage()) . ").";
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Task App - Registration with Email Error</title>
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }

                    body {
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        min-height: 100vh;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        padding: 20px;
                    }

                    .container {
                        background: white;
                        border-radius: 20px;
                        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                        padding: 40px;
                        width: 100%;
                        max-width: 500px;
                        animation: slideUp 0.6s ease-out;
                        text-align: center;
                    }

                    @keyframes slideUp {
                        from {
                            opacity: 0;
                            transform: translateY(30px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }

                    .warning-icon {
                        font-size: 4rem;
                        color: #ffc107;
                        margin-bottom: 20px;
                    }

                    h1 {
                        color: #856404;
                        font-size: 2rem;
                        margin-bottom: 20px;
                    }

                    .message {
                        background: #fff3cd;
                        border: 1px solid #ffeaa7;
                        border-radius: 10px;
                        padding: 20px;
                        margin-bottom: 30px;
                        color: #856404;
                    }

                    .btn {
                        display: inline-block;
                        padding: 12px 25px;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        text-decoration: none;
                        border-radius: 8px;
                        font-weight: 600;
                        transition: all 0.3s ease;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                        font-size: 0.9rem;
                        margin: 0 10px;
                    }

                    .btn:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="warning-icon">⚠️</div>
                    <h1>Registration Complete</h1>
                    <div class="message">
                        <p><?php echo $errorMsg; ?></p>
                        <p>Your account was created successfully, but we couldn't send the welcome email.</p>
                    </div>
                    <a href="users.php" class="btn">View All Users</a>
                </div>
            </body>
            </html>
            <?php
        }
    }
} else {
    // PHPMailer not installed — use mail() as fallback
    if (mail($email, $subject, $bodyPlain, "From: no-reply@taskapp.com")) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Task App - Registration Successful</title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                }

                .container {
                    background: white;
                    border-radius: 20px;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                    padding: 40px;
                    width: 100%;
                    max-width: 500px;
                    animation: slideUp 0.6s ease-out;
                    text-align: center;
                }

                @keyframes slideUp {
                    from {
                        opacity: 0;
                        transform: translateY(30px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .success-icon {
                    font-size: 4rem;
                    color: #28a745;
                    margin-bottom: 20px;
                }

                h1 {
                    color: #28a745;
                    font-size: 2rem;
                    margin-bottom: 20px;
                }

                .message {
                    background: #d4edda;
                    border: 1px solid #c3e6cb;
                    border-radius: 10px;
                    padding: 20px;
                    margin-bottom: 30px;
                    color: #155724;
                }

                .btn {
                    display: inline-block;
                    padding: 12px 25px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    text-decoration: none;
                    border-radius: 8px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    font-size: 0.9rem;
                    margin: 0 10px;
                }

                .btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="success-icon">✅</div>
                <h1>Registration Successful!</h1>
                <div class="message">
                    <p>✅ Registered — welcome email sent (using mail()).</p>
                    <p>Welcome to our Task App community!</p>
                </div>
                <a href="users.php" class="btn">View All Users</a>
            </div>
        </body>
        </html>
        <?php
    } else {
        $configMsg = "Registered but failed to send email. " .
                    "Configure SMTP or install PHPMailer for " .
                    "reliable delivery.";
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Task App - Registration with Email Error</title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                }

                .container {
                    background: white;
                    border-radius: 20px;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                    padding: 40px;
                    width: 100%;
                    max-width: 500px;
                    animation: slideUp 0.6s ease-out;
                    text-align: center;
                }

                @keyframes slideUp {
                    from {
                        opacity: 0;
                        transform: translateY(30px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .warning-icon {
                    font-size: 4rem;
                    color: #ffc107;
                    margin-bottom: 20px;
                }

                h1 {
                    color: #856404;
                    font-size: 2rem;
                    margin-bottom: 20px;
                }

                .message {
                    background: #fff3cd;
                    border: 1px solid #ffeaa7;
                    border-radius: 10px;
                    padding: 20px;
                    margin-bottom: 30px;
                    color: #856404;
                }

                .btn {
                    display: inline-block;
                    padding: 12px 25px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    text-decoration: none;
                    border-radius: 8px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    font-size: 0.9rem;
                    margin: 0 10px;
                }

                .btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="warning-icon">⚠️</div>
                <h1>Registration Complete</h1>
                <div class="message">
                    <p><?php echo $configMsg; ?></p>
                    <p>Your account was created successfully, but we couldn't send the welcome email.</p>
                </div>
                <a href="users.php" class="btn">View All Users</a>
            </div>
        </body>
        </html>
        <?php
    }
}
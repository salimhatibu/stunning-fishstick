<?php
/**
 * Email Configuration
 * 
 * Update these settings with your email provider's SMTP details
 * 
 * Common SMTP Settings:
 * - Gmail: smtp.gmail.com, port 587, TLS
 * - Outlook: smtp-mail.outlook.com, port 587, TLS
 * - Yahoo: smtp.mail.yahoo.com, port 587, TLS
 * - Custom SMTP: Check with your hosting provider
 */

return [
    // SMTP Server Settings
    'smtp_host' => 'smtp.gmail.com',           // Your SMTP server
    'smtp_port' => 587,                        // Port (587 for TLS, 465 for SSL)
    'smtp_username' => 'salimhatibu786@gmail.com', // Your email address
    'smtp_password' => 'goln cnkg dyeq xmii',    // Your email password or app password
    'smtp_secure' => 'tls',                    // 'tls' or 'ssl'
    
    // Email Settings
    'from_email' => 'salimhatibu786@gmail.com',    // Sender email
    'from_name' => 'Task App',                 // Sender name
    
    // Alternative: Use PHP's built-in mail() function
    'use_php_mail' => false,                   // Set to true to use PHP mail() instead of SMTP
    
    // Debug settings
    'debug' => true,                          // Set to true for debugging
    'debug_output' => 'error_log'              // 'echo' or 'error_log'
];
?>
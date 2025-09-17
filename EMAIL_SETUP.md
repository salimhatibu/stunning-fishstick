# Email Setup Guide

This guide will help you fix the email delivery issue in your Task App.

## The Problem
Your registration system is working, but emails are not being sent because:
1. PHPMailer is not installed
2. SMTP credentials are not configured
3. PHP's mail() function may not be properly configured

## Solutions

### Option 1: Quick Fix - Use PHP's mail() function (Recommended for testing)

1. **Update email configuration:**
   - Open `includes/email_config.php`
   - Set `'use_php_mail' => true`
   - Update `'from_email'` to a valid email address

2. **Configure your server's mail settings:**
   - For XAMPP/WAMP: Configure sendmail in `php.ini`
   - For live hosting: Contact your hosting provider

### Option 2: Install PHPMailer (Recommended for production)

1. **Install Composer** (if not already installed):
   - Download from: https://getcomposer.org/download/
   - Run the installer

2. **Install PHPMailer:**
   ```bash
   composer install
   ```

3. **Configure SMTP settings:**
   - Open `includes/email_config.php`
   - Update the SMTP settings with your email provider's details

### Option 3: Use Gmail SMTP (Most reliable)

1. **Enable 2-Factor Authentication** on your Gmail account
2. **Generate an App Password:**
   - Go to Google Account settings
   - Security → 2-Step Verification → App passwords
   - Generate a password for "Mail"

3. **Update configuration:**
   ```php
   'smtp_host' => 'smtp.gmail.com',
   'smtp_port' => 587,
   'smtp_username' => 'your-email@gmail.com',
   'smtp_password' => 'your-16-character-app-password',
   'smtp_secure' => 'tls',
   ```

## Common SMTP Settings

### Gmail
- Host: `smtp.gmail.com`
- Port: `587` (TLS) or `465` (SSL)
- Security: `tls` or `ssl`

### Outlook/Hotmail
- Host: `smtp-mail.outlook.com`
- Port: `587`
- Security: `tls`

### Yahoo
- Host: `smtp.mail.yahoo.com`
- Port: `587`
- Security: `tls`

## Testing

After configuration, test the registration form. You should see:
- ✅ "Registration Successful!" with email confirmation
- ❌ If still failing, check the error message for specific issues

## Troubleshooting

1. **"PHPMailer not found"**: Install PHPMailer or use PHP mail()
2. **"SMTP authentication failed"**: Check username/password
3. **"Connection refused"**: Check SMTP host/port settings
4. **"mail() function not available"**: Configure your server's mail settings

## Need Help?

If you're still having issues:
1. Check your server's error logs
2. Verify your email provider's SMTP settings
3. Test with a simple PHP mail script first
4. Contact your hosting provider for mail configuration help

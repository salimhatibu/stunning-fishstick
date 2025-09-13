# Task App - User Registration System

A modern, responsive PHP web application for user registration with email notifications.

## Features

- **Modern UI Design**: Beautiful, responsive interface with gradient backgrounds and smooth animations
- **User Registration**: Simple signup form with client-side and server-side validation
- **Email Notifications**: Welcome emails sent using PHPMailer (with fallback to PHP mail())
- **User Management**: View all registered users with statistics
- **Error Handling**: Comprehensive error handling with styled error pages
- **Mobile Responsive**: Optimized for all device sizes
- **Database Integration**: MySQL database with automatic table creation

## Installation

1. **Database Setup**: Create a MySQL database named `taskapp`
2. **Configuration**: Update database credentials in `includes/config.php`
3. **Web Server**: Place files in your web server directory
4. **Optional**: Install PHPMailer via Composer for better email delivery

## File Structure

```
stunning-fishstick/
├── public/
│   ├── index.php          # Main registration form
│   ├── users.php          # User listing page
│   └── mail.php           # Registration processing & email handling
├── includes/
│   └── config.php         # Database configuration
└── README.md
```

## Usage

1. **Register**: Visit `index.php` to sign up new users
2. **View Users**: Check `users.php` to see all registered users
3. **Email Setup**: Configure SMTP settings in `mail.php` for reliable email delivery

## Recent Improvements

- ✅ Modern, responsive UI design with CSS animations
- ✅ Enhanced form validation with real-time feedback
- ✅ Styled error and success pages
- ✅ User statistics dashboard
- ✅ Mobile-friendly responsive design
- ✅ Improved database schema with timestamps
- ✅ Better error handling and user experience
- ✅ Consistent navigation between pages

## Requirements

- PHP 7.4+
- MySQL 5.7+
- Web server (Apache/Nginx)
- Optional: PHPMailer for email functionality
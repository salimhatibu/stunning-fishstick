<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task App - Sign Up</title>
    <link href="index.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Task App</h1>
            <p>Join our community and start managing your tasks efficiently</p>
        </div>

        <form method="post" action="mail.php" id="signupForm">
            <div class="form-group">
                <label for="username">Full Name</label>
                <input 
                    type="text" 
                    id="username"
                    name="username" 
                    required 
                    maxlength="100"
                    placeholder="Enter your full name"
                    autocomplete="name"
                >
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email"
                    name="email" 
                    required 
                    maxlength="255"
                    placeholder="Enter your email address"
                    autocomplete="email"
                >
            </div>

            <button type="submit" class="btn">Create Account</button>
        </form>

        <div class="nav-links">
            <a href="users.php">View Registered Users</a>
        </div>
    </div>

    <script>
        // Form validation and enhancement
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            
            if (username.length === 0) {
                e.preventDefault();
                showError('Please enter your full name.');
                return;
            }
            
            if (username.length > 100) {
                e.preventDefault();
                showError('Name must be less than 100 characters.');
                return;
            }
            
            if (email.length === 0) {
                e.preventDefault();
                showError('Please enter your email address.');
                return;
            }
            
            if (!isValidEmail(email)) {
                e.preventDefault();
                showError('Please enter a valid email address.');
                return;
            }
        });

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function showError(message) {
            // Remove existing error messages
            const existingError = document.querySelector('.error-message');
            if (existingError) {
                existingError.remove();
            }
            
            // Create new error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.textContent = message;
            
            // Insert before the form
            const form = document.getElementById('signupForm');
            form.parentNode.insertBefore(errorDiv, form);
            
            // Scroll to error
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // Add loading state to button
        document.getElementById('signupForm').addEventListener('submit', function() {
            const btn = this.querySelector('.btn');
            btn.textContent = 'Creating Account...';
            btn.disabled = true;
        });
    </script>
</body>
</html>

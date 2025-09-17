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
    <script src="index.js"></script>
</body>
</html>

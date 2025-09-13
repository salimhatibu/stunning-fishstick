<!-- public/index.php -->
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Task App - Signup</title>
</head>
<body>
  <h1>Sign up</h1>
  <form method="post" action="mail.php">
    <label>
      Name:<br>
      <input type="text" name="username" required maxlength="100">
    </label><br><br>

    <label>
      Email:<br>
      <input type="email" name="email" required maxlength="255">
    </label><br><br>

    <button type="submit">Register</button>
  </form>
</body>
</html>

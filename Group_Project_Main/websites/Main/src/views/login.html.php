<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Technify University - Login</title>
  <link rel="stylesheet" href="assets/css/portal_login.css">
</head>
<body class="minimal-theme">
  <img class="bg-image" src="../assets/images/portal.png" alt="">

  <div class="welcome-wrapper">
    <div class="welcome-content">
      <img src="../assets/images/tu.png" alt="Technify University Logo" class="logo-image">
      <h1>Technify University</h1>
      <form class="login-form" method="POST" action="">
        <h2 class="login-heading">University Login Portal</h2>

        <?php if (!empty($error_message)): ?>
          <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
          <label for="password">Password:</label>
          <div class="password-wrapper">
            <input type="password" id="password" name="password" required>
          </div>
          <div class="show-password">
            <input type="checkbox" id="showPasswordToggle">
            <label for="showPasswordToggle">Show Password</label>
          </div>
        </div>

        <button type="submit" name="login" class="btn-login">Login</button>
      </form>
    </div>
    <footer>&copy; 2025 Technify University</footer>
  </div>

  <script src="/assets/js/main.js"></script>
</body>
</html>

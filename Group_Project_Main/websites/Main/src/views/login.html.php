<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technify University - Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="logo">
            <h1>Technify University</h1>
        </div>
        <form class="login-form" method="POST" action="">
            <h2>Student Portal Login</h2>
            <?php if (!empty($error_message)): ?>
                <div class="error-message" style="display: block;"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <div class="form-group">
                <label for="username">Student Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div style="position:relative;">
                    <input type="password" id="password" name="password" required style="padding-right:32px;">
                    <button type="button" id="togglePassword" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; font-size:16px; color:#888;" tabindex="-1">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
            </div>
            <button type="submit" name="login" class="login-btn">Login</button>
        </form>
        <div class="forgot-password">
            <a href="#">Forgot your password?</a>
        </div>
        <script src="public/assets/js/main.js"></script>
    </div>
</body>
</html>
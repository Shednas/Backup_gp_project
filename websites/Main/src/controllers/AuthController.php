<?php
require_once __DIR__ . '/../db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error_message = '';

// Check if the user is already logged in, redirect to appropriate page
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && !isset($_POST['login'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header('Location: ../index.php?page=admin_dashboard');
    } else {
        header('Location: ../index.php');
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = fetchOne("SELECT * FROM users WHERE username = ?", [$username]);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user['id'];
        require_once __DIR__ . '/../helper.php';
        $role = get_user_role($user['id']);
        
        $_SESSION['role'] = $role;

        // Redirect after login
        if ($_SESSION['role'] === 'admin') {
            header('Location: ../index.php?page=admin_dashboard');
        } else {
            header('Location: ../index.php');
        }
        exit;
    } else {
        $error_message = 'Invalid username or password!';
    }
}

// Show login form
require __DIR__ . '/../views/login.html.php';

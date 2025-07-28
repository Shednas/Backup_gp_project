<?php
require_once __DIR__ . '/../db.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = fetchOne("SELECT * FROM users WHERE username = ?", [$username]);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = (bool)$user['check_admin'];
        $_SESSION['is_student'] = (bool)$user['check_student'];
        $_SESSION['is_lecturer'] = (bool)$user['check_lecturer'];

        // Redirect based on role
        if ($_SESSION['is_admin']) {
            header('Location: index.php?page=admin_dashboard');
        } else {
            header('Location: index.php');
        }
        exit;
    } else {
        $error_message = 'Invalid username or password!';
    }
}

// Render the login view
require __DIR__ . '/../views/login.html.php';
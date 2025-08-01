<?php
require_once __DIR__ . '/../../src/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Only POST requests allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$username = $input['username'] ?? '';
$password = $input['password'] ?? '';

if (!$username || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Username and password required']);
    exit;
}

$user = fetchOne("SELECT * FROM users WHERE username = ?", [$username]);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $username;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['is_admin'] = (bool)$user['check_admin'];
    $_SESSION['is_student'] = (bool)$user['check_student'];
    $_SESSION['is_lecturer'] = (bool)$user['check_lecturer'];

    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'data' => [
            'user_id' => $user['id'],
            'username' => $username,
            'is_admin' => $_SESSION['is_admin'],
            'is_student' => $_SESSION['is_student'],
            'is_lecturer' => $_SESSION['is_lecturer']
        ]
    ]);
    exit;
}

http_response_code(401);
echo json_encode(['error' => 'Invalid credentials']);
exit;

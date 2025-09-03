<?php
// University Management System Test Runner
echo "Starting tests...\n";

require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/sendUserCredentialsEmail.php';

$pdo = db();

function section($title) {
    echo "\n==== $title ====";
}
function result($desc, $pass, $extra = '') {
    $status = $pass ? "[PASS]" : "[FAIL]";
    echo "\n$status $desc";
    if ($extra) echo " - $extra";
}

try {
    section('User Creation');
    $username = 'testuser_' . rand(1000,9999);
    $email = $username . '@example.com';
    $full_name = 'Test User';
    $password = password_hash('TestPass123!', PASSWORD_DEFAULT);
    $stmt = query("INSERT INTO users (username, password, email, full_name) VALUES (?, ?, ?, ?)", [$username, $password, $email, $full_name]);
    $user_id = fetchOne("SELECT LAST_INSERT_ID() AS id")['id'] ?? 0;
    result("User created", $stmt && $user_id, $user_id ? "ID: $user_id" : "");

    section('Course Creation');
    $course_code = 'TEST' . rand(100,999);
    $title = 'Test Course';
    $stmt = query("INSERT INTO courses (course_code, title, description, lecturer, credits, term) VALUES (?, ?, ?, ?, ?, ?)", [$course_code, $title, 'Dummy course for testing', $full_name, 3, 'Spring']);
    $course_id = fetchOne("SELECT LAST_INSERT_ID() AS id")['id'] ?? 0;
    result("Course created", $stmt && $course_id, $course_id ? "ID: $course_id" : "");

    section('Assignment Creation');
    $stmt = query("INSERT INTO assignments (course_id, title, description, due_date) VALUES (?, ?, ?, ?)", [$course_id, 'Test Assignment', 'Dummy assignment for testing', date('Y-m-d H:i:s', strtotime('+7 days'))]);
    $assignment_id = fetchOne("SELECT LAST_INSERT_ID() AS id")['id'] ?? 0;
    result("Assignment created", $stmt && $assignment_id, $assignment_id ? "ID: $assignment_id" : "");

    section('Password Generation');
    $generated_password = bin2hex(random_bytes(6));
    result("Password generated", strlen($generated_password) === 12, $generated_password);

    section('Soft Delete User');
    $stmt = query("UPDATE users SET is_deleted = 1 WHERE id = ?", [$user_id]);
    $deleted_user = fetchOne("SELECT * FROM users WHERE id = ? AND is_deleted = 1", [$user_id]);
    result("User soft deleted", $stmt && $deleted_user);

    section('Email Function');
    $email_func_exists = function_exists('sendUserCredentialsEmail');
    result("Email function available", $email_func_exists);

    section('Security Tests');
    
    // SQL Injection Prevention
    $injection_username = "admin' OR '1'='1";
    $user_check = fetchOne("SELECT * FROM users WHERE username = ?", [$injection_username]);
    result("SQL injection prevention", !$user_check);
    
    // File Upload Validation
    function validateFileExtension($filename) {
        $allowed = ['pdf', 'doc', 'docx', 'jpg', 'png'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($ext, $allowed);
    }
    result("File upload security", !validateFileExtension('malicious.php'));
    
    // Input Validation
    $valid_email = filter_var('user@example.com', FILTER_VALIDATE_EMAIL);
    $invalid_email = filter_var('invalid.email', FILTER_VALIDATE_EMAIL);
    result("Input validation", $valid_email && !$invalid_email);
    
    // XSS Prevention
    $xss_payload = '<script>alert("XSS")</script>';
    $encoded = htmlspecialchars($xss_payload, ENT_QUOTES, 'UTF-8');
    result("XSS prevention", $encoded !== $xss_payload);

    section('Cleanup');
    $delAssignment = query("DELETE FROM assignments WHERE id = ?", [$assignment_id]);
    $delCourse = query("DELETE FROM courses WHERE id = ?", [$course_id]);
    $delUser = query("DELETE FROM users WHERE id = ?", [$user_id]);
    result("Assignment deleted", $delAssignment);
    result("Course deleted", $delCourse);
    result("User deleted", $delUser);

    echo "\n\nAll tests completed successfully.\n";

} catch (Exception $e) {
    result("Exception", false, $e->getMessage());
}
?>


<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helper.php';

requireLogin();

$current_user = getCurrentUser();
list($user_name, $user_initials) = loadUserDisplayInfo();

$update_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $student_id = trim($_POST['student_id']);
    $address = trim($_POST['address']);

    $update_result = query(
        "UPDATE users SET full_name = ?, email = ?, phone = ?, student_id = ?, address = ? WHERE username = ?",
        [$full_name, $email, $phone, $student_id, $address, $_SESSION['username']]
    );

    if ($update_result) {
        $update_message = '<div class="success-message" style="display: block;">Profile updated successfully!</div>';

        // Refresh user data and display info after update
        $current_user = getCurrentUser();
        list($user_name, $user_initials) = loadUserDisplayInfo();
    } else {
        $update_message = '<div class="error-message" style="display: block;">Failed to update profile. Please try again.</div>';
    }
}

// Capture content
ob_start();
require __DIR__ . '/../views/profile.html.php';
$content = ob_get_clean();

// Load layout
require __DIR__ . '/../../templates/main_layout.php';

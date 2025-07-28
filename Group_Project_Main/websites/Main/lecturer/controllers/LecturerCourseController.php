<?php
require_once __DIR__ . '/../../src/db.php';
require_once __DIR__ . '/../../src/helper.php';

requireLogin();

$current_user = getCurrentUser();

if (!isLecturer($current_user)) {
    header('Location: index.php?page=courses');
    exit;
}

$course_id = $_GET['id'] ?? 0;
if (!$course_id) {
    header('Location: index.php?page=courses');
    exit;
}

$current_section = $_GET['section'] ?? 'manage_content';

$course = fetchOne("SELECT * FROM courses WHERE id = ?", [$course_id]);
if (!$course) {
    header('Location: index.php?page=courses');
    exit;
}

if (!canManageCourse($course_id, $current_user)) {
    header('Location: index.php?page=courses&error=access_denied');
    exit;
}

$message = '';
$error = '';

// Handle form submissions (same logic as before)
if ($_POST) {
    // ... (copy your form handling logic here)
}

// Get user initials and name
$user_initials = '';
$user_name = $current_user['full_name'] ?? $_SESSION['username'];
if ($current_user && $current_user['full_name']) {
    $names = explode(' ', trim($current_user['full_name']));
    $user_initials = strtoupper(substr($names[0], 0, 1));
    if (count($names) > 1) {
        $user_initials .= strtoupper(substr($names[count($names)-1], 0, 1));
    }
} else {
    $user_initials = strtoupper(substr($_SESSION['username'], 0, 2));
}

// Get data for different sections
$announcements = [];
$assignments = [];
$materials = [];
$submissions = [];

if ($current_section === 'manage_announcement') {
    $announcements = fetchAll("SELECT * FROM course_announcements WHERE course_id = ? ORDER BY created_at DESC", [$course_id]);
} elseif ($current_section === 'manage_assignment') {
    $assignments = fetchAll("SELECT * FROM assignments WHERE course_id = ? ORDER BY due_date ASC", [$course_id]);
} elseif ($current_section === 'manage_content') {
    $materials = fetchAll("SELECT * FROM course_materials WHERE course_id = ? ORDER BY upload_date DESC", [$course_id]);
} elseif ($current_section === 'manage_submissions') {
    $submissions = fetchAll("SELECT s.*, a.title as assignment_title, a.max_score, u.full_name as student_name, u.username as student_username
                            FROM assignment_submissions s 
                            JOIN assignments a ON s.assignment_id = a.id 
                            JOIN users u ON s.student_id = u.id 
                            WHERE a.course_id = ? 
                            ORDER BY s.submitted_at DESC", [$course_id]);
}

// Capture content
ob_start();
require __DIR__ . '/../views/lecturer_course_dashboard.html.php';
$content = ob_get_clean();

// Load layout
require __DIR__ . '/../../templates/main_layout.php';

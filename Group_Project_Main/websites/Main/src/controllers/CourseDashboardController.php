<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helper.php';

// Check if user is logged in
requireLogin();

// Get logged-in user's information
$current_user = getCurrentUser();
list($user_name, $user_initials) = loadUserDisplayInfo();

// Get course ID from URL
$course_id = $_GET['id'] ?? 0;
if (!$course_id) {
    header('Location: index.php?page=courses');
    exit;
}

// Get current section (default to content)
$current_section = $_GET['section'] ?? 'content';

// Handle file submission
$submission_message = '';
$submission_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_assignment'])) {
    $assignment_id = $_POST['assignment_id'] ?? null;

    if (!$assignment_id) {
        $submission_error = 'No assignment selected.';
    } elseif (isset($_FILES['submission_file']) && $_FILES['submission_file']['error'] == 0) {
        $upload_dir = 'assets/files/submissions/';
        $file_name = time() . '_' . $_FILES['submission_file']['name'];
        $file_path = $upload_dir . $file_name;

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (move_uploaded_file($_FILES['submission_file']['tmp_name'], $file_path)) {
            $submission_data = [
                'assignment_id' => $assignment_id,
                'student_id' => $current_user['id'],
                'file_path' => $file_path,
                'submission_text' => $_POST['submission_comments'] ?? '',
                'submitted_at' => date('Y-m-d H:i:s')
            ];

            $insert_query = "INSERT INTO assignment_submissions (assignment_id, student_id, file_path, submission_text, submitted_at) 
                             VALUES (?, ?, ?, ?, ?)";

            if (query($insert_query, array_values($submission_data))) {
                $submission_message = 'Assignment submitted successfully!';
            } else {
                $submission_error = 'Error saving submission to database.';
                unlink($file_path);
            }
        } else {
            $submission_error = 'Error uploading file. Please try again.';
        }
    } else {
        $submission_error = 'Please select a file to submit.';
    }
}

// Get course information
$course = fetchOne("SELECT * FROM courses WHERE id = ?", [$course_id]);
if (!$course) {
    header('Location: index.php?page=courses');
    exit;
}

// Check if user has access to this course
if (!canAccessCourse($course_id, $current_user)) {
    header('Location: index.php?page=courses&error=access_denied');
    exit;
}

// For the view
$user_initials = getUserInitials($current_user);
$user_name = getUserDisplayName($current_user);

// Fetch data for each section
$announcements = [];
$grades = [];
$graded_submissions = [];
$assignments = [];
$user_submissions = [];
$assignment = null;

if ($current_section === 'announcement') {
    $announcements = fetchAll("SELECT ca.*, u.full_name as lecturer_name 
        FROM course_announcements ca 
        JOIN users u ON ca.lecturer_id = u.id 
        WHERE ca.course_id = ? 
        ORDER BY ca.created_at DESC", [$course_id]);
} elseif ($current_section === 'grade') {
    $grades = fetchAll("SELECT cg.*, a.title as assignment_title, a.due_date 
        FROM course_grades cg 
        LEFT JOIN assignments a ON cg.assignment_id = a.id 
        WHERE cg.course_id = ? AND cg.student_id = ? 
        ORDER BY cg.grade_date DESC", [$course_id, $current_user['id']]);
    $graded_submissions = fetchAll("SELECT s.*, a.title as assignment_title, a.max_score, a.due_date 
        FROM assignment_submissions s 
        JOIN assignments a ON s.assignment_id = a.id 
        WHERE a.course_id = ? AND s.student_id = ? AND s.grade IS NOT NULL 
        ORDER BY s.graded_at DESC", [$course_id, $current_user['id']]);
} elseif ($current_section === 'assignment') {
    $assignments = fetchAll("SELECT * FROM assignments WHERE course_id = ? ORDER BY due_date ASC", [$course_id]);
} elseif ($current_section === 'submission') {
    $assignment_id = $_GET['assignment_id'] ?? null;
    if ($assignment_id) {
        $assignment = fetchOne("SELECT * FROM assignments WHERE id = ? AND course_id = ?", [$assignment_id, $course_id]);
    }
    $assignments = fetchAll("SELECT * FROM assignments WHERE course_id = ? ORDER BY due_date ASC", [$course_id]);
    $user_submissions = fetchAll("SELECT s.*, a.title as assignment_title 
        FROM assignment_submissions s 
        JOIN assignments a ON s.assignment_id = a.id 
        WHERE a.course_id = ? AND s.student_id = ? 
        ORDER BY s.submitted_at DESC", [$course_id, $current_user['id']]);
}

// Capture content
ob_start();
require __DIR__ . '/../views/course_dashboard.html.php';
$content = ob_get_clean();

// Load layout
require __DIR__ . '/../../templates/main_layout.php';
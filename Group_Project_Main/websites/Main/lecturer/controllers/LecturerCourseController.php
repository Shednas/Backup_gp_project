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

// Handle form submissions
if ($_POST) {
    // Add Announcement
    if (isset($_POST['add_announcement'])) {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $is_important = isset($_POST['is_important']) ? 1 : 0;
        if ($title && $content) {
            try {
                query("INSERT INTO course_announcements (course_id, lecturer_id, title, content, is_important, created_at) VALUES (?, ?, ?, ?, ?, NOW())",
                    [$course_id, $current_user['id'], $title, $content, $is_important]);
                $message = "Announcement added successfully!";
            } catch (Exception $e) {
                $error = "Error adding announcement: " . $e->getMessage();
            }
        } else {
            $error = "Please fill in all required fields for announcement.";
        }
    }

    // Add Assignment
    if (isset($_POST['add_assignment'])) {
        $title = trim($_POST['assignment_title'] ?? '');
        $description = trim($_POST['assignment_description'] ?? '');
        $due_date = $_POST['due_date'] ?? null;
        $max_score = (float)($_POST['max_score'] ?? 100);
        $weight_percentage = (float)($_POST['weight_percentage'] ?? 0);
        $instructions = trim($_POST['instructions'] ?? '');
        $file_path = '';
        if (isset($_FILES['assignment_file']) && $_FILES['assignment_file']['error'] == 0) {
            $upload_dir = __DIR__ . '/../../public/assets/files/assignments/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $file_name = time() . '_' . basename($_FILES['assignment_file']['name']);
            $file_path = '/assets/files/assignments/' . $file_name;
            move_uploaded_file($_FILES['assignment_file']['tmp_name'], $upload_dir . $file_name);
        }
        if ($title && $due_date) {
            try {
                query("INSERT INTO assignments (course_id, title, description, due_date, max_score, weight_percentage, instructions, file_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                    [$course_id, $title, $description, $due_date, $max_score, $weight_percentage, $instructions, $file_path]);
                $message = "Assignment added successfully!";
            } catch (Exception $e) {
                $error = "Error adding assignment: " . $e->getMessage();
            }
        } else {
            $error = "Please fill in all required fields for assignment.";
        }
    }

    // Add Course Material
    if (isset($_POST['add_material'])) {
        $title = trim($_POST['material_title'] ?? '');
        $description = trim($_POST['material_description'] ?? '');
        $upload_date = date('Y-m-d H:i:s');
        $file_path = '';
        if (isset($_FILES['material_file']) && $_FILES['material_file']['error'] == 0) {
            $upload_dir = __DIR__ . '/../../public/assets/files/courses/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $file_name = time() . '_' . basename($_FILES['material_file']['name']);
            $file_path = '/assets/files/courses/' . $file_name;
            move_uploaded_file($_FILES['material_file']['tmp_name'], $upload_dir . $file_name);
        }
        if ($title && $file_path) {
            try {
                query("INSERT INTO course_materials (course_id, title, description, file_path, upload_date, uploaded_by) VALUES (?, ?, ?, ?, ?, ?)",
                    [$course_id, $title, $description, $file_path, $upload_date, $current_user['id']]);
                $message = "Material uploaded successfully!";
            } catch (Exception $e) {
                $error = "Error uploading material: " . $e->getMessage();
            }
        } else {
            $error = "Please fill in all required fields and select a file for material.";
        }
    }

    // Grade Submission
    if (isset($_POST['grade_submission'])) {
        $submission_id = (int)($_POST['submission_id'] ?? 0);
        $grade = (float)($_POST['grade'] ?? 0);
        $feedback = trim($_POST['feedback'] ?? '');
        if ($submission_id && $grade) {
            try {
                // Update assignment_submissions
                query("UPDATE assignment_submissions SET grade = ?, feedback = ?, graded_at = NOW(), graded_by = ? WHERE id = ?",
                    [$grade, $feedback, $current_user['id'], $submission_id]);

                // Get submission info for grade record
                $submission = fetchOne("SELECT * FROM assignment_submissions WHERE id = ?", [$submission_id]);
                if ($submission) {
                    $assignment_id = $submission['assignment_id'];
                    $student_id = $submission['student_id'];
                    // Get assignment info for max_score
                    $assignment = fetchOne("SELECT * FROM assignments WHERE id = ?", [$assignment_id]);
                    $max_score = $assignment ? $assignment['max_score'] : 100;

                    // Insert or update course_grades
                    $existing_grade = fetchOne("SELECT id FROM course_grades WHERE course_id = ? AND student_id = ? AND assignment_id = ? AND grade_type = 'assignment'", [$course_id, $student_id, $assignment_id]);
                    if ($existing_grade) {
                        query("UPDATE course_grades SET points_earned = ?, points_possible = ?, grade_date = NOW(), notes = ? WHERE id = ?",
                            [$grade, $max_score, $feedback, $existing_grade['id']]);
                    } else {
                        query("INSERT INTO course_grades (course_id, student_id, assignment_id, grade_type, points_earned, points_possible, grade_date, notes) VALUES (?, ?, ?, 'assignment', ?, ?, NOW(), ?)",
                            [$course_id, $student_id, $assignment_id, $grade, $max_score, $feedback]);
                    }
                }
                $message = "Submission graded successfully!";
            } catch (Exception $e) {
                $error = "Error grading submission: " . $e->getMessage();
            }
        } else {
            $error = "Please provide a grade and feedback.";
        }
    }
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


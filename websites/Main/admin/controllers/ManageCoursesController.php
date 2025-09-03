<?php
// Set page meta info
$page_title = 'Manage Courses - Admin Panel';
$current_page = 'manage_courses';

$message = '';
$error = '';

// Directories for uploads
$upload_base_dir = __DIR__ . '/../../public/assets/';
$images_dir = $upload_base_dir . 'images/';
$courses_dir = $upload_base_dir . 'files/courses/';
$assignments_dir = $upload_base_dir . 'files/assignments/';
$submissions_dir = $upload_base_dir . 'files/submissions/';

// Ensure upload directories exist
foreach ([$images_dir, $courses_dir, $assignments_dir, $submissions_dir] as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Handle POST form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['add_course'])) {
        // Gather inputs
        $course_code = strtoupper(trim($_POST['course_code']));
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $lecturer = trim($_POST['lecturer']);
        $credits = (int) ($_POST['credits'] ?? 0);
        $term = trim($_POST['term']);

        // Simple required field check
        if ($course_code && $title && $lecturer && $credits && $term) {
            // Handle file uploads
            $image_course_file = $_FILES['image_course']['name'] ?? '';
            $contents_file = $_FILES['contents']['name'] ?? '';
            $assignment_file = $_FILES['assignment']['name'] ?? '';
            $assignment_submission_file = $_FILES['assignment_submission']['name'] ?? '';

            // Move uploaded files if provided
            if (!empty($image_course_file)) {
                move_uploaded_file($_FILES['image_course']['tmp_name'], $images_dir . $image_course_file);
            }
            if (!empty($contents_file)) {
                move_uploaded_file($_FILES['contents']['tmp_name'], $courses_dir . $contents_file);
            }
            if (!empty($assignment_file)) {
                move_uploaded_file($_FILES['assignment']['tmp_name'], $assignments_dir . $assignment_file);
            }
            if (!empty($assignment_submission_file)) {
                move_uploaded_file($_FILES['assignment_submission']['tmp_name'], $submissions_dir . $assignment_submission_file);
            }

            try {
                query("INSERT INTO courses (course_code, title, description, lecturer, credits, image_course, term, contents, assignment, assignment_submission) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    [$course_code, $title, $description, $lecturer, $credits, $image_course_file, $term, $contents_file, $assignment_file, $assignment_submission_file]);
                $message = "Course added successfully!";
            } catch (Exception $e) {
                $error = strpos($e->getMessage(), 'Duplicate entry') !== false ? "Course code already exists!" : "Error adding course: " . $e->getMessage();
            }
        } else {
            $error = "Please fill in all required fields!";
        }
    }
    elseif (isset($_POST['update_course'])) {
        $id = (int) ($_POST['course_id'] ?? 0);
        if ($id > 0) {
            $existing_course = fetchOne("SELECT * FROM courses WHERE id = ?", [$id]);

            $course_code = strtoupper(trim($_POST['course_code']));
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $lecturer = trim($_POST['lecturer']);
            $credits = (int) ($_POST['credits'] ?? 0);
            $term = trim($_POST['term']);

            // Use existing file names unless new ones uploaded
            $image_course_file = $existing_course['image_course'] ?? '';
            $contents_file = $existing_course['contents'] ?? '';
            $assignment_file = $existing_course['assignment'] ?? '';
            $assignment_submission_file = $existing_course['assignment_submission'] ?? '';

            if (!empty($_FILES['image_course']['name']) && $_FILES['image_course']['error'] === 0) {
                $image_course_file = $_FILES['image_course']['name'];
                move_uploaded_file($_FILES['image_course']['tmp_name'], $images_dir . $image_course_file);
            }
            if (!empty($_FILES['contents']['name']) && $_FILES['contents']['error'] === 0) {
                $contents_file = $_FILES['contents']['name'];
                move_uploaded_file($_FILES['contents']['tmp_name'], $courses_dir . $contents_file);
            }
            if (!empty($_FILES['assignment']['name']) && $_FILES['assignment']['error'] === 0) {
                $assignment_file = $_FILES['assignment']['name'];
                move_uploaded_file($_FILES['assignment']['tmp_name'], $assignments_dir . $assignment_file);
            }
            if (!empty($_FILES['assignment_submission']['name']) && $_FILES['assignment_submission']['error'] === 0) {
                $assignment_submission_file = $_FILES['assignment_submission']['name'];
                move_uploaded_file($_FILES['assignment_submission']['tmp_name'], $submissions_dir . $assignment_submission_file);
            }

            try {
                query("UPDATE courses SET course_code=?, title=?, description=?, lecturer=?, credits=?, image_course=?, term=?, contents=?, assignment=?, assignment_submission=? WHERE id=?",
                    [$course_code, $title, $description, $lecturer, $credits, $image_course_file, $term, $contents_file, $assignment_file, $assignment_submission_file, $id]);
                $message = "Course updated successfully!";
            } catch (Exception $e) {
                $error = strpos($e->getMessage(), 'Duplicate entry') !== false ? "Course code already exists!" : "Error updating course: " . $e->getMessage();
            }
        }
    }
    elseif (isset($_POST['delete_course'])) {
        $id = (int) ($_POST['course_id'] ?? 0);
        if ($id > 0) {
            try {
                query("DELETE FROM courses WHERE id=?", [$id]);
                $message = "Course deleted successfully!";
            } catch (Exception $e) {
                $error = "Error deleting course: " . $e->getMessage();
            }
        }
    }
}

// Fetch courses and edit_course for view
$courses = fetchAll("SELECT * FROM courses ORDER BY course_code");
$edit_course = isset($_GET['edit']) && is_numeric($_GET['edit']) ? fetchOne("SELECT * FROM courses WHERE id = ?", [(int)$_GET['edit']]) : null;

// Capture the view output
ob_start();
require __DIR__ . '/../views/manage_courses.html.php';
$content = ob_get_clean();

// Render admin layout with $content
require __DIR__ . '/../../templates/admin_layout.php';

<?php
// Set page meta info
$page_title = 'Manage Users - Admin Dashboard';
$current_page = 'manage_users';

$message = '';
$error = '';
$active_tab = $_GET['tab'] ?? 'add-user';
$selected_user_type = $_POST['user_type'] ?? 'student'; // default selected user type

// Fetch logged-in user data
if (isset($_SESSION['username'])) {
    $current_user = fetchOne("SELECT * FROM users WHERE username = ?", [$_SESSION['username']]);
} else {
    $current_user = null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Common POST inputs
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $user_type = $_POST['user_type'] ?? '';
    $selected_courses = $_POST['courses'] ?? [];

    try {
        if (isset($_POST['preview_user_type'])) {
            $selected_user_type = $user_type ?: 'student';

        } elseif (isset($_POST['add_user'])) {
            if ($username && $password && $email && $full_name && $user_type) {
                $existing_user = fetchOne("SELECT id FROM users WHERE username = ? OR email = ?", [$username, $email]);
                if ($existing_user) {
                    $error = "Username or email already exists!";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    query("INSERT INTO users (username, password, email, full_name) VALUES (?, ?, ?, ?)",
                          [$username, $hashed_password, $email, $full_name]);
                    $new_user_id = fetchOne("SELECT LAST_INSERT_ID() AS id")['id'] ?? 0;
                    // Assign role in user_roles
                    $role_row = fetchOne("SELECT id FROM roles WHERE name = ?", [$user_type]);
                    if ($role_row && $new_user_id) {
                        query("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)", [$new_user_id, $role_row['id']]);
                    }
                    if ($user_type === 'lecturer' && $selected_courses) {
                        foreach ($selected_courses as $course_id) {
                            query("INSERT INTO lecturer_courses (lecturer_id, course_id, assigned_by) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE is_active = 1",
                                  [$new_user_id, $course_id, $_SESSION['user_id']]);
                            query("UPDATE courses SET lecturer_id = ? WHERE id = ?", [$new_user_id, $course_id]);
                        }
                    } elseif ($user_type === 'student' && $selected_courses) {
                        foreach ($selected_courses as $course_id) {
                            query("INSERT INTO student_enrollments (student_id, course_id, enrolled_by) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE status = 'active'",
                                  [$new_user_id, $course_id, $_SESSION['user_id']]);
                        }
                    }
                    // Send email to user with username and password
                    require_once __DIR__ . '/../../src/helper.php';
                    sendUserCredentialsEmail($email, $username, $password);
                    $message = ucfirst($user_type) . " added successfully!";
                    if ($selected_courses) {
                        $message .= ($user_type === 'lecturer' ? " Assigned to " : " Enrolled in ") . count($selected_courses) . " course(s).";
                    }
                }
            } else {
                $error = "Please fill in all required fields.";
            }

        } elseif (isset($_POST['delete_user'])) {
            $user_id = (int)($_POST['user_id'] ?? 0);
            $user_to_delete = fetchOne("SELECT * FROM users WHERE id = ?", [$user_id]);

            // Check if the target user has super_admin role
            $is_target_super_admin = fetchOne("
                SELECT 1 FROM user_roles ur 
                JOIN roles r ON ur.role_id = r.id 
                WHERE ur.user_id = ? AND r.super_admin = 1
            ", [$user_id]);

            // Check if current user has super_admin role
            $is_current_super_admin = fetchOne("
                SELECT 1 FROM user_roles ur 
                JOIN roles r ON ur.role_id = r.id 
                WHERE ur.user_id = ? AND r.super_admin = 1
            ", [$_SESSION['user_id']]);

            if ($user_id === $_SESSION['user_id']) {
                $error = "You cannot delete your own account!";
            } elseif ($is_target_super_admin && !$is_current_super_admin) {
                $error = "Only super admins can delete other super admins!";
            } elseif ($is_target_super_admin) {
                $error = "Super admin accounts cannot be deleted for security reasons!";
            } else {
                // Additional safety check: prevent deletion of user ID 1 (original admin)
                if ($user_id === 1) {
                    $error = "The primary admin account cannot be deleted!";
                } else {
                    // Soft delete: set is_deleted flag
                    query("UPDATE users SET is_deleted = 1 WHERE id = ?", [$user_id]);
                    $message = "User deleted successfully!";
                }
            }
            
        } elseif (isset($_POST['assign_course'])) {
            $lecturer_id = (int)($_POST['lecturer_id'] ?? 0);
            $course_id = (int)($_POST['course_id'] ?? 0);

            query("INSERT INTO lecturer_courses (lecturer_id, course_id, assigned_by) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE is_active = 1",
                  [$lecturer_id, $course_id, $_SESSION['user_id']]);
            query("UPDATE courses SET lecturer_id = ? WHERE id = ?", [$lecturer_id, $course_id]);
            $message = "Course assigned successfully!";

        } elseif (isset($_POST['remove_assignment'])) {
            $assignment_id = (int)($_POST['assignment_id'] ?? 0);
            $assignment = fetchOne("SELECT course_id FROM lecturer_courses WHERE id = ?", [$assignment_id]);

            query("DELETE FROM lecturer_courses WHERE id = ?", [$assignment_id]);
            query("UPDATE courses SET lecturer_id = NULL WHERE id = ?", [$assignment['course_id'] ?? 0]);
            $message = "Course assignment removed successfully!";
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch data for view
$users = fetchAll("
    SELECT u.*, 
           GROUP_CONCAT(r.name) as roles,
           MAX(r.super_admin) as is_super_admin
    FROM users u 
    LEFT JOIN user_roles ur ON u.id = ur.user_id 
    LEFT JOIN roles r ON ur.role_id = r.id 
    WHERE u.is_deleted IS NULL OR u.is_deleted = 0
    GROUP BY u.id 
    ORDER BY u.full_name ASC
");

$courses = fetchAll("SELECT * FROM courses ORDER BY title ASC");
$lecturers = fetchAll("SELECT u.* FROM users u JOIN user_roles ur ON u.id = ur.user_id JOIN roles r ON ur.role_id = r.id WHERE r.name = 'lecturer' ORDER BY u.full_name ASC");

// Check if current user is super admin
$current_user_is_super_admin = fetchOne("
    SELECT 1 FROM user_roles ur 
    JOIN roles r ON ur.role_id = r.id 
    WHERE ur.user_id = ? AND r.super_admin = 1
", [$_SESSION['user_id']]);

try {
    $lecturer_assignments = fetchAll("
        SELECT lc.*, u.full_name as lecturer_name, c.title as course_title, c.course_code
        FROM lecturer_courses lc
        JOIN users u ON lc.lecturer_id = u.id
        JOIN courses c ON lc.course_id = c.id
        WHERE lc.is_active = 1
        ORDER BY u.full_name ASC, c.title ASC
    ");
} catch (Exception $e) {
    $lecturer_assignments = [];
    $error = "Error loading course assignments: " . $e->getMessage();
}

// Render view
ob_start();
require __DIR__ . '/../views/manage_users.html.php';
$content = ob_get_clean();

// Render layout
require __DIR__ . '/../../templates/admin_layout.php';
?>

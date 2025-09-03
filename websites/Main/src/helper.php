<?php
// helper.php

require_once __DIR__ . '/sendUserCredentialsEmail.php';
/**
 * Escape output for HTML to prevent XSS
 */
function escape(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is an admin based on user array
 */
function isAdmin(?array $user): bool {
    if (empty($user)) return false;
    $role = fetchOne("SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = ? AND r.name = 'admin'", [$user['id']]);
    return !empty($role);
}

/**
 * Check if user is a lecturer based on user array
 */
function isLecturer(?array $user = null): bool {
    if (empty($user)) return false;
    $role = fetchOne("SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = ? AND r.name = 'lecturer'", [$user['id']]);
    return !empty($role);
}

/**
 * Redirect to a given URL and exit
 */
function redirect(string $url): void {
    header("Location: $url");
    exit;
}

/**
 * Return main navigation items for regular users
 */
function getMainNavItems(): array {
    return [
        'home' => 'Home',
        'profile' => 'My Profile',
        'courses' => 'Courses',
        'events' => 'Events'
    ];
}

/**
 * Return admin navigation items
 * NOTE: Use keys matching your router page parameters exactly.
 */
function getAdminNavItems(): array {
    return [
        'admin_dashboard' => 'Dashboard',
        'manage_users' => 'Manage Users',
        'manage_courses' => 'Manage Courses',
        'manage_events' => 'Manage Events',
        'manage_announcements' => 'Manage Announcements',
    ];
}

/**
 * Require user login, redirect to login page if not logged in
 */
function requireLogin(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if we are already on the login page to prevent redirect loops
    $current_page = $_GET['page'] ?? 'home';
    if ($current_page === 'login') {
        return;
    }
    
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        redirect('index.php?page=login');
    }
}

/**
 * Get current logged-in user from session
 */
function getCurrentUser(): ?array {
    if (!isset($_SESSION['username'])) {
        return null;
    }
    return fetchOne("SELECT * FROM users WHERE username = ?", [$_SESSION['username']]);
}

/**
 * Get user status string based on user roles
 */
function getUserStatus(?array $user): string {
    if (!$user) return 'Guest';
    $roles = fetchAll("SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = ?", [$user['id']]);
    $roleNames = array_map(function($r) { return $r['name']; }, $roles);
    if (in_array('admin', $roleNames) || in_array('super_admin', $roleNames)) return 'Admin';
    if (in_array('lecturer', $roleNames)) return 'Lecturer';
    if (in_array('student', $roleNames)) return 'Student';
    return 'User';
}

/**
 * Get courses accessible by the user (admin sees all, lecturer their courses, student enrolled)
 */
function getAccessibleCourses(?array $user): array {
    if (!$user) return [];
    $roles = fetchAll("SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = ?", [$user['id']]);
    $roleNames = array_map(function($r) { return $r['name']; }, $roles);
    if (in_array('admin', $roleNames) || in_array('super_admin', $roleNames)) {
        return fetchAll("SELECT * FROM courses ORDER BY course_code");
    }
    if (in_array('lecturer', $roleNames)) {
        return fetchAll(
            "SELECT c.* FROM courses c
             JOIN lecturer_courses lc ON lc.course_id = c.id
             WHERE lc.lecturer_id = ? AND lc.is_active = 1
             ORDER BY c.course_code",
            [$user['id']]
        );
    }
    if (in_array('student', $roleNames)) {
        return fetchAll(
            "SELECT c.* FROM courses c
             JOIN student_enrollments se ON se.course_id = c.id
             WHERE se.student_id = ? AND se.status = 'active'
             ORDER BY c.course_code",
            [$user['id']]
        );
    }
    return [];
}

/**
 * Check if user can access a specific course
 */
function canAccessCourse(int $course_id, ?array $user): bool {
    if (!$user) return false;
    $roles = fetchAll("SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = ?", [$user['id']]);
    $roleNames = array_map(function($r) { return $r['name']; }, $roles);
    if (in_array('admin', $roleNames) || in_array('super_admin', $roleNames)) {
        return true;
    }
    if (in_array('lecturer', $roleNames)) {
        $found = fetchOne(
            "SELECT 1 FROM lecturer_courses WHERE lecturer_id = ? AND course_id = ? AND is_active = 1",
            [$user['id'], $course_id]
        );
        return (bool)$found;
    }
    if (in_array('student', $roleNames)) {
        $found = fetchOne(
            "SELECT 1 FROM student_enrollments WHERE student_id = ? AND course_id = ? AND status = 'active'",
            [$user['id'], $course_id]
        );
        return (bool)$found;
    }
    return false;
}

/**
 * Get initials for display from user full name or username
 */
function getUserInitials(?array $user): string {
    if (!$user) {
        return '';
    }

    if (!empty($user['full_name'])) {
        $names = explode(' ', trim($user['full_name']));
        $initials = strtoupper(substr($names[0], 0, 1));
        if (count($names) > 1) {
            $initials .= strtoupper(substr($names[count($names) - 1], 0, 1));
        }
        return $initials;
    }

    if (!empty($user['username'])) {
        return strtoupper(substr($user['username'], 0, 2));
    }

    return '';
}

/**
 * Get display name for user (full name or username fallback)
 */
function getUserDisplayName(?array $user): string {
    if (!$user) {
        return 'Guest';
    }

    if (!empty($user['full_name'])) {
        return $user['full_name'];
    }

    if (!empty($user['username'])) {
        return $user['username'];
    }

    return 'User';
}

/**
 * Check if user can manage a course (admin or assigned lecturer)
 */
function canManageCourse(int $course_id, ?array $user): bool {
    if (!$user) return false;
    $roles = fetchAll("SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = ?", [$user['id']]);
    $roleNames = array_map(function($r) { return $r['name']; }, $roles);
    if (in_array('admin', $roleNames) || in_array('super_admin', $roleNames)) {
        return true;
    }
    if (in_array('lecturer', $roleNames)) {
        $found = fetchOne(
            "SELECT 1 FROM lecturer_courses WHERE lecturer_id = ? AND course_id = ? AND is_active = 1",
            [$user['id'], $course_id]
        );
        return (bool)$found;
    }
    return false;
}


/**
 * Load user display name and initials together (useful for layout)
 * Returns [displayName, initials]
 */
function loadUserDisplayInfo(): array {
    $user = getCurrentUser();

    $user_name = $user['full_name'] ?? $_SESSION['username'] ?? 'Guest';
    $initials = '';

    if ($user && !empty($user['full_name'])) {
        $names = explode(' ', trim($user['full_name']));
        $initials = strtoupper(substr($names[0], 0, 1));
        if (count($names) > 1) {
            $initials .= strtoupper(substr($names[count($names) - 1], 0, 1));
        }
    } elseif (isset($_SESSION['username'])) {
        $initials = strtoupper(substr($_SESSION['username'], 0, 2));
    }

    return [$user_name, $initials];
}

/**
 * Get user role string for authorization or display purposes
 */
function getUserRole(?array $user): string {
    if (!$user) return 'guest';
    $roles = fetchAll("SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = ?", [$user['id']]);
    $roleNames = array_map(function($r) { return $r['name']; }, $roles);
    if (in_array('admin', $roleNames) || in_array('super_admin', $roleNames)) return 'admins';
    if (in_array('lecturer', $roleNames)) return 'lecturers';
    if (in_array('student', $roleNames)) return 'students';
    return 'all';
}

// Returns the role name for a given user_id (e.g., 'admin', 'lecturer', 'student')
function get_user_role($user_id) {
    $row = fetchOne("SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = ? LIMIT 1", [$user_id]);
    $role = $row ? $row['name'] : null;
    // Convert super_admin to admin for simplicity
    if ($role === 'super_admin') {
        $role = 'admin';
    }
    return $role;
}
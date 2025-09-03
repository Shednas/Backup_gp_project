<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helper.php';

// Check if user is logged in (implement requireLogin in helper.php)
requireLogin();

// Get logged-in user's information
$current_user = getCurrentUser();
list($user_name, $user_initials) = loadUserDisplayInfo();

// Determine user role
$is_lecturer = isLecturer($current_user);
$is_admin = isAdmin($current_user);

// Get current filter and search parameters
$current_term = $_GET['term'] ?? 'all';
$current_filter = $_GET['filter'] ?? 'all';
$search_query = $_GET['search'] ?? '';

// Check for access denied error
$error_message = '';
if (isset($_GET['error']) && $_GET['error'] === 'access_denied') {
    $error_message = 'You do not have access to that course. You can only access courses you are enrolled in or assigned to.';
}

// Get courses based on user access
$courses = getAccessibleCourses($current_user);

// Convert database courses to display format
$display_courses = [];
foreach ($courses as $course) {
    $display_courses[] = [
        'id' => $course['id'],
        'code' => $course['course_code'],
        'title' => $course['title'],
        'credits' => $course['credits'],
        'term' => $course['term'] ?: 'Not set',
        'image_course' => $course['image_course'],
        'lecturer' => $course['lecturer'],
        'status' => $course['status'] ?? 'active' // Default to 'active' if not set
    ];
}

// Filter courses based on search and filters

$filtered_courses = array_filter($display_courses, function($course) use ($current_term, $current_filter, $search_query, $is_lecturer) {
    $term_match = $current_term === 'all' || $course['term'] === $current_term;
    $search_match = empty($search_query) ||
        stripos($course['title'], $search_query) !== false ||
        stripos($course['code'], $search_query) !== false;

    $status = $course['status'] ?? 'active';
    $status_match = true;

    if ($current_filter !== 'all') {
        if ($is_lecturer) {
            // Lecturer filters: active, archived
            $status_match = ($current_filter === $status);
        } else {
            // Student filters: open, enrolled, completed
            if ($current_filter === 'open') {
                $status_match = ($status === 'active');
            } elseif ($current_filter === 'enrolled') {
                // Assume enrolled if course has a property 'enrolled' true, otherwise fallback to true for demo
                $status_match = isset($course['enrolled']) ? $course['enrolled'] : true;
            } elseif ($current_filter === 'completed') {
                $status_match = ($status === 'completed');
            } else {
                $status_match = true;
            }
        }
    }

    return $term_match && $search_match && $status_match;
});

// Set user status based on role (implement getUserStatus in helper.php if needed)
$user_status = getUserStatus($current_user);

// Capture content
ob_start();
require __DIR__ . '/../views/courses.html.php';
$content = ob_get_clean();

// Load layout
require __DIR__ . '/../../templates/main_layout.php';

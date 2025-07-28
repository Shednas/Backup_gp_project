<?php
// index.php (entry point)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include core dependencies
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helper.php';

// Logout handler
if (isset($_GET['page']) && $_GET['page'] === 'logout') {
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}

// If no ?page= provided and user is logged in as admin, redirect to dashboard
if (!isset($_GET['page']) && !empty($_SESSION['logged_in']) && !empty($_SESSION['is_admin'])) {
    header('Location: index.php?page=admin_dashboard');
    exit;
}

// Determine the page to load (default: home)
$page = $_GET['page'] ?? 'home';

// Route logic
switch ($page) {
    // Public routes
    case 'home':
        require __DIR__ . '/../src/controllers/HomeController.php';
        break;
    case 'login':
        require __DIR__ . '/../src/controllers/AuthController.php';
        break;
    case 'profile':
        require __DIR__ . '/../src/controllers/ProfileController.php';
        break;
    case 'courses':
        require __DIR__ . '/../src/controllers/CourseController.php';
        break;
    case 'events':
        require __DIR__ . '/../src/controllers/EventsController.php';
        break;
    case 'course_dashboard':
        require __DIR__ . '/../src/controllers/CourseDashboardController.php';
        break;
    case 'lecturer_course_dashboard':
        require __DIR__ . '/../lecturer/controllers/LecturerCourseController.php';
        break;

    // Admin routes
    case 'admin_dashboard':
        require __DIR__ . '/../admin/controllers/DashboardController.php';
        break;
    case 'manage_users':
        require __DIR__ . '/../admin/controllers/ManageUsersController.php';
        break;
    case 'manage_courses':
        require __DIR__ . '/../admin/controllers/ManageCoursesController.php';
        break;
    case 'manage_announcements':
        require __DIR__ . '/../admin/controllers/ManageAnnouncementsController.php';
        break;
    case 'manage_events':
        require __DIR__ . '/../admin/controllers/ManageEventsController.php';
        break;

    // Default fallback
    default:
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
        break;
}
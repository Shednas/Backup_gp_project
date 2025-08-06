<?php
/**
 * Main Application Entry Point
 * 
 * This file handles routing for the application based on the user's
 * authentication status and role.
 */

// Initialize session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include core dependencies
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helper.php';

// Handle logout request
if (isset($_GET['page']) && $_GET['page'] === 'logout') {
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}

// Get requested page, default to empty string
$page = $_GET['page'] ?? '';

// =====================================================================
// AUTHENTICATED USER ROUTING
// =====================================================================
if (!empty($_SESSION['logged_in']) && !empty($_SESSION['username'])) {
    // Recover role if missing but user_id exists
    if (empty($_SESSION['role']) && !empty($_SESSION['user_id'])) {
        $_SESSION['role'] = get_user_role($_SESSION['user_id']);
    }

    // Default routing when no page specified
    if ($page === '') {
        if (!empty($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'super_admin')) {
            header('Location: index.php?page=admin_dashboard');
        } else {
            header('Location: index.php?page=home');
        }
        exit;
    }

    // Prevent access to login page when already authenticated
    if ($page === 'login') {
        if (!empty($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'super_admin')) {
            header('Location: index.php?page=admin_dashboard');
        } else {
            header('Location: index.php?page=home');
        }
        exit;
    }

    // Route to appropriate controller based on requested page
    switch ($page) {
        // Admin pages - require admin role
        case 'admin_dashboard':
        case 'manage_users':
        case 'manage_courses':
        case 'manage_announcements':
        case 'manage_events':
            if (!empty($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'super_admin')) {
                // For admin_dashboard, load directly
                if ($page === 'admin_dashboard') {
                    require __DIR__ . '/../admin/controllers/DashboardController.php';
                } else {
                    // For manage_* pages, load the corresponding controller
                    require __DIR__ . '/../admin/controllers/Manage' . ucfirst(substr($page, 7)) . 'Controller.php';
                }
            } else {
                // Unauthorized access attempt to admin pages
                header('Location: index.php?page=home');
                exit;
            }
            break;
        
        // Lecturer pages
        case 'lecturer_course_dashboard':
            if (!empty($_SESSION['role']) && $_SESSION['role'] === 'lecturer') {
                require __DIR__ . '/../lecturer/controllers/LecturerCourseController.php';
            } else {
                header('Location: index.php?page=home');
                exit;
            }
            break;
            
        // Common pages for all authenticated users
        case 'home':
            require __DIR__ . '/../src/controllers/HomeController.php';
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
            
        // Page not found
        default:
            http_response_code(404);
            echo "<h1>404 - Page Not Found</h1>";
            break;
    }
    exit;
}

// =====================================================================
// NON-AUTHENTICATED USER ROUTING
// =====================================================================
if ($page === 'login') {
    require __DIR__ . '/../src/controllers/AuthController.php';
} else {
    // All non-authenticated requests except login go to portal
    require __DIR__ . '/../src/views/portal.html.php';
}

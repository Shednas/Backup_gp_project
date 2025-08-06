<?php
// DashboardController.php


// Set page meta info
$page_title = 'Admin Dashboard - Technify University Portal';
$page_class = 'admin-dashboard';
$current_page = 'dashboard';

// Fetch counts for dashboard stats
$total_users = fetchOne("SELECT COUNT(*) as count FROM users")['count'] ?? 0;
$total_students = fetchOne("SELECT COUNT(*) as count FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE r.name = 'student'")['count'] ?? 0;
$total_lecturers = fetchOne("SELECT COUNT(*) as count FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE r.name = 'lecturer'")['count'] ?? 0;
$total_courses = fetchOne("SELECT COUNT(*) as count FROM courses")['count'] ?? 0;
$total_assignments = fetchOne("SELECT COUNT(*) as count FROM assignments")['count'] ?? 0;

// Calendar variables for current month view
$month = date('F');
$year = date('Y');
$today = date('j');
$current_month = date('n');
$current_year = date('Y');
$first_day_of_month = mktime(0, 0, 0, $current_month, 1, $current_year);
$days_in_month = date('t', $first_day_of_month);
$first_day_weekday = date('w', $first_day_of_month); // 0=Sun ... 6=Sat

// Capture dashboard view output
ob_start();
require __DIR__ . '/../views/dashboard.html.php';
$content = ob_get_clean();

// Render admin layout (which outputs the page)
require __DIR__ . '/../../templates/admin_layout.php';

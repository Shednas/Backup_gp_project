<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helper.php';

requireLogin();

$current_user = getCurrentUser();
// Load current user info for layout display
$user_name = $current_user['full_name'] ?? $_SESSION['username'];

$user_initials = '';
if ($current_user && $current_user['full_name']) {
    $names = explode(' ', trim($current_user['full_name']));
    $user_initials = strtoupper(substr($names[0], 0, 1));
    if (count($names) > 1) {
        $user_initials .= strtoupper(substr($names[count($names)-1], 0, 1));
    }
} else {
    $user_initials = strtoupper(substr($_SESSION['username'], 0, 2));
}


// Handle registration/unregistration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['register_event']) || isset($_POST['unregister_event']))) {
    $event_id = (int)$_POST['event_id'];

    if (!isset($_SESSION['registered_events'])) {
        $_SESSION['registered_events'] = [];
    }

    if (isset($_POST['register_event'])) {
        $_SESSION['registered_events'][] = $event_id;
    } else {
        $_SESSION['registered_events'] = array_diff($_SESSION['registered_events'], [$event_id]);
    }
}

// Get current tab and filter from URL parameters
$current_tab = $_GET['tab'] ?? 'upcoming';
$current_filter = $_GET['filter'] ?? 'all';

// Get events from database
$today = date('Y-m-d');
$current_time = date('H:i:s');

$base_query = "SELECT * FROM events WHERE status = 'active'";
$params = [];

if ($current_tab === 'upcoming') {
    $base_query .= " AND (event_date > ? OR (event_date = ? AND event_time > ?))";
    $params = [$today, $today, $current_time];
} else {
    $base_query .= " AND (event_date < ? OR (event_date = ? AND event_time < ?))";
    $params = [$today, $today, $current_time];
}

if ($current_filter !== 'all') {
    $base_query .= " AND category = ?";
    $params[] = $current_filter;
}

$base_query .= " ORDER BY event_date ASC, event_time ASC";

try {
    $events = fetchAll($base_query, $params);
} catch (Exception $e) {
    $events = [];
}

$page_title = 'Events - Technify University Portal';
$current_page = 'events';

// Capture content
ob_start();
require __DIR__ . '/../views/events.html.php';
$content = ob_get_clean();

// Load layout
require __DIR__ . '/../../templates/main_layout.php';
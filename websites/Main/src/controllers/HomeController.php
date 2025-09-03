<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helper.php';

requireLogin();

$current_user = getCurrentUser();
list($user_name, $user_initials) = loadUserDisplayInfo();
$user_role = getUserRole($current_user);

// Dates for event filtering
$today = date('Y-m-d');
$now = date('H:i:s');

// Fetch Notices
try {
    $notices = fetchAll(
        "SELECT * FROM notices 
         WHERE is_active = 1 
         AND (target_audience = 'all' OR target_audience = ?) 
         AND (expires_at IS NULL OR expires_at > NOW())
         ORDER BY is_important DESC, created_at DESC 
         LIMIT 5",
        [$user_role]
    );
} catch (Exception $e) {
    $notices = [];
}

// Fetch News
try {
    $news_items = fetchAll(
        "SELECT * FROM news 
         WHERE is_published = 1 
         AND (target_audience = 'all' OR target_audience = ?) 
         AND publish_date <= NOW()
         ORDER BY is_featured DESC, publish_date DESC 
         LIMIT 5",
        [$user_role]
    );
} catch (Exception $e) {
    $news_items = [];
}

// Fetch Events
try {
    $sidebar_events = fetchAll(
        "SELECT title, event_date FROM events 
         WHERE status = 'active' 
         AND (event_date > ? OR (event_date = ? AND event_time > ?)) 
         ORDER BY event_date ASC, event_time ASC 
         LIMIT 5",
        [$today, $today, $now]
    );
} catch (Exception $e) {
    $sidebar_events = [];
}

// Page metadata
$page_title = 'Home | Technify University';
$page_class = 'home';
$current_page = 'home';

// Capture content
ob_start();
require __DIR__ . '/../views/home.html.php';
$content = ob_get_clean();

// Load layout
require __DIR__ . '/../../templates/main_layout.php';

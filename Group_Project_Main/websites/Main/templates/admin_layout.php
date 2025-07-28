<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helper.php';

// Access control: redirect if not logged in or not admin
if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../index.php?page=login');
    exit;
}
if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../index.php');
    exit;
}

// Fetch logged-in user data
$current_user = fetchOne("SELECT * FROM users WHERE username = ?", [$_SESSION['username']]);
$user_name = $current_user['full_name'] ?? $_SESSION['username'];

// Generate user initials for profile circle
if (!empty($current_user['full_name'])) {
    $names = explode(' ', trim($current_user['full_name']));
    $user_initials = strtoupper(substr($names[0], 0, 1));
    if (count($names) > 1) {
        $user_initials .= strtoupper(substr($names[count($names)-1], 0, 1));
    }
} else {
    $user_initials = strtoupper(substr($_SESSION['username'], 0, 2));
}

// Default page variables (can be overwritten by controller)
$page_title = $page_title ?? 'Admin - Technify University Portal';
$page_class = $page_class ?? 'admin-page';
$current_page = $current_page ?? 'admin_dashboard';
$header_actions = $header_actions ?? '';
$additional_css = $additional_css ?? '';
$additional_js = $additional_js ?? '';
$content = $content ?? '';

// Fetch navigation items helper function (assumed in helper.php)
$nav_items = getAdminNavItems();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($page_title) ?></title>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <?= $additional_css ?>
</head>
<body class="<?= htmlspecialchars($page_class) ?>">
    <header class="header">
        <div class="logo">Technify University</div>
        <nav class="header-nav">
            <input type="search" class="search-box" placeholder="Search..." />
            <span style="color: white; margin-left: 15px;">Admin Panel</span>
            <?= $header_actions ?>
        </nav>
    </header>

    <div class="container">
        <aside class="sidebar">
            <div class="profile">
                <div class="profile-pic"><?= htmlspecialchars($user_initials) ?></div>
                <h3><?= htmlspecialchars($user_name) ?></h3>
                <div class="status">Administrator</div>
            </div>
            <nav role="navigation">
                <ul class="nav-menu">
                    <?php foreach ($nav_items as $page => $label): 
                        $is_active = $current_page === $page;
                        $href = "index.php?page=" . urlencode($page);
                    ?>
                        <li>
                            <a href="<?= $href ?>" class="<?= $is_active ? 'active' : '' ?>" <?= $is_active ? 'aria-current="page"' : '' ?>>
                                <?= htmlspecialchars($label) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <li><a href="../index.php?page=logout">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <?= $content ?>
        </main>
    </div>

    <?= $additional_js ?>
</body>
<script>
    window.dashboardStats = {
        totalUsers: <?= (int) $total_users ?>,
        totalStudents: <?= (int) $total_students ?>,
        totalLecturers: <?= (int) $total_lecturers ?>,
        totalCourses: <?= (int) $total_courses ?>,
        totalAssignments: <?= (int) $total_assignments ?>
    };
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/assets/js/main.js"></script>
</html>

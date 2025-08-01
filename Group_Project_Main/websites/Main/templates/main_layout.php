<?php
require_once __DIR__ . '/../src/helper.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($page_title ?? 'Technify University Portal'); ?></title>
    <link rel="stylesheet" href="assets/css/user.css">
</head>
<body class="<?php echo htmlspecialchars($page_class ?? 'main-page'); ?>">

    <header class="header">
        <div class="logo"><img src="../assets/images/tu.png" alt="Technify University Logo">Technify University</div>
        <nav class="header-nav">
            <?php if (isset($header_actions)) echo $header_actions; ?>
        </nav>
    </header>

    <div class="container">
        <aside class="sidebar">
            <div class="profile">
                <div class="profile-pic"><?php echo htmlspecialchars($user_initials ?? ''); ?></div>
                <h3><?php echo htmlspecialchars($user_name ?? 'Guest'); ?></h3>
                <div class="status">Active</div>
            </div>
            <nav role="navigation">
                <ul class="nav-menu">
                    <?php
                    $nav_items = getMainNavItems();
                    foreach ($nav_items as $page => $label):
                        $is_active = ($current_page ?? '') === $page;
                    ?>
                        <li>
                            <a href="index.php?page=<?php echo urlencode($page); ?>"
                               class="<?php echo $is_active ? 'active' : ''; ?>"
                               <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
                                <?php echo htmlspecialchars($label); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <li><a href="index.php?page=logout">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <?php echo $content ?? ''; ?>
        </main>
    </div>

    <?php if (isset($additional_js)) echo $additional_js; ?>
</body>
</html>

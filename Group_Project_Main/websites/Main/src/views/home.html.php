<div class="content-left">
    <section class="notice">
        <h2>Notice</h2>
        <?php
        // Get active notices from database
        try {
            $user_role = 'all'; // Default to all
            if ($current_user) {
                if ($current_user['check_admin']) $user_role = 'admins';
                elseif ($current_user['check_lecturer']) $user_role = 'lecturers';
                elseif ($current_user['check_student']) $user_role = 'students';
            }
            
            $notices = fetchAll(
                "SELECT * FROM notices 
                 WHERE is_active = 1 
                 AND (target_audience = 'all' OR target_audience = ?)
                 AND (expires_at IS NULL OR expires_at > NOW())
                 ORDER BY is_important DESC, created_at DESC 
                 LIMIT 5", 
                [$user_role]
            );
            
            if (!empty($notices)) {
                foreach ($notices as $notice) {
                    echo '<div class="notice-item' . ($notice['is_important'] ? ' important' : '') . '">';
                    echo '<h4>' . htmlspecialchars($notice['title']) . '</h4>';
                    echo '<p>' . nl2br(htmlspecialchars($notice['content'])) . '</p>';
                    echo '<div class="notice-date">' . date('M j, Y', strtotime($notice['created_at'])) . '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No notices available at this time.</p>';
            }
        } catch (Exception $e) {
            echo '<p>Unable to load notices.</p>';
        }
        ?>
    </section>

    <section class="news">
        <h2>News</h2>
        <?php
        // Get published news from database
        try {
            $user_role = 'all'; // Use same role as notices
            
            $news_items = fetchAll(
                "SELECT * FROM news 
                 WHERE is_published = 1 
                 AND (target_audience = 'all' OR target_audience = ?)
                 AND publish_date <= NOW()
                 ORDER BY is_featured DESC, publish_date DESC 
                 LIMIT 5", 
                [$user_role]
            );
            
            if (!empty($news_items)) {
                foreach ($news_items as $news) {
                    echo '<div class="news-item' . ($news['is_featured'] ? ' featured' : '') . '">';
                    if ($news['image_path']) {
                        echo '<div class="news-image"><img src="' . htmlspecialchars($news['image_path']) . '" alt="' . htmlspecialchars($news['title']) . '"></div>';
                    }
                    echo '<h4>' . htmlspecialchars($news['title']) . '</h4>';
                    if ($news['summary']) {
                        echo '<p class="news-summary">' . htmlspecialchars($news['summary']) . '</p>';
                    } else {
                        echo '<p>' . substr(strip_tags($news['content']), 0, 150) . '...</p>';
                    }
                    echo '<div class="news-date">' . date('M j, Y', strtotime($news['publish_date'])) . '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No news available at this time.</p>';
            }
        } catch (Exception $e) {
            echo '<p>Unable to load news.</p>';
        }
        ?>
    </section>
</div>

<div class="sidebar-right">
    <section class="events">
        <h3>Events</h3>
        <div class="event-tabs">
            <button class="event-tab active">Upcoming</button>
        </div>
        <div class="events-list-sidebar">
            <?php
            // Get upcoming events from database
            try {
                $today = date('Y-m-d');
                $current_time = date('H:i:s');
                $sidebar_events = fetchAll(
                    "SELECT title, event_date FROM events 
                     WHERE status = 'active' 
                     AND (event_date > ? OR (event_date = ? AND event_time > ?))
                     ORDER BY event_date ASC, event_time ASC 
                     LIMIT 5", 
                    [$today, $today, $current_time]
                );
                
                if (!empty($sidebar_events)) {
                    foreach ($sidebar_events as $event) {
                        echo '<div class="event-item-sidebar">';
                        echo '<div class="event-title-sidebar">' . htmlspecialchars($event['title']) . '</div>';
                        echo '<div class="event-date-sidebar">' . date('M j, Y', strtotime($event['event_date'])) . '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="no-events-sidebar">No upcoming events</div>';
                }
            } catch (Exception $e) {
                echo '<div class="no-events-sidebar">Unable to load events</div>';
            }
            ?>
        </div>
    </section>
</div>

<?php
// Set page meta info
$page_title = 'Manage Events - Admin Panel';
$current_page = 'manage_events';

$message = '';
$error = '';

// Create events table if it doesn't exist (optional, but keep it if you want auto-setup)
try {
    query("CREATE TABLE IF NOT EXISTS events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        event_date DATE NOT NULL,
        event_time TIME NOT NULL,
        location VARCHAR(255),
        category ENUM('academic', 'sports', 'cultural', 'workshop', 'seminar', 'other') DEFAULT 'other',
        max_participants INT DEFAULT NULL,
        created_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        status ENUM('active', 'cancelled', 'completed') DEFAULT 'active',
        FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
    )");
} catch (Exception $e) {
    $error = "Database error: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allowed_categories = ['academic', 'sports', 'cultural', 'workshop', 'seminar', 'other'];
    $allowed_statuses = ['active', 'cancelled', 'completed'];

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $event_date = $_POST['event_date'] ?? null;
    $event_time = $_POST['event_time'] ?? null;
    $location = trim($_POST['location'] ?? '');
    $category = $_POST['category'] ?? 'other';
    $max_participants = (isset($_POST['max_participants']) && is_numeric($_POST['max_participants']) && (int)$_POST['max_participants'] > 0)
        ? (int)$_POST['max_participants']
        : 0;
    $status = $_POST['status'] ?? 'active';

    if (!in_array($category, $allowed_categories)) {
        $category = 'other';
    }
    if (!in_array($status, $allowed_statuses)) {
        $status = 'active';
    }

    if (isset($_POST['add_event'])) {
        if ($title && $event_date && $event_time) {
            try {
                query("INSERT INTO events (title, description, event_date, event_time, location, category, max_participants, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                      [$title, $description, $event_date, $event_time, $location, $category, $max_participants, $_SESSION['user_id']]);
                $message = "Event added successfully!";
            } catch (Exception $e) {
                $error = "Error adding event: " . $e->getMessage();
            }
        } else {
            $error = "Please fill in all required fields!";
        }
    }

    if (isset($_POST['update_event'])) {
        $id = (int)($_POST['event_id'] ?? 0);
        try {
            query("UPDATE events SET title=?, description=?, event_date=?, event_time=?, location=?, category=?, max_participants=?, status=? WHERE id=?",
                  [$title, $description, $event_date, $event_time, $location, $category, $max_participants, $status, $id]);
            $message = "Event updated successfully!";
        } catch (Exception $e) {
            $error = "Error updating event: " . $e->getMessage();
        }
    }

    if (isset($_POST['delete_event'])) {
        $id = (int)($_POST['event_id'] ?? 0);
        try {
            query("DELETE FROM events WHERE id=?", [$id]);
            $message = "Event deleted successfully!";
        } catch (Exception $e) {
            $error = "Error deleting event: " . $e->getMessage();
        }
    }
}

// Fetch events for listing
$events = fetchAll("SELECT e.*, u.full_name as creator_name FROM events e 
                    LEFT JOIN users u ON e.created_by = u.id 
                    ORDER BY e.event_date DESC, e.event_time DESC");

// Fetch event for editing (if any)
$edit_event = isset($_GET['edit']) && is_numeric($_GET['edit'])
    ? fetchOne("SELECT * FROM events WHERE id = ?", [(int)$_GET['edit']])
    : null;

// Capture view output and render layout
ob_start();
require __DIR__ . '/../views/manage_events.html.php';
$content = ob_get_clean();

require __DIR__ . '/../../templates/admin_layout.php';

<?php
// ManageAnnouncementsController.php

// Start session & require dependencies (if not already done in layout, you can skip here)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Access control - can also be skipped if handled in admin_layout.php but no harm keeping here
if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../index.php?page=login');
    exit;
}
$current_user = fetchOne("SELECT * FROM users WHERE username = ?", [$_SESSION['username']]);
if (!isAdmin($current_user)) {
    header('Location: ../index.php');
    exit;
}

$page_title = 'Manage Announcements - Admin Dashboard';
$current_page = 'manage_announcements';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_notice'])) {
        $title = trim($_POST['notice_title']);
        $content = trim($_POST['notice_content']);
        $is_important = isset($_POST['is_important']) ? 1 : 0;
        $target_audience = $_POST['target_audience'] ?? 'all';
        $expires_at = !empty($_POST['expires_at']) ? $_POST['expires_at'] : null;

        if ($title && $content) {
            try {
                query("INSERT INTO notices (title, content, is_important, target_audience, expires_at, created_by) VALUES (?, ?, ?, ?, ?, ?)",
                      [$title, $content, $is_important, $target_audience, $expires_at, $current_user['id']]);
                $message = "Notice added successfully!";
            } catch (Exception $e) {
                $error = "Error adding notice: " . $e->getMessage();
            }
        } else {
            $error = "Please fill in all required fields.";
        }
    } elseif (isset($_POST['add_news'])) {
        $title = trim($_POST['news_title']);
        $content = trim($_POST['news_content']);
        $summary = trim($_POST['news_summary']);
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        $target_audience = $_POST['news_target_audience'] ?? 'all';
        $publish_date = !empty($_POST['publish_date']) ? $_POST['publish_date'] : date('Y-m-d H:i:s');

        $image_path = '';
        if (isset($_FILES['news_image']) && $_FILES['news_image']['error'] == 0) {
            $upload_dir = __DIR__ . '/../../public/assets/images/news/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $file_name = time() . '_' . basename($_FILES['news_image']['name']);
            $image_path = $upload_dir . $file_name;
            if (!move_uploaded_file($_FILES['news_image']['tmp_name'], $image_path)) {
                $error = "Error uploading image.";
                $image_path = '';
            }
        }

        if ($title && $content) {
            try {
                query("INSERT INTO news (title, content, summary, image_path, is_featured, target_audience, publish_date, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                      [$title, $content, $summary, $image_path, $is_featured, $target_audience, $publish_date, $current_user['id']]);
                $message = "News article added successfully!";
            } catch (Exception $e) {
                $error = "Error adding news: " . $e->getMessage();
            }
        } else {
            $error = "Please fill in all required fields.";
        }
    } elseif (isset($_POST['delete_notice'])) {
        $notice_id = (int)$_POST['notice_id'];
        try {
            query("DELETE FROM notices WHERE id = ?", [$notice_id]);
            $message = "Notice deleted successfully!";
        } catch (Exception $e) {
            $error = "Error deleting notice: " . $e->getMessage();
        }
    } elseif (isset($_POST['delete_news'])) {
        $news_id = (int)$_POST['news_id'];
        try {
            query("DELETE FROM news WHERE id = ?", [$news_id]);
            $message = "News article deleted successfully!";
        } catch (Exception $e) {
            $error = "Error deleting news: " . $e->getMessage();
        }
    }
}

// Fetch data for view
$notices = fetchAll("SELECT n.*, u.full_name as created_by_name FROM notices n JOIN users u ON n.created_by = u.id ORDER BY n.created_at DESC");
$news_items = fetchAll("SELECT n.*, u.full_name as created_by_name FROM news n JOIN users u ON n.created_by = u.id ORDER BY n.created_at DESC");

// Capture view output
ob_start();
require __DIR__ . '/../views/manage_announcements.html.php';
$content = ob_get_clean();

// Render layout with $content inside
require __DIR__ . '/../../templates/admin_layout.php';

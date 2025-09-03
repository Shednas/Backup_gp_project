<div class="admin-container">
    <div class="admin-header">
        <h1>📢 Manage Announcements</h1>
        <p>Create and manage global notices and news that appear on the homepage</p>
    </div>

    <?php if ($message): ?>
        <div class="message success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Tabs -->
    <div class="admin-tabs">
        <button class="admin-tab active" onclick="showTab('notices')">📋 Notices</button>
        <button class="admin-tab" onclick="showTab('news')">📰 News</button>
    </div>

    <!-- Notices Tab -->
    <div id="notices-tab" class="tab-content active">
        <div class="form-container">
            <h3>Add New Notice</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="notice_title">Notice Title</label>
                    <input type="text" id="notice_title" name="notice_title" placeholder="Enter notice title..." required>
                </div>
                <div class="form-group">
                    <label for="notice_content">Notice Content</label>
                    <textarea id="notice_content" name="notice_content" placeholder="Enter notice content..." required></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="target_audience">Target Audience</label>
                        <select id="target_audience" name="target_audience">
                            <option value="all">All Users</option>
                            <option value="students">Students Only</option>
                            <option value="lecturers">Lecturers Only</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="expires_at">Expiry Date (Optional)</label>
                        <input class="form-control" type="datetime-local" id="expires_at" name="expires_at">
                    </div>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="is_important" name="is_important">
                    <label for="is_important">Mark as Important</label>
                </div>
                <button type="submit" name="add_notice" class="btn btn-primary">📋 Add Notice</button>
            </form>
        </div>

        <!-- Existing Notices -->
        <div class="form-container">
            <h3>Existing Notices</h3>
            <?php if (empty($notices)): ?>
                <p>No notices found. Create your first notice above.</p>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Audience</th>
                            <th>Priority</th>
                            <th>Created</th>
                            <th>Expires</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notices as $notice): ?>
                            <tr>
                                <td><?= htmlspecialchars($notice['title']) ?></td>
                                <td><?= ucfirst($notice['target_audience']) ?></td>
                                <td>
                                    <?php if ($notice['is_important']): ?>
                                        <span class="badge important">Important</span>
                                    <?php else: ?>
                                        <span class="badge normal">Normal</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('M j, Y', strtotime($notice['created_at'])) ?></td>
                                <td><?= $notice['expires_at'] ? date('M j, Y', strtotime($notice['expires_at'])) : 'Never' ?></td>
                                <td>
                                    <form method="POST" class="inline-form">
                                        <input type="hidden" name="notice_id" value="<?= $notice['id'] ?>">
                                        <button type="submit" name="delete_notice" class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this notice?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- News Tab -->
    <div id="news-tab" class="tab-content">
        <div class="form-container">
            <h3>Add News Article</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="news_title">News Title</label>
                    <input type="text" id="news_title" name="news_title" placeholder="Enter news title..." required>
                </div>
                <div class="form-group">
                    <label for="news_summary">Summary (Optional)</label>
                    <input type="text" id="news_summary" name="news_summary" placeholder="Brief summary for homepage display...">
                </div>
                <div class="form-group">
                    <label for="news_content">News Content</label>
                    <textarea id="news_content" name="news_content" placeholder="Enter full news content..." required></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="news_target_audience">Target Audience</label>
                        <select id="news_target_audience" name="news_target_audience">
                            <option value="all">All Users</option>
                            <option value="students">Students Only</option>
                            <option value="lecturers">Lecturers Only</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="publish_date">Publish Date</label>
                        <input type="datetime-local" id="publish_date" name="publish_date" value="<?= date('Y-m-d\TH:i') ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="news_image">Featured Image (Optional)</label>
                    <input type="file" id="news_image" name="news_image" accept="image/*">
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="is_featured" name="is_featured">
                    <label for="is_featured">Mark as Featured</label>
                </div>
                <button type="submit" name="add_news" class="btn btn-primary">📰 Add News Article</button>
            </form>
        </div>

        <!-- Existing News -->
        <div class="form-container">
            <h3>Existing News Articles</h3>
            <?php if (empty($news_items)): ?>
                <p>No news articles found. Create your first article above.</p>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Audience</th>
                            <th>Status</th>
                            <th>Published</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($news_items as $news): ?>
                            <tr>
                                <td><?= htmlspecialchars($news['title']) ?></td>
                                <td><?= ucfirst($news['target_audience']) ?></td>
                                <td>
                                    <?php if ($news['is_featured']): ?>
                                        <span class="badge featured">Featured</span>
                                    <?php else: ?>
                                        <span class="badge normal">Normal</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('M j, Y', strtotime($news['publish_date'])) ?></td>
                                <td><?= htmlspecialchars($news['created_by_name']) ?></td>
                                <td>
                                    <form method="POST" class="inline-form">
                                        <input type="hidden" name="news_id" value="<?= $news['id'] ?>">
                                        <button type="submit" name="delete_news" class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this news article?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelectorAll('.admin-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    document.getElementById(tabName + '-tab').classList.add('active');
    event.target.classList.add('active');
}
</script>
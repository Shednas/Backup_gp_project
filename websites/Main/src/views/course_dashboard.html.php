<?php
// Define base URL for routing consistency
$base_url = 'index.php?page=course_dashboard&id=' . urlencode($course_id);
?>

<a href="index.php?page=courses" class="back-link">
    ← Back to Courses
</a>

<div class="course-dashboard">
    <!-- Course Header -->
    <div class="course-header">
        <div class="course-image-header">
            <?php if (!empty($course['image_course'])): ?>
                <img src="/assets/images/<?php echo htmlspecialchars($course['image_course']); ?>" 
                     alt="<?php echo htmlspecialchars($course['title']); ?>"
                     onerror="this.style.display='none'; this.parentNode.innerHTML='📚';">
            <?php else: ?>
                📚
            <?php endif; ?>
        </div>
        <h1 class="course-title-header"><?php echo htmlspecialchars($course['title'] ?? ''); ?></h1>
        <div class="course-code-header"><?php echo htmlspecialchars($course['course_code'] ?? ''); ?></div>
        <div class="course-meta-header">
            <span>👨‍🏫 <?php echo htmlspecialchars($course['lecturer'] ?? ''); ?></span>
            <span>🏆 <?php echo htmlspecialchars($course['credits'] ?? ''); ?> Credits</span>
            <span>📅 Term <?php echo htmlspecialchars($course['term'] ?? ''); ?></span>
        </div>
    </div>

    <!-- Course Navigation -->
    <nav class="course-nav">
        <?php
        $sections = [
            'content' => '📚 Content',
            'announcement' => '📢 Announcements',
            'grade' => '📊 Grades',
            'assignment' => '📝 Assignments',
            'submission' => '📤 Submissions'
        ];
        foreach ($sections as $key => $label):
            $activeClass = ($current_section === $key) ? 'active' : '';
        ?>
            <a href="<?php echo $base_url; ?>&section=<?php echo urlencode($key); ?>" 
               class="course-nav-item <?php echo $activeClass; ?>">
                <?php echo $label; ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- Course Content Section -->
    <?php if ($current_section === 'content'): ?>
        <div class="course-content">
            <div class="course-description">
                <h2 class="section-title">📖 Description</h2>
                <p><?php echo nl2br(htmlspecialchars($course['description'] ?? 'No description available.')); ?></p>
            </div>

            <div class="course-files">
                <h3 class="section-title">📁 Course Materials</h3>
                <ul class="materials-list">
                    <?php if (!empty($course_materials)): ?>
                        <?php foreach ($course_materials as $file_url): ?>
                            <li class="material-item">
                                <a href="<?php echo htmlspecialchars($file_url); ?>" target="_blank" class="material-link">
                                    📄 <?php echo basename($file_url); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="no-materials">No files available.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

    <?php elseif ($current_section === 'announcement'): ?>
        <div class="course-content">
            <h2 class="section-title">📢 Announcements</h2>
            <?php if (!empty($announcements)): ?>
                <ul class="announcements-list">
                    <?php foreach ($announcements as $announcement): ?>
                        <li class="announcement-item">
                            <h3 class="announcement-title"><?php echo htmlspecialchars($announcement['title']); ?></h3>
                            <p class="announcement-content"><?php echo nl2br(htmlspecialchars($announcement['content'] ?? '')); ?></p>
                            <small class="announcement-meta">Posted by <?php echo htmlspecialchars($announcement['lecturer_name'] ?? ''); ?> on <?php echo htmlspecialchars($announcement['created_at'] ?? ''); ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📢</div>
                    <h3>No announcements available</h3>
                    <p>Check back later for course announcements.</p>
                </div>
            <?php endif; ?>
        </div>

    <?php elseif ($current_section === 'grade'): ?>
        <div class="course-content">
            <h2 class="section-title">📊 Grades</h2>
            <?php if (!empty($grades)): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Assignment</th>
                            <th>Grade</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grades as $grade): ?>
                            <?php
                            $percentage = (isset($grade['points_earned'], $grade['points_possible']) && $grade['points_possible'] > 0)
                                ? round(($grade['points_earned'] / $grade['points_possible']) * 100, 2) . '%'
                                : htmlspecialchars($grade['points_earned'] ?? 'N/A');
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($grade['assignment_title'] ?? ''); ?></td>
                                <td><?php echo $percentage; ?></td>
                                <td><?php echo htmlspecialchars($grade['grade_date'] ?? ''); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📊</div>
                    <h3>No grades available</h3>
                    <p>Grades will appear here once assignments are graded.</p>
                </div>
            <?php endif; ?>
        </div>

    <?php elseif ($current_section === 'assignment'): ?>
        <div class="course-content">
            <h2 class="section-title">📝 Assignments Contents</h2>
                        <?php if (!empty($assignments)): ?>
                <div class="assignments-container">
                    <?php foreach ($assignments as $assignment): ?>
                        <div class="assignment-card">
                            <div class="assignment-header">
                                <h3 class="assignment-title"><?php echo htmlspecialchars($assignment['title'] ?? ''); ?></h3>
                            </div>
                            <div class="assignment-meta">
                                <span>📅 Due: <?php echo htmlspecialchars($assignment['due_date'] ?? ''); ?></span>
                            </div>
                            <div class="assignment-description">
                                <p><?php echo nl2br(htmlspecialchars($assignment['description'] ?? '')); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📝</div>
                    <h3>No assignments available</h3>
                    <p>Assignments will appear here when they are created.</p>
                </div>
            <?php endif; ?>
        

            <div class="assignment-files">
                <h3 class="section-title">📁 Assignment Files</h3>
                <ul class="materials-list">
                    <?php if (!empty($assignment_files)): ?>
                        <?php foreach ($assignment_files as $file_url): ?>
                            <li class="material-item">
                                <a href="<?php echo htmlspecialchars($file_url); ?>" target="_blank" class="material-link">
                                    📄 <?php echo basename($file_url); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="no-materials">No assignment files available.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>


    <?php elseif ($current_section === 'submission'): ?>
        <div class="course-content">
            <h2 class="section-title">📤 Submissions</h2>
            
            <!-- Assignment selection dropdown always shown -->
            <div class="management-form">
                <form method="GET" action="index.php">
                    <input type="hidden" name="page" value="course_dashboard">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($course_id); ?>">
                    <input type="hidden" name="section" value="submission">
                    <div class="form-group">
                        <label for="assignment_id">Select assignment to submit:</label>
                        <select name="assignment_id" id="assignment_id" onchange="this.form.submit()">
                            <option value="">-- Select Assignment --</option>
                            <?php foreach ($assignments as $a): ?>
                                <option value="<?php echo $a['id']; ?>" <?php echo (isset($_GET['assignment_id']) && $_GET['assignment_id'] == $a['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($a['title']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Show upload form if assignment is selected -->
            <?php if (!empty($assignment)): ?>
                <div class="management-form">
                    <h3>Submit for: <?php echo htmlspecialchars($assignment['title'] ?? ''); ?></h3>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="assignment_id" value="<?php echo htmlspecialchars($assignment['id'] ?? ''); ?>">
                        <div class="form-group">
                            <label for="submission_file">Select file to upload:</label>
                            <input type="file" name="submission_file" id="submission_file" required>
                        </div>
                        <div class="form-group">
                            <label for="submission_comments">Comments:</label>
                            <textarea name="submission_comments" id="submission_comments"></textarea>
                        </div>
                        <button type="submit" name="submit_assignment" class="btn btn-primary">Submit Assignment</button>
                    </form>
                    <?php if (!empty($submission_message)): ?>
                        <div class="message success"><?php echo htmlspecialchars($submission_message); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($submission_error)): ?>
                        <div class="message error"><?php echo htmlspecialchars($submission_error); ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <h3 class="section-title">📋 Your Previous Submissions</h3>
            <?php if (!empty($user_submissions)): ?>
                <div class="submissions-container">
                    <?php foreach ($user_submissions as $submission): ?>
                        <?php
                        $submissionGrade = '';
                        if (isset($submission['points_earned'], $submission['points_possible']) && $submission['points_possible'] > 0) {
                            $submissionGrade = round(($submission['points_earned'] / $submission['points_possible']) * 100, 2) . '%';
                        }
                        ?>
                        <div class="assignment-card">
                            <div class="assignment-header">
                                <h4 class="assignment-title">
                                    <a href="<?php echo htmlspecialchars($submission['file_path'] ?? '#'); ?>" target="_blank">
                                        <?php echo htmlspecialchars($submission['assignment_title'] ?? ''); ?>
                                    </a>
                                </h4>
                                <?php if ($submissionGrade): ?>
                                    <span class="assignment-status status-submitted">Grade: <?php echo $submissionGrade; ?></span>
                                <?php else: ?>
                                    <span class="assignment-status status-pending">Submitted</span>
                                <?php endif; ?>
                            </div>
                            <div class="assignment-meta">
                                <span>📅 Submitted: <?php echo htmlspecialchars($submission['submitted_at'] ?? ''); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📤</div>
                    <h3>No submissions found</h3>
                    <p>Your assignment submissions will appear here.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

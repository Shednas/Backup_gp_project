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
        <?php echo htmlspecialchars($course['lecturer'] ?? ''); ?></span>
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
        <div class="course-description">
            <h2>Description</h2>
            <p><?php echo nl2br(htmlspecialchars($course['description'] ?? 'No description available.')); ?></p>
        </div>

        <div class="course-files">
            <h3>Course Materials</h3>
            <ul>
                <?php if (!empty($course_materials)): ?>
                    <?php foreach ($course_materials as $file_url): ?>
                        <li><a href="<?php echo htmlspecialchars($file_url); ?>" target="_blank"><?php echo basename($file_url); ?></a></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No files available.</li>
                <?php endif; ?>
            </ul>
        </div>

    <?php elseif ($current_section === 'announcement'): ?>
        <h2>Announcements</h2>
        <?php if (!empty($announcements)): ?>
            <ul class="announcements-list">
                <?php foreach ($announcements as $announcement): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($announcement['title']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($announcement['content'] ?? '')); ?></p>
                        <small>Posted by <?php echo htmlspecialchars($announcement['lecturer_name'] ?? ''); ?> on <?php echo htmlspecialchars($announcement['created_at'] ?? ''); ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No announcements available.</p>
        <?php endif; ?>

    <?php elseif ($current_section === 'grade'): ?>
        <h2>Grades</h2>
        <?php if (!empty($grades)): ?>
            <table>
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
            <p>No grades available.</p>
        <?php endif; ?>

    <?php elseif ($current_section === 'assignment'): ?>
        <h2>Assignments</h2>
        <div class="assignment-files">
            <h3>Assignment Files</h3>
            <ul>
                <?php if (!empty($assignment_files)): ?>
                    <?php foreach ($assignment_files as $file_url): ?>
                        <li><a href="<?php echo htmlspecialchars($file_url); ?>" target="_blank"><?php echo basename($file_url); ?></a></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No files available.</li>
                <?php endif; ?>
            </ul>
        </div>
        <?php if (!empty($assignments)): ?>
            <ul class="assignments-list">
                <?php foreach ($assignments as $assignment): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($assignment['title'] ?? ''); ?></h3>
                        <p>Due: <?php echo htmlspecialchars($assignment['due_date'] ?? ''); ?></p>
                        <p><?php echo nl2br(htmlspecialchars($assignment['description'] ?? '')); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No assignments available.</p>
        <?php endif; ?>

    <?php elseif ($current_section === 'submission'): ?>
        <h2>Submissions</h2>
        <!-- Assignment selection dropdown always shown -->
        <form method="GET" action="index.php">
            <input type="hidden" name="page" value="course_dashboard">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($course_id); ?>">
            <input type="hidden" name="section" value="submission">
            <label for="assignment_id">Select assignment to submit:</label>
            <select name="assignment_id" id="assignment_id" onchange="this.form.submit()">
                <option value="">-- Select Assignment --</option>
                <?php foreach ($assignments as $a): ?>
                    <option value="<?php echo $a['id']; ?>" <?php echo (isset($_GET['assignment_id']) && $_GET['assignment_id'] == $a['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($a['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <!-- Show upload form if assignment is selected -->
        <?php if (!empty($assignment)): ?>
            <h3>Submit for: <?php echo htmlspecialchars($assignment['title'] ?? ''); ?></h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="assignment_id" value="<?php echo htmlspecialchars($assignment['id'] ?? ''); ?>">
                <label for="submission_file">Select file to upload:</label>
                <input type="file" name="submission_file" id="submission_file" required>
                <label for="submission_comments">Comments:</label>
                <textarea name="submission_comments" id="submission_comments"></textarea>
                <button type="submit" name="submit_assignment">Submit Assignment</button>
            </form>
            <?php if (!empty($submission_message)): ?>
                <p class="success-message"><?php echo htmlspecialchars($submission_message); ?></p>
            <?php endif; ?>
            <?php if (!empty($submission_error)): ?>
                <p class="error-message"><?php echo htmlspecialchars($submission_error); ?></p>
            <?php endif; ?>
        <?php endif; ?>

        <h3>Your previous submissions</h3>
        <?php if (!empty($user_submissions)): ?>
            <ul>
                <?php foreach ($user_submissions as $submission): ?>
                    <?php
                    $submissionGrade = '';
                    if (isset($submission['points_earned'], $submission['points_possible']) && $submission['points_possible'] > 0) {
                        $submissionGrade = round(($submission['points_earned'] / $submission['points_possible']) * 100, 2) . '%';
                    }
                    ?>
                    <li>
                        <a href="<?php echo htmlspecialchars($submission['file_path'] ?? '#'); ?>" target="_blank">
                            <?php echo htmlspecialchars($submission['assignment_title'] ?? ''); ?>
                        </a>
                        - Submitted on <?php echo htmlspecialchars($submission['submitted_at'] ?? ''); ?>
                        <?php if ($submissionGrade): ?>
                            - Grade: <?php echo $submissionGrade; ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No submissions found.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

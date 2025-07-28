<a href="index.php?page=courses" class="back-link">
    ← Back to Courses
</a>

<div class="course-dashboard">
    <!-- Course Header -->
    <div class="course-header">
        <div class="course-image-header">
            <?php if (!empty($course['image_course'])): ?>
                <img src="assets/images/<?php echo htmlspecialchars($course['image_course']); ?>" 
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
        $sections = ['content' => '📚 Content', 'announcement' => '📢 Announcements', 'grade' => '📊 Grades', 'assignment' => '📝 Assignments', 'submission' => '📤 Submissions'];
        foreach ($sections as $key => $label):
            $activeClass = ($current_section === $key) ? 'active' : '';
        ?>
            <a href="?page=course_dashboard&id=<?php echo urlencode($course_id); ?>&section=<?php echo urlencode($key); ?>" class="course-nav-item <?php echo $activeClass; ?>">
                <?php echo $label; ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- Course Content -->
    <?php if ($current_section === 'content'): ?>
        <div class="course-description">
            <h2>Description</h2>
            <p><?php echo nl2br(htmlspecialchars($course['description'] ?? 'No description available.')); ?></p>
        </div>

        <?php if (!empty($course['contents'])): ?>
            <div class="course-files">
                <h3>Course Materials</h3>
                <ul>
                    <?php
                    $files = is_array($course['contents']) ? $course['contents'] : json_decode($course['contents'], true);
                    if ($files && is_array($files)):
                        foreach ($files as $file): ?>
                            <li><a href="assets/uploads/courses/<?php echo htmlspecialchars($file); ?>" target="_blank"><?php echo htmlspecialchars($file); ?></a></li>
                        <?php endforeach;
                    else: ?>
                        <li>No files available.</li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>

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
                        // Calculate percentage grade or fallback to points earned
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
        <?php else: ?>
            <p>Please select an assignment to submit.</p>
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

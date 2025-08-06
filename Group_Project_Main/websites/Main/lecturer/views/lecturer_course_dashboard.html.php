<?php
// Define base URL for routing consistency
$base_url = 'index.php?page=lecturer_course_dashboard&id=' . urlencode($course_id);
?>

<a href="index.php?page=courses" class="back-link">
    ← Back to My Courses
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
            <span>👨‍🏫 Lecturer Dashboard</span>
            <span>🏆 <?php echo htmlspecialchars($course['credits'] ?? ''); ?> Credits</span>
            <span>📅 Term <?php echo htmlspecialchars($course['term'] ?? ''); ?></span>
        </div>
    </div>

    <!-- Course Navigation -->
    <nav class="course-nav">
        <?php
        $sections = [
            'manage_content' => '📚 Content',
            'manage_announcement' => '📢 Announcements',
            'manage_assignment' => '📝 Assignments',
            'manage_submissions' => '📤 Submissions'
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
    <?php if ($message): ?>
        <div class="message success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($current_section === 'manage_content'): ?>
        <h2>📚 Manage Course Content</h2>
        
        <!-- Add New Material Form -->
        <form method="POST" enctype="multipart/form-data" class="management-form">
            <h3 style="margin-bottom: 20px; color: #2c3e50;">Add Course Material</h3>
            <div class="form-group">
                <label for="material_title">Material Title</label>
                <input type="text" id="material_title" name="material_title" placeholder="Enter material title..." required>
            </div>
            <div class="form-group">
                <label for="material_description">Description</label>
                <textarea id="material_description" name="material_description" placeholder="Enter material description..."></textarea>
            </div>
            <div class="form-group">
                <label for="material_file">Upload File</label>
                <input type="file" id="material_file" name="material_file" accept=".pdf,.doc,.docx,.ppt,.pptx" required>
            </div>
            <button type="submit" name="add_material" class="btn btn-primary">
                📁 Add Material
            </button>
        </form>

        <!-- Existing Materials -->
        <h3 style="margin-bottom: 20px; color: #2c3e50;">Course Materials</h3>
        <?php if (empty($materials)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📚</div>
                <h3>No materials uploaded yet</h3>
                <p>Upload your first course material using the form above.</p>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>File</th>
                        <th>Upload Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materials as $material): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($material['title']); ?></td>
                            <td><a href="/<?php echo ltrim(htmlspecialchars($material['file_path']), '/'); ?>" target="_blank">📁 Download</a></td>
                            <td><?php echo date('M j, Y', strtotime($material['upload_date'])); ?></td>
                            <td>
                                <button class="btn btn-danger" onclick="if(confirm('Delete this material?')) { /* Add delete functionality */ }">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    <?php elseif ($current_section === 'manage_announcement'): ?>
        <h2>📢 Manage Announcements</h2>
        
        <!-- Create New Announcement Form -->
        <form method="POST" class="management-form">
            <h3 style="margin-bottom: 20px; color: #2c3e50;">Create New Announcement</h3>
            <div class="form-group">
                <label for="title">Announcement Title</label>
                <input type="text" id="title" name="title" placeholder="Enter announcement title..." required>
            </div>
            <div class="form-group">
                <label for="content">Announcement Content</label>
                <textarea id="content" name="content" placeholder="Enter announcement content..." required></textarea>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="is_important" name="is_important">
                <label for="is_important">Mark as Important</label>
            </div>
            <button type="submit" name="add_announcement" class="btn btn-primary">
                📢 Create Announcement
            </button>
        </form>

        <!-- Existing Announcements -->
        <h3 style="margin-bottom: 20px; color: #2c3e50;">Course Announcements</h3>
        <?php if (empty($announcements)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📢</div>
                <h3>No announcements created yet</h3>
                <p>Create your first announcement using the form above.</p>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Content Preview</th>
                        <th>Important</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($announcements as $announcement): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($announcement['title']); ?></td>
                            <td><?php echo substr(htmlspecialchars($announcement['content']), 0, 100) . '...'; ?></td>
                            <td>
                                <?php if ($announcement['is_important']): ?>
                                    <span class="badge important">Important</span>
                                <?php else: ?>
                                    <span class="badge">Normal</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($announcement['created_at'])); ?></td>
                            <td>
                                <button class="btn btn-danger" onclick="if(confirm('Delete this announcement?')) { /* Add delete functionality */ }">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    <?php elseif ($current_section === 'manage_assignment'): ?>
        <h2>📝 Manage Assignments</h2>
        
        <!-- Create New Assignment Form -->
        <form method="POST" enctype="multipart/form-data" class="management-form">
            <h3 style="margin-bottom: 20px; color: #2c3e50;">Create New Assignment</h3>
            <div class="form-group">
                <label for="assignment_title">Assignment Title</label>
                <input type="text" id="assignment_title" name="assignment_title" placeholder="Enter assignment title..." required>
            </div>
            <div class="form-group">
                <label for="assignment_description">Description</label>
                <textarea id="assignment_description" name="assignment_description" placeholder="Enter assignment description..." required></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="due_date">Due Date</label>
                    <input type="datetime-local" id="due_date" name="due_date" required>
                </div>
                <div class="form-group">
                    <label for="max_score">Max Score</label>
                    <input type="number" id="max_score" name="max_score" value="100" min="1" required>
                </div>
                <div class="form-group">
                    <label for="weight_percentage">Weight (%)</label>
                    <input type="number" id="weight_percentage" name="weight_percentage" value="10" min="0" max="100" step="0.1">
                </div>
            </div>
            <div class="form-group">
                <label for="instructions">Instructions</label>
                <textarea id="instructions" name="instructions" placeholder="Enter detailed instructions..."></textarea>
            </div>
            <div class="form-group">
                <label for="assignment_file">Assignment File (Optional)</label>
                <input type="file" id="assignment_file" name="assignment_file" accept=".pdf,.doc,.docx">
            </div>
            <button type="submit" name="add_assignment" class="btn btn-primary">
                📝 Create Assignment
            </button>
        </form>

        <!-- Existing Assignments -->
        <h3 style="margin-bottom: 20px; color: #2c3e50;">Course Assignments</h3>
        <?php if (empty($assignments)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📝</div>
                <h3>No assignments created yet</h3>
                <p>Create your first assignment using the form above.</p>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Due Date</th>
                        <th>Max Score</th>
                        <th>Weight</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assignments as $assignment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($assignment['title']); ?></td>
                            <td><?php echo date('M j, Y g:i A', strtotime($assignment['due_date'])); ?></td>
                            <td><?php echo $assignment['max_score']; ?></td>
                            <td><?php echo $assignment['weight_percentage']; ?>%</td>
                            <td>
                                <?php if ($assignment['file_path']): ?>
                                    <a href="/<?php echo ltrim(htmlspecialchars($assignment['file_path']), '/'); ?>" target="_blank" class="btn btn-primary" style="margin-right: 5px;">View File</a>
                                <?php endif; ?>
                                <button class="btn btn-danger" onclick="if(confirm('Delete this assignment?')) { /* Add delete functionality */ }">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    <?php elseif ($current_section === 'manage_submissions'): ?>
        <h2>📤 Manage Submissions</h2>
        
        <?php if (empty($submissions)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📤</div>
                <h3>No submissions yet</h3>
                <p>Student assignment submissions will appear here once they submit their work.</p>
            </div>
        <?php else: ?>
            <div style="margin-bottom: 20px;">
                <p><strong>Total Submissions:</strong> <?php echo count($submissions); ?></p>
            </div>
            <?php 
            $current_assignment = '';
            foreach ($submissions as $submission): 
                if ($current_assignment !== $submission['assignment_title']):
                    if ($current_assignment !== ''): ?>
                        </div>
                    <?php endif; ?>
                    <h3 style="margin: 30px 0 15px 0; color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px;">
                        📝 <?php echo htmlspecialchars($submission['assignment_title']); ?>
                    </h3>
                    <div style="margin-left: 20px;">
                    <?php $current_assignment = $submission['assignment_title']; 
                endif; ?>
                <div class="assignment-card" style="margin-bottom: 20px; border-left: 4px solid <?php echo isset($submission['grade']) ? '#28a745' : '#ffc107'; ?>;">
                    <div class="assignment-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div class="assignment-title">
                                    👤 <?php echo htmlspecialchars($submission['student_name']); ?>
                                    <span style="color: #6c757d; font-size: 14px;">(<?php echo htmlspecialchars($submission['student_username']); ?>)</span>
                                </div>
                            </div>
                            <div class="assignment-status <?php echo isset($submission['grade']) ? 'status-submitted' : 'status-pending'; ?>">
                                <?php echo isset($submission['grade']) ? 'Graded' : 'Needs Grading'; ?>
                            </div>
                        </div>
                    </div>
                    <div class="assignment-meta">
                        <span>📅 Submitted: <?php echo date('M j, Y', strtotime($submission['submitted_at'])); ?></span>
                        <span>⏰ <?php echo date('g:i A', strtotime($submission['submitted_at'])); ?></span>
                        <?php if (isset($submission['grade'])): ?>
                            <span>🏆 Grade: <?php echo $submission['grade']; ?>/<?php echo $submission['max_score']; ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if ($submission['submission_text']): ?>
                        <div class="assignment-description">
                            <strong>Student Comments:</strong><br>
                            <?php echo nl2br(htmlspecialchars($submission['submission_text'])); ?>
                        </div>
                    <?php endif; ?>
                    <div class="assignment-actions" style="display: flex; gap: 10px; align-items: center; margin-top: 15px;">
                        <a href="/<?php echo ltrim(htmlspecialchars($submission['file_path']), '/'); ?>" 
                            class="btn btn-secondary" download>
                            📁 Download Submission
                        </a>
                        <?php if (!isset($submission['grade'])): ?>
                            <button onclick="toggleGradeForm(<?php echo $submission['id']; ?>)" class="btn btn-primary">
                                ✏️ Grade Assignment
                            </button>
                        <?php else: ?>
                            <button onclick="toggleGradeForm(<?php echo $submission['id']; ?>)" class="btn" style="background: #17a2b8; color: white;">
                                ✏️ Update Grade
                            </button>
                        <?php endif; ?>
                    </div>
                    <div id="grade-form-<?php echo $submission['id']; ?>" style="display: none; margin-top: 20px; padding: 20px; background: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6;">
                        <form method="POST" style="margin: 0;">
                            <input type="hidden" name="submission_id" value="<?php echo $submission['id']; ?>">
                            <div style="display: flex; gap: 15px; align-items: end;">
                                <div style="flex: 1;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Grade (out of <?php echo $submission['max_score']; ?>)</label>
                                    <input type="number" name="grade" 
                                            value="<?php echo isset($submission['grade']) ? $submission['grade'] : ''; ?>" 
                                            min="0" max="<?php echo $submission['max_score']; ?>" step="0.1" 
                                            style="width: 100%; padding: 8px; border: 1px solid #dee2e6; border-radius: 4px;" required>
                                </div>
                                <div style="flex: 2;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Feedback (Optional)</label>
                                    <textarea name="feedback" rows="2" 
                                            style="width: 100%; padding: 8px; border: 1px solid #dee2e6; border-radius: 4px; resize: vertical;"
                                            placeholder="Provide feedback to the student..."><?php echo isset($submission['feedback']) ? htmlspecialchars($submission['feedback']) : ''; ?></textarea>
                                </div>
                                <div>
                                    <button type="submit" name="grade_submission" class="btn btn-primary">
                                        💾 Save Grade
                                    </button>
                                    <button type="button" onclick="toggleGradeForm(<?php echo $submission['id']; ?>)" class="btn" style="background: #6c757d; color: white; margin-left: 5px;">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                        <?php if (isset($submission['feedback']) && $submission['feedback']): ?>
                            <div style="margin-top: 15px; padding: 10px; background: white; border-radius: 4px; border-left: 4px solid #3498db;">
                                <strong>Previous Feedback:</strong><br>
                                <?php echo nl2br(htmlspecialchars($submission['feedback'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if ($current_assignment !== ''): ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
    function toggleGradeForm(submissionId) {
        const form = document.getElementById('grade-form-' + submissionId);
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
</script>
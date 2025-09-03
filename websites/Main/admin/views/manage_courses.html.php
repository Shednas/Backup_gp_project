<div class="admin-content">
    <h1>Manage Courses</h1>
    
    <?php if ($message): ?>
        <div class="message success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <!-- Add/Edit Course Form -->
    <div class="form-section">
        <h2><?php echo $edit_course ? 'Edit Course' : 'Add New Course'; ?></h2>
        
        <form method="POST" enctype="multipart/form-data">
            <?php if ($edit_course): ?>
                <input type="hidden" name="course_id" value="<?php echo $edit_course['id']; ?>">
            <?php endif; ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="course_code">Course Code *</label>
                    <input type="text" 
                           id="course_code" 
                           name="course_code" 
                           required
                           value="<?php echo $edit_course ? htmlspecialchars($edit_course['course_code']) : ''; ?>"
                           placeholder="e.g., CS101">
                </div>
                
                <div class="form-group">
                    <label for="title">Course Title *</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           required
                           value="<?php echo $edit_course ? htmlspecialchars($edit_course['title']) : ''; ?>"
                           placeholder="e.g., Introduction to Computer Science">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="lecturer">Lecturer *</label>
                    <input type="text" 
                           id="lecturer" 
                           name="lecturer" 
                           required
                           value="<?php echo $edit_course ? htmlspecialchars($edit_course['lecturer']) : ''; ?>"
                           placeholder="e.g., Dr. John Smith">
                </div>
                
                <div class="form-group">
                    <label for="credits">Credits *</label>
                    <select id="credits" name="credits" required>
                        <option value="1" <?php echo ($edit_course && $edit_course['credits'] == 1) ? 'selected' : ''; ?>>1 Credit</option>
                        <option value="2" <?php echo ($edit_course && $edit_course['credits'] == 2) ? 'selected' : ''; ?>>2 Credits</option>
                        <option value="3" <?php echo (!$edit_course || $edit_course['credits'] == 3) ? 'selected' : ''; ?>>3 Credits</option>
                        <option value="4" <?php echo ($edit_course && $edit_course['credits'] == 4) ? 'selected' : ''; ?>>4 Credits</option>
                        <option value="5" <?php echo ($edit_course && $edit_course['credits'] == 5) ? 'selected' : ''; ?>>5 Credits</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="term">Term *</label>
                    <select id="term" name="term" required>
                        <option value="">Select Term</option>
                        <option value="2024" <?php echo ($edit_course && $edit_course['term'] == '2024') ? 'selected' : ''; ?>>2024</option>
                        <option value="2025" <?php echo ($edit_course && $edit_course['term'] == '2025') ? 'selected' : ''; ?>>2025</option>
                        <option value="2026" <?php echo ($edit_course && $edit_course['term'] == '2026') ? 'selected' : ''; ?>>2026</option>
                        <option value="2027" <?php echo ($edit_course && $edit_course['term'] == '2027') ? 'selected' : ''; ?>>2027</option>
                        <option value="2028" <?php echo ($edit_course && $edit_course['term'] == '2028') ? 'selected' : ''; ?>>2028</option>
                        <option value="2029" <?php echo ($edit_course && $edit_course['term'] == '2029') ? 'selected' : ''; ?>>2029</option>
                        <option value="2030" <?php echo ($edit_course && $edit_course['term'] == '2030') ? 'selected' : ''; ?>>2030</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="image_course">Course Image</label>
                    <input type="file" 
                           id="image_course" 
                           name="image_course" 
                           accept=".jpg,.jpeg,.png,.gif">
                    <?php if ($edit_course && $edit_course['image_course']): ?>
                        <small>Current: <?php echo htmlspecialchars($edit_course['image_course']); ?></small>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="contents">Contents File</label>
                    <input type="file" 
                           id="contents" 
                           name="contents" 
                           accept=".pdf,.doc,.docx,.txt">
                    <?php if ($edit_course && $edit_course['contents']): ?>
                        <small>Current: <?php echo htmlspecialchars($edit_course['contents']); ?></small>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="assignment">Assignment File</label>
                    <input type="file" 
                           id="assignment" 
                           name="assignment" 
                           accept=".pdf,.doc,.docx,.txt">
                    <?php if ($edit_course && $edit_course['assignment']): ?>
                        <small>Current: <?php echo htmlspecialchars($edit_course['assignment']); ?></small>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="assignment_submission">Assignment Submission File</label>
                    <input type="file" 
                           id="assignment_submission" 
                           name="assignment_submission" 
                           accept=".pdf,.doc,.docx,.txt">
                    <?php if ($edit_course && $edit_course['assignment_submission']): ?>
                        <small>Current: <?php echo htmlspecialchars($edit_course['assignment_submission']); ?></small>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-group full-width">
                <label for="description">Description</label>
                <textarea id="description" 
                          name="description" 
                          placeholder="Course description..."><?php echo $edit_course ? htmlspecialchars($edit_course['description']) : ''; ?></textarea>
            </div>
            
            <div class="form-actions">
                <?php if ($edit_course): ?>
                    <button type="submit" name="update_course" class="btn btn-primary">
                        Update Course
                    </button>
                    <a href="index.php?page=manage_courses" class="btn btn-secondary">
                        Cancel
                    </a>
                <?php else: ?>
                    <button type="submit" name="add_course" class="btn btn-success">
                        Add Course
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>
    
    <!-- Courses List -->
    <div class="courses-table">
        <table>
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Title</th>
                    <th>Lecturer</th>
                    <th>Credits</th>
                    <th>Term</th>
                    <th>Image</th>
                    <th>Files</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($courses)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 30px; color: #666;">
                            No courses found. Add your first course above.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($course['course_code']); ?></strong>
                            </td>
                            <td>
                                <div style="font-weight: bold;"><?php echo htmlspecialchars($course['title']); ?></div>
                                <?php if ($course['description']): ?>
                                    <small style="color: #666;">
                                        <?php echo substr(htmlspecialchars($course['description']), 0, 50) . '...'; ?>
                                    </small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($course['lecturer']); ?></td>
                            <td><?php echo $course['credits']; ?> credits</td>
                            <td>
                                <?php echo $course['term'] ? htmlspecialchars($course['term']) : '<span style="color: #999;">Not set</span>'; ?>
                            </td>
                            <td>
                                <?php if ($course['image_course']): ?>
                                    <img src="../../assets/images/<?php echo htmlspecialchars($course['image_course']); ?>" 
                                         alt="Course Image" 
                                         style="width: 50px; height: 30px; object-fit: cover; border-radius: 4px;">
                                <?php else: ?>
                                    <span style="color: #999; font-size: 12px;">No image</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($course['contents']): ?>
                                    <span style="color: #28a745; font-size: 12px;">📄 Contents</span><br>
                                <?php endif; ?>
                                <?php if ($course['assignment']): ?>
                                    <span style="color: #007bff; font-size: 12px;">📝 Assignment</span><br>
                                <?php endif; ?>
                                <?php if ($course['assignment_submission']): ?>
                                    <span style="color: #6f42c1; font-size: 12px;">📤 Submissions</span>
                                <?php endif; ?>
                                <?php if (!$course['contents'] && !$course['assignment'] && !$course['assignment_submission']): ?>
                                    <span style="color: #999; font-size: 12px;">No files</span>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <a href="index.php?page=manage_courses&edit=<?php echo $course['id']; ?>" 
                                   class="btn btn-warning">
                                    Edit
                                </a>
                                <form method="POST" 
                                      style="display: inline;" 
                                      onsubmit="return confirm('Are you sure you want to delete this course?');">
                                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                    <button type="submit" name="delete_course" class="btn btn-danger">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
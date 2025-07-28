<section class="courses">
    <h2><?php echo $is_lecturer ? 'My Courses' : 'Courses'; ?></h2>
    <p style="color: #6c757d; margin-bottom: 25px;">
        <?php if ($is_lecturer): ?>
            Manage and monitor your teaching courses
        <?php else: ?>
            Explore and manage your academic courses
        <?php endif; ?>
    </p>

    <?php if ($error_message): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f1aeb5;">
            ⚠️ <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <!-- Search and Filter Controls -->
    <div class="courses-controls">
        <div class="controls-row">
            <!-- Search Box -->
            <form method="GET" class="search-form">
                <input type="hidden" name="term" value="<?php echo $current_term; ?>">
                <input type="hidden" name="filter" value="<?php echo $current_filter; ?>">
                <div class="search-input-container">
                    <input type="text" name="search" class="course-search" 
                           placeholder="Search courses..." 
                           value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit" class="search-btn">🔍</button>
                </div>
            </form>

            <!-- Terms Dropdown -->
            <form method="GET" class="filter-form">
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                <input type="hidden" name="filter" value="<?php echo $current_filter; ?>">
                <div class="filter-group">
                    <label>Terms</label>
                    <select name="term" onchange="this.form.submit()">
                        <option value="all" <?php echo $current_term === 'all' ? 'selected' : ''; ?>>All terms</option>
                        <option value="2024" <?php echo $current_term === '2024' ? 'selected' : ''; ?>>2024</option>
                        <option value="2025" <?php echo $current_term === '2025' ? 'selected' : ''; ?>>2025</option>
                        <option value="2026" <?php echo $current_term === '2026' ? 'selected' : ''; ?>>2026</option>
                        <option value="2027" <?php echo $current_term === '2027' ? 'selected' : ''; ?>>2027</option>
                        <option value="2028" <?php echo $current_term === '2028' ? 'selected' : ''; ?>>2028</option>
                        <option value="2029" <?php echo $current_term === '2029' ? 'selected' : ''; ?>>2029</option>
                        <option value="2030" <?php echo $current_term === '2030' ? 'selected' : ''; ?>>2030</option>
                    </select>
                </div>
            </form>

            <!-- Filters Dropdown -->
            <form method="GET" class="filter-form">
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                <input type="hidden" name="term" value="<?php echo $current_term; ?>">
                <div class="filter-group">
                    <label>Status</label>
                    <select name="filter" onchange="this.form.submit()">
                        <option value="all" <?php echo $current_filter === 'all' ? 'selected' : ''; ?>>All courses</option>
                        <?php if ($is_lecturer): ?>
                            <option value="active" <?php echo $current_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="archived" <?php echo $current_filter === 'archived' ? 'selected' : ''; ?>>Archived</option>
                        <?php else: ?>
                            <option value="open" <?php echo $current_filter === 'open' ? 'selected' : ''; ?>>Available</option>
                            <option value="enrolled" <?php echo $current_filter === 'enrolled' ? 'selected' : ''; ?>>Enrolled</option>
                            <option value="completed" <?php echo $current_filter === 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <?php endif; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Info -->
    <div class="results-info">
        <span><?php echo count($filtered_courses); ?> course<?php echo count($filtered_courses) !== 1 ? 's' : ''; ?> found</span>
    </div>

    <!-- Courses Grid -->
    <div class="courses-grid">
        <?php if (empty($filtered_courses)): ?>
            <div class="no-courses">
                <div class="no-courses-icon">📚</div>
                <h3>No courses found</h3>
                <p>
                    <?php if ($is_lecturer): ?>
                        No courses assigned to you yet.
                    <?php else: ?>
                        Try adjusting your search criteria or filters.
                    <?php endif; ?>
                </p>
            </div>
        <?php else: ?>
            <?php foreach ($filtered_courses as $course): ?>
                <a href="<?php echo $is_lecturer ? 'index.php?page=lecturer_course_dashboard&id=' . $course['id'] : 'index.php?page=course_dashboard&id=' . $course['id']; ?>" style="text-decoration: none; color: inherit;">
                    <div class="course-card">
                        <div class="course-header">
                            <div class="course-icon-wrapper">
                                <?php if ($course['image_course']): ?>
                                    <img src="assets/images/<?php echo htmlspecialchars($course['image_course']); ?>" 
                                         alt="<?php echo htmlspecialchars($course['title']); ?>" 
                                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                                <?php else: ?>
                                    <div style="color: white; font-size: 28px;">📚</div>
                                <?php endif; ?>
                            </div>
                            <div class="course-status-badge <?php echo $is_lecturer ? 'enrolled' : 'open'; ?>">
                                <?php echo $is_lecturer ? 'Teaching' : 'Available'; ?>
                            </div>
                        </div>
                        <div class="course-content">
                            <div class="course-code"><?php echo htmlspecialchars($course['code']); ?></div>
                            <h3 class="course-title"><?php echo htmlspecialchars($course['title']); ?></h3>
                            <div class="course-meta">
                                <div class="instructor">
                                    <span class="instructor-icon">👨‍🏫</span>
                                    <span><?php echo htmlspecialchars($course['lecturer']); ?></span>
                                </div>
                                <div class="course-credits">
                                    <span class="credits-icon">🏆</span>
                                    <span><?php echo $course['credits']; ?> Credits</span>
                                </div>
                            </div>
                            <div class="course-term">
                                <span class="term-icon">📅</span>
                                <span>Term: <?php echo $course['term']; ?></span>
                            </div>
                            <div class="course-actions">
                                <button class="course-btn primary">
                                    <?php echo $is_lecturer ? 'Manage Course' : 'Access Course'; ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
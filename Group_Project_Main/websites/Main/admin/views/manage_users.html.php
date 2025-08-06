<div class="admin-container">
    <div class="admin-header">
        <h1>User Management</h1>
        <p>Add, manage users and assign courses to lecturers</p>
    </div>

    <?php if ($message): ?>
        <div class="message success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Navigation Tabs -->
    <div class="admin-tabs">
        <?php 
        $tabs = [
            'add-user' => ['icon' => '➕', 'title' => 'Add User'],
            'manage-users' => ['icon' => '👥', 'title' => 'Manage Users'],
            'course-assignments' => ['icon' => '📚', 'title' => 'Course Assignments']
        ];
        
        foreach ($tabs as $tab_key => $tab_info): 
            $is_active = $active_tab == $tab_key ? 'active' : '';
        ?>
            <a href="index.php?page=manage_users&tab=<?= $tab_key ?>" class="admin-tab <?= $is_active ?>">
                <?= $tab_info['icon'] ?> <?= $tab_info['title'] ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Add User Tab -->
    <div id="add-user-tab" class="tab-content <?= $active_tab == 'add-user' ? 'active' : '' ?>">
        <div class="form-container">
            <div class="section-header">
                <h3>Add New User</h3>
                <p>Create a new user account and assign appropriate permissions</p>
            </div>
            
            <form method="POST" id="addUserForm">
                <!-- Basic Information -->
                <div class="form-section">
                    <h4>👤 Basic Information</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="username">Username *</label>
                            <input type="text" id="username" name="username" required placeholder="Enter username">
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required placeholder="user@example.com">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="full_name">Full Name *</label>
                            <input type="text" id="full_name" name="full_name" required placeholder="Enter full name">
                        </div>
                        <div class="form-group">
                            <label for="password">Password *</label>
                            <input type="password" id="addUserPassword" name="password" required placeholder="Enter secure password">
                        </div>
                    </div>
                </div>

                <!-- User Type Selection -->
                <div class="form-section">
                    <h4>🏷️ User Type & Permissions</h4>
                    <div class="form-group user-type-selector">
                        <label>Select User Type</label>
                        <div class="user-type-options">
                            <?php 
                            $user_types = [
                                'student' => ['label' => 'Student', 'class' => 'student'],
                                'lecturer' => ['label' => 'Lecturer', 'class' => 'lecturer'],
                                'admin' => ['label' => 'Admin', 'class' => 'admin']
                            ];
                            
                            foreach ($user_types as $type => $info):
                                $checked = $selected_user_type == $type ? 'checked' : '';
                            ?>
                                <label class="user-type-option">
                                    <input type="radio" name="user_type" value="<?= $type ?>" <?= $checked ?>>
                                    <span class="badge <?= $info['class'] ?>"><?= $info['label'] ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Course Assignment -->
                <div class="form-section course-selection-section <?= ($selected_user_type == 'student' || $selected_user_type == 'lecturer') ? 'visible' : 'hidden' ?>" id="courseSelection">
                    <h4>📚 Course Assignment</h4>
                    <div class="form-group">
                        <label>
                            <span id="courseSelectionLabel">
                                <?= $selected_user_type == 'lecturer' ? 'Assign Courses to Teach' : 'Enroll in Courses' ?>
                            </span>
                        </label>
                        <div class="course-selection">
                            <?php if (!empty($courses)): ?>
                                <?php foreach ($courses as $course): ?>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="course_<?= $course['id'] ?>" name="courses[]" value="<?= $course['id'] ?>">
                                        <label for="course_<?= $course['id'] ?>">
                                            <strong><?= htmlspecialchars($course['course_code']) ?></strong> - <?= htmlspecialchars($course['title']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="no-data">No courses available for assignment.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" name="add_user" class="btn btn-primary">
                        ➕ Create User Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Manage Users Tab -->
    <div id="manage-users-tab" class="tab-content <?= $active_tab == 'manage-users' ? 'active' : '' ?>">
        <div class="form-container">
            <div class="section-header">
                <h3>All Users</h3>
                <?php if (!empty($users)): ?>
                    <p><?= count($users) ?> users registered</p>
                <?php endif; ?>
            </div>
            
            <?php if (empty($users)): ?>
                <div class="no-data">
                    <p>🚫 No users found. Create your first user account above!</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>👤 User Details</th>
                                <th>🏷️ Type</th>
                                <th>📧 Contact</th>
                                <th>📅 Created</th>
                                <th>⚙️ Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            <strong><?= htmlspecialchars($user['full_name']) ?></strong>
                                            <div class="user-meta">
                                                <small>@<?= htmlspecialchars($user['username']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php 
                                        require_once __DIR__ . '/../../src/helper.php';
                                        $role = get_user_role($user['id']);
                                        if ($role === 'admin') {
                                            echo '<span class="badge admin">Admin</span>';
                                        } elseif ($role === 'lecturer') {
                                            echo '<span class="badge lecturer">Lecturer</span>';
                                        } else {
                                            echo '<span class="badge student">Student</span>';
                                        }
                                        ?>
                                    </td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <?php if ($user['id'] != $current_user['id']): ?>
                                            <form method="POST" class="inline-form">
                                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                <button type="submit" name="delete_user" class="btn btn-danger btn-small"
                                                        onclick="return confirm('⚠️ Are you sure you want to delete this user?\n\nThis action cannot be undone.')"
                                                        title="Delete User">
                                                    🗑️ Delete
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="current-user-indicator">
                                                <em>👤 Current User</em>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Course Assignments Tab -->
    <div id="course-assignments-tab" class="tab-content <?= $active_tab == 'course-assignments' ? 'active' : '' ?>">
        <!-- Assignment Form -->
        <div class="form-container">
            <div class="section-header">
                <h3>Assign Course to Lecturer</h3>
            </div>
            
            <form method="POST" class="course-assignment-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="lecturer_id">👨‍🏫 Select Lecturer</label>
                        <select id="lecturer_id" name="lecturer_id" required>
                            <option value="">Choose a lecturer...</option>
                            <?php foreach ($lecturers as $lecturer): ?>
                                <option value="<?= $lecturer['id'] ?>">
                                    <?= htmlspecialchars($lecturer['full_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="course_id">📖 Select Course</label>
                        <select id="course_id" name="course_id" required>
                            <option value="">Choose a course...</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= $course['id'] ?>">
                                    <?= htmlspecialchars($course['course_code'] . ' - ' . $course['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" name="assign_course" class="btn btn-success">
                        📚 Add Course
                    </button>
                </div>
            </form>
        </div>

        <!-- Current Assignments -->
        <div class="form-container">
            <div class="section-header">
                <h3>Current Course Assignments</h3>
                <?php if (!empty($lecturer_assignments)): ?>
                    <p><?= count($lecturer_assignments) ?> assignment<?= count($lecturer_assignments) !== 1 ? 's' : '' ?> active</p>
                <?php endif; ?>
            </div>
            
            <?php if (empty($lecturer_assignments)): ?>
                <div class="no-data">
                    <p>📝 No course assignments found. Create your first assignment above!</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>👨‍🏫 Lecturer</th>
                                <th>📚 Course Details</th>
                                <th>📅 Assigned Date</th>
                                <th>⚙️ Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lecturer_assignments as $assignment): ?>
                                <tr>
                                    <td><?= htmlspecialchars($assignment['lecturer_name']) ?></td>
                                    <td>
                                        <div class="course-info">
                                            <strong><?= htmlspecialchars($assignment['course_code']) ?></strong>
                                            <div class="course-title">
                                                <small><?= htmlspecialchars($assignment['course_title']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= date('M j, Y', strtotime($assignment['assigned_at'])) ?></td>
                                    <td>
                                        <form method="POST" class="inline-form">
                                            <input type="hidden" name="assignment_id" value="<?= $assignment['id'] ?>">
                                            <button type="submit" name="remove_assignment" class="btn btn-danger btn-small"
                                                    onclick="return confirm('⚠️ Are you sure you want to remove this assignment?\n\nThe lecturer will no longer have access to this course.')"
                                                    title="Remove Assignment">
                                                🗑️ Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Dynamic course selection visibility and labeling
document.addEventListener('DOMContentLoaded', function() {
    const userTypeRadios = document.querySelectorAll('input[name="user_type"]');
    const courseSelection = document.getElementById('courseSelection');
    const courseLabel = document.getElementById('courseSelectionLabel');
    
    userTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'student' || this.value === 'lecturer') {
                courseSelection.classList.remove('hidden');
                courseSelection.classList.add('visible');
                courseLabel.textContent = this.value === 'lecturer' 
                    ? 'Assign Courses to Teach' 
                    : 'Enroll in Courses';
            } else {
                courseSelection.classList.remove('visible');
                courseSelection.classList.add('hidden');
            }
        });
    });
});
</script>
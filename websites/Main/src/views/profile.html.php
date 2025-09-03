<div class="profile-container">
    <?php echo $update_message; ?>
    <div class="profile-layout">
        <!-- Left Column - Profile Info -->
        <div class="profile-sidebar">
            <div class="profile-card">
                <div class="profile-avatar-large">
                    <?php echo $user_initials; ?>
                </div>
                <h2 class="profile-name"><?php echo htmlspecialchars($user_name); ?></h2>
                <p class="profile-username">@<?php echo htmlspecialchars($_SESSION['username']); ?></p>
                <div class="user-badges">
                    <?php if (isLecturer($current_user)): ?>
                        <span class="badge lecturer">
                            <i class="icon">👨‍🏫</i> Lecturer
                        </span>
                    <?php endif; ?>
                    <?php if (getUserRole($current_user) === 'students'): ?>
                        <span class="badge student">
                            <i class="icon">🎓</i> Student
                        </span>
                    <?php endif; ?>
                    <?php if (isAdmin($current_user)): ?>
                        <span class="badge admin">
                            <i class="icon">🛡️</i> Admin
                        </span>
                    <?php endif; ?>
                    <?php if (getUserRole($current_user) === 'super_admins'): ?>
                        <span class="badge super-admin">
                            <i class="icon">⭐</i> Super Admin
                        </span>
                    <?php endif; ?>
                </div>
                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-value">Active</span>
                        <span class="stat-label">Status</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value"><?php echo date('Y'); ?></span>
                        <span class="stat-label">Member Since</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Right Column - Editable Information -->
        <div class="profile-main">
            <div class="profile-header-bar">
                <h1>Personal Information</h1>
            </div>
            <form method="POST" class="modern-form">
                <div class="form-sections">
                    <!-- Basic Details Section -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <span class="section-icon">👤</span>
                            Basic Details
                        </h3>
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="full_name">Full Name</label>
                                <input type="text" id="full_name" name="full_name" 
                                        value="<?php echo htmlspecialchars($current_user['full_name'] ?? ''); ?>" 
                                        disabled="">
                            </div>
                            <div class="form-field">
                                <label for="username">Username</label>
                                <input type="text" id="username" 
                                        value="<?php echo htmlspecialchars($_SESSION['username']); ?>" disabled>
                            </div>
                        </div>
                    </div>
                    <!-- Contact Information Section -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <span class="section-icon">📧</span>
                            Contact Information
                        </h3>
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" 
                                        value="<?php echo htmlspecialchars($current_user['email'] ?? ''); ?>"
                                        disabled="">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
    <script>
        // Auto-hide success/error messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const messages = document.querySelectorAll('.success-message, .error-message');
            messages.forEach(message => {
                if (message.style.display !== 'none') {
                    setTimeout(() => {
                        message.style.display = 'none';
                    }, 5000);
                }
            });
        });
    </script>

<div class="admin-content">
    <div class="content-header">
        <h1>📅 Manage Events</h1>
        <p>Create, edit, and manage university events</p>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Add/Edit Event Form -->
    <div class="form-section">
        <h2><?php echo $edit_event ? 'Edit Event' : 'Add New Event'; ?></h2>
        <form method="POST" class="admin-form">
            <?php if ($edit_event): ?>
                <input type="hidden" name="event_id" value="<?php echo $edit_event['id']; ?>">
            <?php endif; ?>
            
            <!-- Basic Information -->
            <div class="form-row">
                <div class="form-group">
                    <label for="title">Event Title *</label>
                    <input type="text" id="title" name="title" required 
                           value="<?php echo $edit_event ? htmlspecialchars($edit_event['title']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category">
                        <?php 
                        $categories = [
                            'academic' => 'Academic',
                            'sports' => 'Sports', 
                            'cultural' => 'Cultural',
                            'workshop' => 'Workshop',
                            'seminar' => 'Seminar',
                            'other' => 'Other'
                        ];
                        
                        foreach ($categories as $value => $label):
                            $selected = ($edit_event && $edit_event['category'] == $value) ? 'selected' : '';
                            if (!$edit_event && $value == 'other') $selected = 'selected';
                        ?>
                            <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Enter event description..."><?php echo $edit_event ? htmlspecialchars($edit_event['description']) : ''; ?></textarea>
            </div>

            <!-- Date and Time -->
            <div class="form-row">
                <div class="form-group">
                    <label for="event_date">Event Date *</label>
                    <input type="date" id="event_date" name="event_date" required 
                           value="<?php echo $edit_event ? $edit_event['event_date'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="event_time">Event Time *</label>
                    <input type="time" id="event_time" name="event_time" required 
                           value="<?php echo $edit_event ? $edit_event['event_time'] : ''; ?>">
                </div>
            </div>

            <!-- Location and Capacity -->
            <div class="form-row">
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" placeholder="e.g., Main Auditorium, Room 101"
                           value="<?php echo $edit_event ? htmlspecialchars($edit_event['location']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="max_participants">Max Participants</label>
                    <input type="number" id="max_participants" name="max_participants" min="1" placeholder="Leave empty for unlimited"
                           value="<?php echo $edit_event ? $edit_event['max_participants'] : ''; ?>">
                </div>
            </div>

            <!-- Status (only for editing) -->
            <?php if ($edit_event): ?>
            <div class="form-row">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <?php 
                        $statuses = [
                            'active' => 'Active',
                            'cancelled' => 'Cancelled',
                            'completed' => 'Completed'
                        ];
                        
                        foreach ($statuses as $value => $label):
                            $selected = ($edit_event['status'] == $value) ? 'selected' : '';
                        ?>
                            <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <?php endif; ?>

            <div class="form-actions">
                <button type="submit" name="<?php echo $edit_event ? 'update_event' : 'add_event'; ?>" class="btn btn-primary">
                    <?php echo $edit_event ? '📝 Update Event' : '➕ Add Event'; ?>
                </button>
                <?php if ($edit_event): ?>
                    <a href="manageEvents.php" class="btn btn-secondary">❌ Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Events List -->
    <div class="table-section">
        <div class="content-header">
            <h2>📋 All Events</h2>
            <?php if (!empty($events)): ?>
                <p><?php echo count($events); ?> event<?php echo count($events) !== 1 ? 's' : ''; ?> found</p>
            <?php endif; ?>
        </div>
        
        <?php if (empty($events)): ?>
            <div class="no-data">
                <p>🎯 No events found. Add your first event above!</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>📋 Event Details</th>
                            <th>📅 Date & Time</th>
                            <th>📍 Location</th>
                            <th>🏷️ Category</th>
                            <th>👥 Capacity</th>
                            <th>📊 Status</th>
                            <th>👤 Creator</th>
                            <th>⚙️ Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $event): ?>
                        <tr>
                            <td>
                                <div class="event-title">
                                    <strong><?php echo htmlspecialchars($event['title']); ?></strong>
                                </div>
                                <?php if ($event['description']): ?>
                                    <div class="event-description">
                                        <small class="text-muted">
                                            <?php 
                                            $description = htmlspecialchars($event['description']);
                                            echo strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                                            ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="event-date">
                                    <?php echo date('M j, Y', strtotime($event['event_date'])); ?>
                                </div>
                                <div class="event-time">
                                    <small><?php echo date('g:i A', strtotime($event['event_time'])); ?></small>
                                </div>
                            </td>
                            <td>
                                <?php echo $event['location'] ? htmlspecialchars($event['location']) : '<em>Not specified</em>'; ?>
                            </td>
                            <td>
                                <span class="category-badge category-<?php echo $event['category']; ?>">
                                    <?php echo ucfirst($event['category']); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo $event['max_participants'] ? number_format($event['max_participants']) : 'Unlimited'; ?>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo $event['status']; ?>">
                                    <?php echo ucfirst($event['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($event['creator_name']); ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="manageEvents.php?edit=<?php echo $event['id']; ?>" 
                                       class="btn btn-small btn-edit" title="Edit Event">
                                        ✏️ Edit
                                    </a>
                                    <form method="POST" style="display: inline;" 
                                          onsubmit="return confirm('⚠️ Are you sure you want to delete this event?\n\nThis action cannot be undone.');">
                                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                        <button type="submit" name="delete_event" 
                                                class="btn btn-small btn-delete" title="Delete Event">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
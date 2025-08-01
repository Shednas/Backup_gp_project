
<div class="events-content">
    <div class="events-header">
        <div class="header-main">
            <h1>📅 University Events</h1>
            <p>Stay updated with campus activities and opportunities</p>
        </div>
    </div>

    <!-- Event Tabs -->
    <div class="event-tabs">
        <a href="?page=events&tab=upcoming&filter=<?php echo $current_filter; ?>" 
           class="event-tab <?php echo $current_tab === 'upcoming' ? 'active' : ''; ?>">
            Upcoming Events
        </a>
        <a href="?page=events&tab=past&filter=<?php echo $current_filter; ?>" 
           class="event-tab <?php echo $current_tab === 'past' ? 'active' : ''; ?>">
            Past Events
        </a>
    </div>
    
    <!-- Filter Options -->
    <div class="filter-section">
        <form method="GET">
            <input type="hidden" name="page" value="events">
            <input type="hidden" name="tab" value="<?php echo $current_tab; ?>">
            <select name="filter" class="filter-select" onchange="this.form.submit()">
                <option value="all" <?php echo $current_filter === 'all' ? 'selected' : ''; ?>>All Categories</option>
                <option value="academic" <?php echo $current_filter === 'academic' ? 'selected' : ''; ?>>Academic</option>
                <option value="sports" <?php echo $current_filter === 'sports' ? 'selected' : ''; ?>>Sports</option>
                <option value="cultural" <?php echo $current_filter === 'cultural' ? 'selected' : ''; ?>>Cultural</option>
                <option value="workshop" <?php echo $current_filter === 'workshop' ? 'selected' : ''; ?>>Workshop</option>
                <option value="seminar" <?php echo $current_filter === 'seminar' ? 'selected' : ''; ?>>Seminar</option>
                <option value="other" <?php echo $current_filter === 'other' ? 'selected' : ''; ?>>Other</option>
            </select>
        </form>
    </div>

    <!-- Events List -->
    <div class="events-list">
        <?php if (empty($events)): ?>
            <div class="no-events">
                <p>No <?php echo $current_tab === 'upcoming' ? 'upcoming' : 'past'; ?> events found<?php echo $current_filter !== 'all' ? ' for the selected category' : ''; ?>.</p>
                <?php if ($current_tab === 'upcoming'): ?>
                    <p>Check back later for new events!</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="events-container">
                <?php foreach ($events as $event): ?>
                    <div class="event-item <?php echo $current_tab === 'past' ? 'past-event' : ''; ?>">
                        <div class="event-date">
                            <div class="date-day"><?php echo date('d', strtotime($event['event_date'])); ?></div>
                            <div class="date-month"><?php echo date('M', strtotime($event['event_date'])); ?></div>
                            <div class="date-year"><?php echo date('Y', strtotime($event['event_date'])); ?></div>
                        </div>
                        <div class="event-content">
                            <div class="event-header">
                                <h3 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h3>
                                <span class="event-category category-<?php echo $event['category']; ?>">
                                    <?php echo ucfirst($event['category']); ?>
                                </span>
                            </div>
                            
                            <?php if ($event['description']): ?>
                                <p class="event-description"><?php echo htmlspecialchars($event['description']); ?></p>
                            <?php endif; ?>
                            
                            <div class="event-details">
                                <div class="event-meta">
                                    <span><strong>🕐 Time:</strong> <?php echo date('g:i A', strtotime($event['event_time'])); ?></span>
                                    <?php if ($event['location']): ?>
                                        <span><strong>📍 Location:</strong> <?php echo htmlspecialchars($event['location']); ?></span>
                                    <?php endif; ?>
                                    <?php if ($event['max_participants']): ?>
                                        <span><strong>👥 Max Participants:</strong> <?php echo $event['max_participants']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <?php if ($current_tab === 'upcoming'): ?>
                                <div class="event-actions">
                                    <?php 
                                    $is_registered = isset($_SESSION['registered_events']) && in_array($event['id'], $_SESSION['registered_events']);
                                    ?>
                                    <form method="POST" class="register-form">
                                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                        <?php if ($is_registered): ?>
                                            <button type="submit" name="unregister_event" class="btn btn-register registered btn-small">
                                                Unregister
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" name="register_event" class="btn btn-register btn-small">
                                                Register
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
// Fetch unread notifications count
$notification_count = 0;
$recent_notifications = [];
if (isset($_SESSION['manager_id'])) {
    // Get unread notifications count
    $count_query = "SELECT COUNT(*) as count FROM notifications WHERE receiver_id = ? AND receiver_type = 'manager' AND is_read = 0";
    $stmt = $conn->prepare($count_query);
    if ($stmt) {
        $stmt->bind_param("i", $_SESSION['manager_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $notification_count = $row['count'] ?? 0;
        $stmt->close();
    }

    // Get recent notifications
    $notif_query = "SELECT * FROM notifications WHERE receiver_id = ? AND receiver_type = 'manager' ORDER BY created_at DESC LIMIT 5";
    $stmt = $conn->prepare($notif_query);
    if ($stmt) {
        $stmt->bind_param("i", $_SESSION['manager_id']);
        $stmt->execute();
        $recent_notifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}

// Get manager name and initials
$manager_name = $_SESSION['manager_name'] ?? 'Admin';
$name_parts = explode(' ', $manager_name);
$initials = strtoupper(substr($name_parts[0], 0, 1) . (isset($name_parts[1]) ? substr($name_parts[1], 0, 1) : ''));
?>
<header class="app-topbar">
    <div class="container-fluid">
        <div class="topbar-container navbar-header align-items-center">
            <div class="topbar-left d-flex align-items-center gap-2">
                <!-- Menu Toggle Button -->
                <button type="button" class="topbar-toggle button-toggle-menu">
                    <iconify-icon icon="solar:hamburger-menu-outline" class="fs-22 align-middle"></iconify-icon>
                </button>

                <!-- Brand Title -->
                <h1 class="brand-title d-none d-md-block mb-0">ApplyBoard Africa</h1>
            </div>

            <div class="topbar-right d-flex align-items-center gap-">
                <!-- Theme Color (Light/Dark) -->
                <button type="button" class="topbar-icon-btn" id="light-dark-mode">
                    <iconify-icon icon="solar:moon-outline" class="align-middle light-mode"></iconify-icon>
                    <iconify-icon icon="solar:sun-2-outline" class="align-middle dark-mode"></iconify-icon>
                </button>

                <!-- Notifications -->
                <div class="dropdown topbar-item mb-">
                    <button type="button" class="topbar-icon-btn" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <iconify-icon icon="solar:bell-outline" class="align-middle"></iconify-icon>
                        <?php if ($notification_count > 0): ?>
                            <span
                                class="notification-badge"><?php echo $notification_count > 9 ? '9+' : $notification_count; ?></span>
                        <?php endif; ?>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                        <div class="notification-header">
                            <h6>Notifications</h6>
                            <?php if ($notification_count > 0): ?>
                                <span class="badge bg-primary"><?php echo $notification_count; ?> new</span>
                            <?php endif; ?>
                        </div>
                        <div class="notification-body">
                            <?php if (empty($recent_notifications)): ?>
                                <div class="notification-empty">
                                    <iconify-icon icon="solar:bell-off-outline"></iconify-icon>
                                    <p>No notifications yet</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($recent_notifications as $notif): ?>
                                    <a href="notifications.php" class="notification-item">
                                        <div class="notification-icon">
                                            <iconify-icon icon="solar:bell-outline"></iconify-icon>
                                        </div>
                                        <div class="notification-content">
                                            <p class="notification-title"><?php echo htmlspecialchars($notif['message']); ?></p>
                                            <span
                                                class="notification-time"><?php echo date('M j, g:i A', strtotime($notif['created_at'])); ?></span>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="notification-footer">
                            <a href="notifications.php">View All Notifications</a>
                        </div>
                    </div>
                </div>

                <!-- User Dropdown -->
                <div class="dropdown topbar-item mb-">
                    <button type="button" class="topbar-user-btn" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <span class="user-avatar"><?php echo $initials; ?></span>
                        <div class="user-info">
                            <div class="user-name"><?php echo htmlspecialchars($manager_name); ?></div>
                            <div class="user-role">Administrator</div>
                        </div>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end user-dropdown">
                        <div class="dropdown-header">
                            <strong><?php echo htmlspecialchars($manager_name); ?></strong>
                            <small class="d-block text-muted">Administrator</small>
                        </div>
                        <a class="dropdown-item" href="profile.php">
                            <iconify-icon icon="solar:user-outline" class="align-middle me-2"></iconify-icon>
                            <span>My Profile</span>
                        </a>
                        <a class="dropdown-item" href="settings.php">
                            <iconify-icon icon="solar:settings-outline" class="align-middle me-2"></iconify-icon>
                            <span>Settings</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="logout.php">
                            <iconify-icon icon="solar:logout-3-outline" class="align-middle me-2"></iconify-icon>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
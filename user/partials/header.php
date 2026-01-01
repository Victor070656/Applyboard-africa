<?php
// Initialize user data for header if not already set
if (!isset($user) && function_exists('auth')) {
    $user = auth('user');
}

$userName = $user['fullname'] ?? 'User';
$userEmail = $user['email'] ?? '';
$userInitials = strtoupper(substr($userName, 0, 1));
$userId = $user['id'] ?? null;

// Initialize notifications if not set
if (!isset($unreadCount) && $userId && function_exists('getUnreadNotificationCount')) {
    $unreadCount = getUnreadNotificationCount($userId, 'client');
}

if (!isset($recentNotifications) && $userId && function_exists('getUserNotifications')) {
    $recentNotifications = array_slice(getUserNotifications($userId, 'client', true) ?? [], 0, 4);
}

$pageTitle = $pageTitle ?? 'Dashboard';
?>

<header class="app-topbar">
    <div class="topbar-container">
        <!-- Left Section: Menu Toggle + Branding -->
        <div class="topbar-left">
            <button type="button" class="topbar-toggle button-toggle-menu">
                <iconify-icon icon="solar:hamburger-menu-outline"></iconify-icon>
            </button>
            <div class="topbar-brand">
                <span class="brand-label">User Portal</span>
                <h1 class="brand-title"><?= htmlspecialchars($pageTitle); ?></h1>
            </div>
        </div>

        <!-- Right Section: Actions -->
        <div class="topbar-right">
            <!-- Theme Toggle -->
            <button type="button" class="topbar-icon-btn" id="light-dark-mode" title="Toggle theme">
                <iconify-icon icon="solar:moon-outline" class="light-mode"></iconify-icon>
                <iconify-icon icon="solar:sun-2-outline" class="dark-mode"></iconify-icon>
            </button>

            <!-- Notifications -->
            <div class="dropdown">
                <button type="button" class="topbar-icon-btn" data-bs-toggle="dropdown" aria-expanded="false">
                    <iconify-icon icon="solar:bell-outline"></iconify-icon>
                    <?php if (!empty($unreadCount)): ?>
                        <span class="notification-badge"><?= $unreadCount > 9 ? '9+' : $unreadCount; ?></span>
                    <?php endif; ?>
                </button>
                <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                    <div class="notification-header">
                        <h6>Notifications</h6>
                        <?php if (!empty($unreadCount)): ?>
                            <span class="badge bg-primary"><?= $unreadCount; ?> New</span>
                        <?php endif; ?>
                    </div>
                    <div class="notification-body">
                        <?php if (!empty($recentNotifications)): ?>
                            <?php foreach ($recentNotifications as $notif): ?>
                                <a class="notification-item" href="notifications.php">
                                    <div class="notification-icon">
                                        <iconify-icon icon="solar:bell-outline"></iconify-icon>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-title">
                                            <?= htmlspecialchars($notif['title'] ?? 'Notification'); ?></p>
                                        <span class="notification-time">
                                            <?= isset($notif['created_at']) ? date('M d, H:i', strtotime($notif['created_at'])) : ''; ?>
                                        </span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="notification-empty">
                                <iconify-icon icon="solar:bell-off-outline"></iconify-icon>
                                <p>No new notifications</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($recentNotifications)): ?>
                        <div class="notification-footer">
                            <a href="notifications.php">View all notifications</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- User Menu -->
            <div class="dropdown">
                <button type="button" class="topbar-user-btn" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar"><?= $userInitials; ?></div>
                    <div class="user-info">
                        <span class="user-name"><?= htmlspecialchars($userName); ?></span>
                        <span class="user-role">Client</span>
                    </div>
                    <iconify-icon icon="solar:alt-arrow-down-outline" class="user-arrow"></iconify-icon>
                </button>
                <div class="dropdown-menu dropdown-menu-end user-dropdown">
                    <div class="user-dropdown-header">
                        <div class="user-avatar lg"><?= $userInitials; ?></div>
                        <div>
                            <h6><?= htmlspecialchars($userName); ?></h6>
                            <?php if ($userEmail): ?>
                                <small><?= htmlspecialchars($userEmail); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="profile.php">
                        <iconify-icon icon="solar:user-circle-outline"></iconify-icon>
                        <span>My Profile</span>
                    </a>
                    <a class="dropdown-item" href="cases.php">
                        <iconify-icon icon="solar:folder-with-files-outline"></iconify-icon>
                        <span>My Cases</span>
                    </a>
                    <a class="dropdown-item" href="../" target="_blank">
                        <iconify-icon icon="solar:global-outline"></iconify-icon>
                        <span>Visit Website</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="logout.php">
                        <iconify-icon icon="solar:logout-3-outline"></iconify-icon>
                        <span>Sign Out</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
<?php
require_once __DIR__ . '/../../config/case_helper.php';

$agentProfile = auth('agent');
$agentId = $agentProfile['id'] ?? null;
$agentName = $agentProfile['fullname'] ?? 'Agent User';
$agentEmail = $agentProfile['email'] ?? '';
$agentInitials = strtoupper(substr($agentName, 0, 1));

if (!isset($agentUnreadCount) && $agentId && function_exists('getUnreadNotificationCount')) {
    $agentUnreadCount = getUnreadNotificationCount($agentId, 'agent');
}

if (!isset($agentRecentNotifications) && $agentId && function_exists('getUserNotifications')) {
    $agentRecentNotifications = array_slice(getUserNotifications($agentId, 'agent', true) ?? [], 0, 4);
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
                <span class="brand-label">Agent Portal</span>
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
                    <?php if (!empty($agentUnreadCount)): ?>
                        <span class="notification-badge"><?= $agentUnreadCount > 9 ? '9+' : $agentUnreadCount; ?></span>
                    <?php endif; ?>
                </button>
                <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                    <div class="notification-header">
                        <h6>Notifications</h6>
                        <?php if (!empty($agentUnreadCount)): ?>
                            <span class="badge bg-primary"><?= $agentUnreadCount; ?> New</span>
                        <?php endif; ?>
                    </div>
                    <div class="notification-body">
                        <?php if (!empty($agentRecentNotifications)): ?>
                            <?php foreach ($agentRecentNotifications as $item): ?>
                                <a class="notification-item" href="notifications.php">
                                    <div class="notification-icon">
                                        <iconify-icon icon="solar:bell-outline"></iconify-icon>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-title"><?= htmlspecialchars($item['title'] ?? 'Notification'); ?>
                                        </p>
                                        <span class="notification-time">
                                            <?= isset($item['created_at']) ? date('M d, H:i', strtotime($item['created_at'])) : ''; ?>
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
                    <?php if (!empty($agentRecentNotifications)): ?>
                        <div class="notification-footer">
                            <a href="notifications.php">View all notifications</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- User Menu -->
            <div class="dropdown">
                <button type="button" class="topbar-user-btn" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar"><?= $agentInitials; ?></div>
                    <div class="user-info">
                        <span class="user-name"><?= htmlspecialchars($agentName); ?></span>
                        <span class="user-role">Agent</span>
                    </div>
                    <iconify-icon icon="solar:alt-arrow-down-outline" class="user-arrow"></iconify-icon>
                </button>
                <div class="dropdown-menu dropdown-menu-end user-dropdown">
                    <div class="user-dropdown-header">
                        <div class="user-avatar lg"><?= $agentInitials; ?></div>
                        <div>
                            <h6><?= htmlspecialchars($agentName); ?></h6>
                            <?php if ($agentEmail): ?>
                                <small><?= htmlspecialchars($agentEmail); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="profile.php">
                        <iconify-icon icon="solar:user-circle-outline"></iconify-icon>
                        <span>My Profile</span>
                    </a>
                    <a class="dropdown-item" href="commissions.php">
                        <iconify-icon icon="solar:wallet-money-outline"></iconify-icon>
                        <span>Commissions</span>
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
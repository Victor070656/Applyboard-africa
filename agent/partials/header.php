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
    <div class="container-fluid">
        <div class="navbar-header">
            <div class="d-flex align-items-center gap-3">
                <div class="topbar-item">
                    <button type="button" class="button-toggle-menu topbar-button">
                        <iconify-icon icon="solar:hamburger-menu-outline" class="fs-24 align-middle"></iconify-icon>
                    </button>
                </div>

                <div class="d-none d-md-flex flex-column">
                    <span class="fw-semibold text-muted"
                        style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.08em;">Agent Panel</span>
                    <span class="fw-semibold" style="font-size: 16px;"><?= htmlspecialchars($pageTitle); ?></span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <div class="dropdown topbar-item">
                    <button type="button" class="topbar-button position-relative" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <iconify-icon icon="solar:bell-outline" class="fs-22 align-middle"></iconify-icon>
                        <?php if (!empty($agentUnreadCount)): ?>
                            <span class="badge bg-danger rounded-pill position-absolute top-0 start-100">
                                <?= $agentUnreadCount > 9 ? '9+' : $agentUnreadCount; ?>
                            </span>
                        <?php endif; ?>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end"
                        style="width: 320px; max-height: 420px; overflow-y: auto;">
                        <div class="dropdown-header d-flex justify-content-between align-items-center">
                            <span>Notifications</span>
                            <?php if (!empty($agentUnreadCount)): ?>
                                <span class="badge bg-primary-subtle text-primary"><?= $agentUnreadCount; ?> New</span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($agentRecentNotifications)): ?>
                            <?php foreach ($agentRecentNotifications as $item): ?>
                                <a class="dropdown-item" href="notifications.php">
                                    <div class="d-flex gap-2">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                                                style="width: 38px; height: 38px;">
                                                <iconify-icon icon="solar:bell-outline" class="fs-16"></iconify-icon>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1 fw-semibold" style="font-size: 14px;">
                                                <?= htmlspecialchars($item['title'] ?? 'Notification'); ?>
                                            </p>
                                            <small class="text-muted">
                                                <?= isset($item['created_at']) ? date('M d, H:i', strtotime($item['created_at'])) : ''; ?>
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center text-primary fw-semibold" href="notifications.php">View all
                                notifications</a>
                        <?php else: ?>
                            <div class="dropdown-item text-center py-4">
                                <iconify-icon icon="solar:bell-off-outline"
                                    class="fs-24 text-muted mb-2 d-block"></iconify-icon>
                                <span class="text-muted small">No new notifications</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="topbar-item d-none d-sm-block">
                    <button type="button" class="topbar-button" id="light-dark-mode">
                        <iconify-icon icon="solar:moon-outline" class="fs-22 align-middle light-mode"></iconify-icon>
                        <iconify-icon icon="solar:sun-2-outline" class="fs-22 align-middle dark-mode"></iconify-icon>
                    </button>
                </div>

                <div class="dropdown topbar-item">
                    <a type="button" class="topbar-button d-flex align-items-center gap-2"
                        id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="avatar-placeholder"><?= $agentInitials; ?></div>
                        <div class="d-none d-md-flex flex-column text-start">
                            <span class="fw-semibold"
                                style="font-size: 14px;"><?= htmlspecialchars($agentName); ?></span>
                            <small class="text-muted" style="font-size: 12px;">Agent</small>
                        </div>
                        <iconify-icon icon="solar:alt-arrow-down-outline"
                            class="fs-16 d-none d-md-block"></iconify-icon>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="dropdown-header">
                            <h6 class="mb-0"><?= htmlspecialchars($agentName); ?></h6>
                            <?php if ($agentEmail): ?>
                                <small class="text-muted"><?= htmlspecialchars($agentEmail); ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="profile.php">
                            <iconify-icon icon="solar:user-circle-outline"
                                class="align-middle me-2 fs-18"></iconify-icon>
                            <span class="align-middle">My Profile</span>
                        </a>
                        <a class="dropdown-item" href="commissions.php">
                            <iconify-icon icon="solar:wallet-money-outline"
                                class="align-middle me-2 fs-18"></iconify-icon>
                            <span class="align-middle">Commissions</span>
                        </a>
                        <a class="dropdown-item" href="../" target="_blank">
                            <iconify-icon icon="solar:global-outline" class="align-middle me-2 fs-18"></iconify-icon>
                            <span class="align-middle">Visit Website</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="logout.php">
                            <iconify-icon icon="solar:logout-3-outline" class="align-middle me-2 fs-18"></iconify-icon>
                            <span class="align-middle">Sign Out</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
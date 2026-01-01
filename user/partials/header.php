<header class="app-topbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <div class="d-flex align-items-center gap-3">
                <!-- Menu Toggle Button -->
                <div class="topbar-item">
                    <button type="button" class="button-toggle-menu topbar-button">
                        <iconify-icon icon="solar:hamburger-menu-outline" class="fs-24 align-middle"></iconify-icon>
                    </button>
                </div>

                <!-- Page Title -->
                <div class="d-none d-md-flex flex-column">
                    <span class="fw-semibold text-muted"
                        style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.08em;">User Portal</span>
                    <span class="fw-semibold"
                        style="font-size: 16px;"><?= htmlspecialchars($pageTitle ?? 'Dashboard'); ?></span>
                </div>
                <!-- Mobile Title -->
                <div class="d-block d-md-none">
                    <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($pageTitle ?? 'Dashboard'); ?></h6>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- Notifications -->
                <div class="dropdown topbar-item">
                    <button type="button" class="topbar-button position-relative" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <iconify-icon icon="solar:bell-outline" class="fs-22 align-middle"></iconify-icon>
                        <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                style="font-size: 10px;">
                                <?= $unreadCount > 9 ? '9+' : $unreadCount ?>
                            </span>
                        <?php endif; ?>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end"
                        style="width: 320px; max-height: 400px; overflow-y: auto;">
                        <div class="dropdown-header d-flex justify-content-between align-items-center">
                            <span>Notifications</span>
                            <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                                <span class="badge bg-primary-subtle text-primary"><?= $unreadCount ?> New</span>
                            <?php endif; ?>
                        </div>
                        <?php if (isset($recentNotifications) && !empty($recentNotifications)): ?>
                            <?php foreach ($recentNotifications as $notif): ?>
                                <a class="dropdown-item py-3" href="notifications.php">
                                    <div class="d-flex gap-2">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                                                style="width: 36px; height: 36px;">
                                                <iconify-icon icon="solar:bell-outline" class="fs-16"></iconify-icon>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1 fw-medium text-truncate" style="max-width: 200px;">
                                                <?= htmlspecialchars($notif['title']) ?>
                                            </p>
                                            <small
                                                class="text-muted"><?= date('M d, H:i', strtotime($notif['created_at'])) ?></small>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center text-primary py-2" href="notifications.php">
                                View All Notifications
                            </a>
                        <?php else: ?>
                            <div class="dropdown-item text-center py-4">
                                <iconify-icon icon="solar:bell-off-outline"
                                    class="fs-24 text-muted mb-2 d-block"></iconify-icon>
                                <p class="text-muted mb-0 small">No new notifications</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Theme Color (Light/Dark) -->
                <div class="topbar-item d-none d-sm-block">
                    <button type="button" class="topbar-button" id="light-dark-mode">
                        <iconify-icon icon="solar:moon-outline" class="fs-22 align-middle light-mode"></iconify-icon>
                        <iconify-icon icon="solar:sun-2-outline" class="fs-22 align-middle dark-mode"></iconify-icon>
                    </button>
                </div>

                <!-- User Dropdown -->
                <div class="dropdown topbar-item">
                    <a type="button" class="topbar-button d-flex align-items-center gap-2"
                        id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="avatar-placeholder">
                            <?= isset($user['fullname']) ? strtoupper(substr($user['fullname'], 0, 1)) : 'U' ?></div>
                        <div class="d-none d-md-flex flex-column text-start">
                            <span class="fw-semibold"
                                style="font-size: 14px;"><?= htmlspecialchars($user['fullname'] ?? 'User') ?></span>
                            <small class="text-muted" style="font-size: 12px;">Client</small>
                        </div>
                        <iconify-icon icon="solar:alt-arrow-down-outline"
                            class="fs-16 d-none d-md-block"></iconify-icon>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="dropdown-header">
                            <h6 class="mb-0"><?= htmlspecialchars($user['fullname'] ?? 'User') ?></h6>
                            <small class="text-muted"><?= htmlspecialchars($user['email'] ?? '') ?></small>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="profile.php">
                            <iconify-icon icon="solar:user-circle-outline"
                                class="align-middle me-2 fs-18"></iconify-icon>
                            <span class="align-middle">My Profile</span>
                        </a>
                        <a class="dropdown-item" href="cases.php">
                            <iconify-icon icon="solar:folder-with-files-outline"
                                class="align-middle me-2 fs-18"></iconify-icon>
                            <span class="align-middle">My Cases</span>
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
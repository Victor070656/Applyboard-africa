<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="app-sidebar">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="./" class="logo-dark">
            <img src="../images/favicon.png" class="logo-sm" alt="logo sm">
            <img src="../images/logo-2.png" class="logo-lg" alt="logo dark">
        </a>
        <a href="./" class="logo-light">
            <img src="../images/favicon.png" class="logo-sm" alt="logo sm">
            <img src="../images/logo-2.png" class="logo-lg" alt="logo light">
        </a>
    </div>

    <div class="scrollbar" data-simplebar>
        <ul class="navbar-nav" id="navbar-nav">

            <li class="menu-title">Main Menu</li>

            <li class="nav-item <?= $currentPage === 'index.php' ? 'active' : '' ?>">
                <a class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>" href="./">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:home-2-outline"></iconify-icon>
                    </span>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <li class="nav-item <?= $currentPage === 'new_application.php' ? 'active' : '' ?>">
                <a class="nav-link <?= $currentPage === 'new_application.php' ? 'active' : '' ?>" href="new_application.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:add-circle-outline"></iconify-icon>
                    </span>
                    <span class="nav-text">New Application</span>
                </a>
            </li>

            <li class="menu-title">My Account</li>

            <li class="nav-item <?= $currentPage === 'cases.php' ? 'active' : '' ?>">
                <a class="nav-link <?= $currentPage === 'cases.php' ? 'active' : '' ?>" href="cases.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:folder-with-files-outline"></iconify-icon>
                    </span>
                    <span class="nav-text">My Cases</span>
                </a>
            </li>

            <li class="nav-item <?= $currentPage === 'documents.php' ? 'active' : '' ?>">
                <a class="nav-link <?= $currentPage === 'documents.php' ? 'active' : '' ?>" href="documents.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:document-text-outline"></iconify-icon>
                    </span>
                    <span class="nav-text">Documents</span>
                </a>
            </li>

            <li class="nav-item <?= $currentPage === 'payments.php' ? 'active' : '' ?>">
                <a class="nav-link <?= $currentPage === 'payments.php' ? 'active' : '' ?>" href="payments.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:card-outline"></iconify-icon>
                    </span>
                    <span class="nav-text">Payments</span>
                </a>
            </li>

            <li class="nav-item <?= $currentPage === 'notifications.php' ? 'active' : '' ?>">
                <a class="nav-link <?= $currentPage === 'notifications.php' ? 'active' : '' ?>" href="notifications.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:bell-outline"></iconify-icon>
                    </span>
                    <span class="nav-text">Notifications</span>
                    <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                    <span class="badge bg-danger rounded-pill ms-auto"><?= $unreadCount ?></span>
                    <?php endif; ?>
                </a>
            </li>

            <li class="menu-title">Settings</li>

            <li class="nav-item <?= $currentPage === 'profile.php' ? 'active' : '' ?>">
                <a class="nav-link <?= $currentPage === 'profile.php' ? 'active' : '' ?>" href="profile.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:user-circle-outline"></iconify-icon>
                    </span>
                    <span class="nav-text">My Profile</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="../" target="_blank">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:global-outline"></iconify-icon>
                    </span>
                    <span class="nav-text">Visit Website</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-danger" href="logout.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:logout-3-outline"></iconify-icon>
                    </span>
                    <span class="nav-text">Sign Out</span>
                </a>
            </li>

        </ul>
    </div>
</div>
<div class="app-sidebar">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="./" class="logo-dark">
            <img src="../images/favicon.png" style="height: 30px; border-radius: 7px;" class="logo-sm" alt="logo sm">
            <img src="../images/logo-2.png" style="height: 40px; border-radius: 7px;" class="logo-lg" alt="logo dark">
        </a>

        <a href="./" class="logo-light">
            <img src="../images/favicon.png" style="height: 30px; border-radius: 7px;" class="logo-sm" alt="logo sm">
            <img src="../images/logo-2.png" style="height: 40px; border-radius: 7px;" class="logo-lg" alt="logo light">
        </a>
    </div>

    <div class="scrollbar" data-simplebar>

        <ul class="navbar-nav" id="navbar-nav">

            <li class="menu-title">Agent Panel</li>

            <li class="nav-item">
                <a class="nav-link" href="./">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:widget-2-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Dashboard </span>
                </a>
            </li>

            <?php if (auth('agent')['status'] == 'pending'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="verification.php">
                        <span class="nav-icon">
                            <iconify-icon icon="solar:shield-check-outline"></iconify-icon>
                        </span>
                        <span class="nav-text text-danger"> Verify Account </span>
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-item">
                <a class="nav-link" href="clients.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:users-group-two-rounded-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> My Clients </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="inquiries.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:question-circle-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Inquiries </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="cases.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:folder-with-files-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Cases </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="commissions.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:wallet-money-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Commissions </span>
                </a>
            </li>

            <li class="menu-title">Account...</li>

            <li class="nav-item">
                <a class="nav-link" href="notifications.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:bell-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Notifications </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="profile.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:user-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> My Profile </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:logout-3-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Logout </span>
                </a>
            </li>

        </ul>
    </div>
</div>
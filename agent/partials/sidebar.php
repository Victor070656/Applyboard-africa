<?php
$agentAccount = auth('agent');
$agentStatus = $agentAccount['status'] ?? 'pending';
$agentName = $agentAccount['fullname'] ?? 'Agent';
$currentAgentPage = basename($_SERVER['SCRIPT_NAME']);

if (!function_exists('isAgentNavActive')) {
    function isAgentNavActive($current, $pages)
    {
        return in_array($current, (array) $pages, true) ? 'active' : '';
    }
}
?>

<div class="app-sidebar">
    <div class="logo-box">
        <a href="./" class="logo-link d-flex align-items-center gap-2">
            <img src="../images/favicon.png" class="logo-sm" style="height: 32px; border-radius: 8px;"
                alt="ApplyBoard Africa">
            <div class="logo-text d-flex flex-column">
                <span class="text-white fw-semibold">ApplyBoard Africa</span>
                <small class="text-white-50">Agent Workspace</small>
            </div>
        </a>
    </div>

    <div class="scrollbar" data-simplebar>
        <ul class="navbar-nav" id="navbar-nav">
            <li class="menu-title">Overview</li>
            <li class="nav-item">
                <a class="nav-link <?= isAgentNavActive($currentAgentPage, 'index.php'); ?>" href="./">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:widget-2-outline"></iconify-icon>
                    </span>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <?php if ($agentStatus === 'pending'): ?>
                <li class="nav-item">
                    <a class="nav-link <?= isAgentNavActive($currentAgentPage, 'verification.php'); ?>"
                        href="verification.php">
                        <span class="nav-icon">
                            <iconify-icon icon="solar:shield-check-outline"></iconify-icon>
                        </span>
                        <span class="nav-text text-warning">Verify Account</span>
                    </a>
                </li>
            <?php endif; ?>

            <li class="menu-title">Clients &amp; Cases</li>
            <li class="nav-item">
                <a class="nav-link <?= isAgentNavActive($currentAgentPage, 'clients.php'); ?>" href="clients.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:users-group-two-rounded-outline"></iconify-icon>
                    </span>
                    <span class="nav-text">My Clients</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isAgentNavActive($currentAgentPage, 'inquiries.php'); ?>" href="inquiries.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:question-circle-outline"></iconify-icon>
                    </span>
                    <span class="nav-text">Inquiries</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isAgentNavActive($currentAgentPage, ['cases.php', 'case-details.php']); ?>"
                    href="cases.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:folder-with-files-outline"></iconify-icon>
                    </span>
                    <span class="nav-text">Case Files</span>
                </a>
            </li>

            <li class="menu-title">Revenue</li>
            <li class="nav-item">
                <a class="nav-link <?= isAgentNavActive($currentAgentPage, 'commissions.php'); ?>"
                    href="commissions.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:wallet-money-outline"></iconify-icon>
                    </span>
                    <span class="nav-text">Commissions</span>
                </a>
            </li>

            <li class="menu-title">Account</li>
            <li class="nav-item">
                <a class="nav-link <?= isAgentNavActive($currentAgentPage, 'notifications.php'); ?>"
                    href="notifications.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:bell-outline"></iconify-icon>
                    </span>
                    <span class="nav-text">Notifications</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isAgentNavActive($currentAgentPage, 'profile.php'); ?>" href="profile.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:user-outline"></iconify-icon>
                    </span>
                    <span class="nav-text">My Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:logout-3-outline"></iconify-icon>
                    </span>
                    <span class="nav-text">Sign Out</span>
                </a>
            </li>
        </ul>
    </div>
</div>
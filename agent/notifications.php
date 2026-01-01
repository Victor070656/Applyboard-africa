<?php
include "../config/config.php";
include "../config/case_helper.php";

if (!isLoggedIn('agent')) {
    header("Location: login.php");
    exit;
}

$agent = auth('agent');
$agent_id = $agent['id'];
$pageTitle = 'Notifications';

// Mark notification as read
if (isset($_GET['mark_read']) && isset($_GET['id'])) {
    $notificationId = intval($_GET['id']);
    markNotificationRead($notificationId);
    header("Location: notifications.php");
    exit;
}

// Mark all as read
if (isset($_GET['mark_all_read'])) {
    markAllNotificationsRead($agent_id, 'agent');
    header("Location: notifications.php");
    exit;
}

// Get all notifications
$notifications = getUserNotifications($agent_id, 'agent', false);
$unreadCount = getUnreadNotificationCount($agent_id, 'agent');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Notifications | Agent Portal - ApplyBoard Africa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="theme-color" content="#0F4C75">

    <link rel="shortcut icon" href="../images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/dashboard.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/config.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>

    <style>
        .notification-item {
            border-left: 4px solid transparent;
            transition: all 0.2s;
        }

        .notification-item:hover {
            background-color: var(--bs-tertiary-bg);
        }

        .notification-item.unread {
            border-left-color: var(--bs-primary);
            background-color: var(--bs-primary-bg-subtle);
        }

        .notification-icon {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
    </style>
</head>

<body>
    <div class="app-wrapper">
        <?php include "partials/header.php"; ?>
        <?php include "partials/sidebar.php"; ?>

        <div class="page-content">
            <div class="container-fluid">

                <!-- Page Title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">Notifications</h4>
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Notifications</li>
                                </ol>
                            </div>
                            <?php if ($unreadCount > 0): ?>
                                <a href="?mark_all_read=1" class="btn btn-sm btn-outline-primary">
                                    <iconify-icon icon="solar:check-read-outline"></iconify-icon> Mark All Read
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <?php if ($unreadCount > 0): ?>
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-primary d-flex align-items-center mb-0">
                                <iconify-icon icon="solar:bell-bing-outline" class="fs-20 me-2"></iconify-icon>
                                You have <strong class="mx-1"><?= $unreadCount ?></strong> unread
                                notification<?= $unreadCount > 1 ? 's' : '' ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">All Notifications</h5>
                            </div>
                            <div class="card-body p-0">
                                <?php if (empty($notifications)): ?>
                                    <div class="text-center py-5">
                                        <div
                                            class="quick-action-icon bg-secondary bg-opacity-10 text-secondary mx-auto mb-3">
                                            <iconify-icon icon="solar:bell-off-outline"></iconify-icon>
                                        </div>
                                        <h5>No Notifications Yet</h5>
                                        <p class="text-muted mb-0">You'll be notified of important updates here</p>
                                    </div>
                                <?php else: ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($notifications as $notification): ?>
                                            <div
                                                class="list-group-item notification-item <?= $notification['is_read'] ? '' : 'unread' ?> p-3">
                                                <div class="d-flex align-items-start">
                                                    <div
                                                        class="notification-icon bg-<?= getNotificationIconColor($notification['type']) ?> bg-opacity-10 me-3">
                                                        <iconify-icon icon="<?= getNotificationIcon($notification['type']) ?>"
                                                            class="fs-20 text-<?= getNotificationIconColor($notification['type']) ?>"></iconify-icon>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1"><?= htmlspecialchars($notification['title']) ?></h6>
                                                        <p class="text-muted mb-1">
                                                            <?= htmlspecialchars($notification['message']) ?>
                                                        </p>
                                                        <small class="text-muted">
                                                            <iconify-icon icon="solar:clock-circle-outline"></iconify-icon>
                                                            <?= getTimeAgo($notification['created_at']) ?>
                                                        </small>
                                                    </div>
                                                    <?php if (!$notification['is_read']): ?>
                                                        <a href="?mark_read=1&id=<?= $notification['id'] ?>"
                                                            class="btn btn-sm btn-light ms-2" title="Mark as read">
                                                            <iconify-icon icon="solar:check-outline"></iconify-icon>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <footer class="footer card mb-0 rounded-0 justify-content-center align-items-center">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 text-center">
                            <p class="mb-0">
                                <script>document.write(new Date().getFullYear())</script> &copy; ApplyBoard Africa Ltd.
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>

</html>

<?php
// Helper functions for notification display
function getNotificationIcon($type)
{
    $icons = [
        'case_created' => 'solar:folder-add-outline',
        'case_updated' => 'solar:folder-check-outline',
        'stage_changed' => 'solar:arrow-right-outline',
        'document_uploaded' => 'solar:document-add-outline',
        'document_verified' => 'solar:document-check-outline',
        'commission_earned' => 'solar:wallet-money-outline',
        'commission_paid' => 'solar:check-circle-outline',
        'client_registered' => 'solar:user-plus-outline',
        'inquiry_received' => 'solar:chat-round-line-outline',
        'system' => 'solar:bell-outline'
    ];
    return isset($icons[$type]) ? $icons[$type] : 'solar:bell-outline';
}

function getNotificationIconColor($type)
{
    $colors = [
        'case_created' => 'primary',
        'case_updated' => 'info',
        'stage_changed' => 'warning',
        'document_uploaded' => 'secondary',
        'document_verified' => 'success',
        'commission_earned' => 'success',
        'commission_paid' => 'success',
        'client_registered' => 'primary',
        'inquiry_received' => 'info',
        'system' => 'secondary'
    ];
    return isset($colors[$type]) ? $colors[$type] : 'secondary';
}

function getTimeAgo($datetime)
{
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60)
        return 'Just now';
    if ($diff < 3600)
        return floor($diff / 60) . ' min ago';
    if ($diff < 86400)
        return floor($diff / 3600) . ' hours ago';
    if ($diff < 604800)
        return floor($diff / 86400) . ' days ago';
    return date('M d, Y', $timestamp);
}
?>
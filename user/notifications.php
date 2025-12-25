<?php
include "../config/config.php";
include "../config/case_helper.php";
if (!isLoggedIn('user')) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

$user = auth('user');
$userId = $user['id'];

// Handle mark as read
if (isset($_GET['mark_read'])) {
    $notifId = intval($_GET['mark_read']);
    markNotificationRead($notifId);
    header("Location: notifications.php");
    exit;
}

// Handle mark all as read
if (isset($_POST['mark_all_read'])) {
    markAllNotificationsRead($userId, 'client');
}

// Get notifications
$notifications = getUserNotifications($userId, 'client');
$unreadCount = getUnreadNotificationCount($userId, 'client');

function getNotificationIcon($type) {
    $icons = [
        'success' => 'solar:check-circle-outline',
        'info' => 'solar:info-circle-outline',
        'warning' => 'solar:danger-triangle-outline',
        'danger' => 'solar:danger-circle-outline',
        'case' => 'solar:folder-with-files-outline',
        'document' => 'solar:document-text-outline',
        'message' => 'solar:letter-outline',
        'payment' => 'solar:wallet-money-outline'
    ];
    return isset($icons[$type]) ? $icons[$type] : 'solar:bell-outline';
}

function getNotificationBadge($type) {
    $badges = [
        'success' => 'bg-success',
        'info' => 'bg-info',
        'warning' => 'bg-warning',
        'danger' => 'bg-danger',
        'case' => 'bg-primary',
        'document' => 'bg-secondary',
        'message' => 'bg-info',
        'payment' => 'bg-success'
    ];
    return isset($badges[$type]) ? $badges[$type] : 'bg-secondary';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <!-- Title Meta -->
     <meta charset="utf-8" />
     <title>ApplyBoard Africa Ltd User || Notifications</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <meta name="robots" content="index, follow" />
     <meta name="theme-color" content="#ffffff">

     <!-- App favicon -->
     <link rel="shortcut icon" href="../images/favicon.png">

     <!-- Google Font Family link -->
     <link rel="preconnect" href="https://fonts.googleapis.com/index.html">
     <link rel="preconnect" href="https://fonts.gstatic.com/index.html" crossorigin>
     <link href="https://fonts.googleapis.com/css2c4ad.css?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">

     <!-- Vendor css -->
     <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />

     <!-- Icons css -->
     <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

     <!-- App css -->
     <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />

     <!-- Theme Config js -->
     <script src="assets/js/config.js"></script>
     <!-- Iconify -->
     <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>

     <style>
        .notification-item {
            border-left: 4px solid transparent;
            transition: all 0.2s;
        }
        .notification-item.unread {
            background: rgba(15, 76, 117, 0.05);
            border-left-color: #0F4C75;
        }
        .notification-item:hover {
            background: rgba(15, 76, 117, 0.08);
        }
        .notification-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 24px;
        }
     </style>
</head>

<body>

     <!-- START Wrapper -->
     <div class="app-wrapper">

          <!-- Topbar Start -->
          <?php include "partials/header.php"; ?>
          <!-- Topbar End -->

          <!-- App Menu Start -->
          <?php include "partials/sidebar.php"; ?>
          <!-- App Menu End -->

          <!-- Start right Content here -->
          <div class="page-content">
               <!-- Start Container Fluid -->
               <div class="container-fluid">

                    <!-- ========== Page Title Start ========== -->
                    <div class="row">
                         <div class="col-12">
                              <div class="page-title-box d-flex justify-content-between align-items-center">
                                   <div>
                                       <h4 class="mb-0">Notifications</h4>
                                       <ol class="breadcrumb mb-0">
                                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                            <li class="breadcrumb-item active">Notifications</li>
                                       </ol>
                                   </div>
                                   <?php if ($unreadCount > 0): ?>
                                   <form method="POST">
                                       <button type="submit" name="mark_all_read" class="btn btn-outline-primary btn-sm">
                                           <iconify-icon icon="solar:check-read-outline"></iconify-icon> Mark All as Read
                                       </button>
                                   </form>
                                   <?php endif; ?>
                              </div>
                         </div>
                    </div>
                    <!-- ========== Page Title End ========== -->

                    <!-- Notification Stats -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="notification-icon bg-primary bg-opacity-10 text-primary me-3">
                                            <iconify-icon icon="solar:bell-outline"></iconify-icon>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Total</h6>
                                            <h4 class="mb-0"><?= count($notifications) ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="notification-icon bg-success bg-opacity-10 text-success me-3">
                                            <iconify-icon icon="solar:check-circle-outline"></iconify-icon>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Read</h6>
                                            <h4 class="mb-0"><?= count($notifications) - $unreadCount ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="notification-icon bg-warning bg-opacity-10 text-warning me-3">
                                            <iconify-icon icon="solar:bell-minimalistic-outline"></iconify-icon>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Unread</h6>
                                            <h4 class="mb-0"><?= $unreadCount ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="notification-icon bg-info bg-opacity-10 text-info me-3">
                                            <iconify-icon icon="solar:calendar-outline"></iconify-icon>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">This Week</h6>
                                            <h4 class="mb-0">
                                                <?php
                                                $weekCount = 0;
                                                $weekAgo = date('Y-m-d H:i:s', strtotime('-1 week'));
                                                foreach ($notifications as $n) {
                                                    if ($n['created_at'] > $weekAgo) $weekCount++;
                                                }
                                                echo $weekCount;
                                                ?>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications List -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">All Notifications</h5>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Filter
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="notifications.php">All Notifications</a></li>
                                            <li><a class="dropdown-item" href="notifications.php?filter=unread">Unread Only</a></li>
                                            <li><a class="dropdown-item" href="notifications.php?filter=case">Cases</a></li>
                                            <li><a class="dropdown-item" href="notifications.php?filter=document">Documents</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <?php if (empty($notifications)): ?>
                                        <div class="text-center py-5">
                                            <div class="notification-icon bg-secondary bg-opacity-10 text-secondary mx-auto mb-3">
                                                <iconify-icon icon="solar:bell-off-outline"></iconify-icon>
                                            </div>
                                            <h5>No Notifications</h5>
                                            <p class="text-muted">You don't have any notifications yet.</p>
                                            <a href="index.php" class="btn btn-primary btn-sm">Back to Dashboard</a>
                                        </div>
                                    <?php else: ?>
                                        <div class="list-group list-group-flush">
                                            <?php foreach ($notifications as $notif): ?>
                                                <?php
                                                $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
                                                if ($filter === 'unread' && $notif['is_read']) continue;
                                                if ($filter === 'case' && stripos($notif['title'], 'case') === false && stripos($notif['message'], 'case') === false) continue;
                                                if ($filter === 'document' && stripos($notif['title'], 'document') === false && stripos($notif['message'], 'document') === false) continue;
                                                ?>
                                                <div class="list-group-item notification-item <?= !$notif['is_read'] ? 'unread' : '' ?>">
                                                    <div class="d-flex align-items-start">
                                                        <div class="notification-icon <?= getNotificationBadge($notif['type']) ?> bg-opacity-10 text-<?= $notif['type'] === 'info' ? 'primary' : ($notif['type'] === 'success' ? 'success' : ($notif['type'] === 'danger' ? 'danger' : 'warning')) ?> me-3 flex-shrink-0">
                                                            <iconify-icon icon="<?= getNotificationIcon($notif['type']) ?>"></iconify-icon>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex justify-content-between align-items-start">
                                                                <div>
                                                                    <h6 class="mb-1 <?= !$notif['is_read'] ? 'fw-bold' : '' ?>">
                                                                        <?= htmlspecialchars($notif['title']) ?>
                                                                    </h6>
                                                                    <p class="mb-1 text-muted"><?= htmlspecialchars($notif['message']) ?></p>
                                                                    <small class="text-muted">
                                                                        <iconify-icon icon="solar:clock-circle-outline"></iconify-icon>
                                                                        <?= date('d M Y, g:i A', strtotime($notif['created_at'])) ?>
                                                                    </small>
                                                                </div>
                                                                <?php if (!$notif['is_read']): ?>
                                                                <a href="?mark_read=<?= $notif['id'] ?>" class="btn btn-sm btn-outline-primary ms-2" title="Mark as read">
                                                                    <iconify-icon icon="solar:check-read-outline"></iconify-icon>
                                                                </a>
                                                                <?php endif; ?>
                                                            </div>
                                                            <?php if ($notif['link']): ?>
                                                                <a href="<?= htmlspecialchars($notif['link']) ?>" class="btn btn-sm btn-link ps-0">
                                                                    View Details <iconify-icon icon="solar:alt-arrow-right-outline"></iconify-icon>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
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
               <!-- End Container Fluid -->

               <!-- Footer Start -->
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
               <!-- Footer End -->

          </div>
          <!-- End Page Content -->

     </div>
     <!-- END Wrapper -->

     <!-- Vendor Javascript -->
     <script src="assets/js/vendor.min.js"></script>

     <!-- App Javascript -->
     <script src="assets/js/app.js"></script>

</body>

</html>

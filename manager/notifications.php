<?php
include "../config/config.php";
if (!isset($_SESSION['sdtravels_manager'])) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

$manager = $_SESSION['sdtravels_manager'];

// Helper functions for notification display
function getNotificationTypeColor($type)
{
     $colors = [
          'info' => 'primary',
          'success' => 'success',
          'warning' => 'warning',
          'error' => 'danger',
          'alert' => 'danger',
          'case_update' => 'info',
          'commission' => 'success',
          'client_registered' => 'primary',
          'agent_verified' => 'success',
          'new_inquiry' => 'warning'
     ];
     return $colors[$type] ?? 'secondary';
}

function getNotificationIcon($type)
{
     $icons = [
          'info' => 'solar:info-circle-outline',
          'success' => 'solar:check-circle-outline',
          'warning' => 'solar:danger-triangle-outline',
          'error' => 'solar:close-circle-outline',
          'alert' => 'solar:bell-outline',
          'case_update' => 'solar:folder-with-files-outline',
          'commission' => 'solar:wallet-money-outline',
          'client_registered' => 'solar:user-plus-rounded-outline',
          'agent_verified' => 'solar:shield-check-outline',
          'new_inquiry' => 'solar:chat-round-line-broken'
     ];
     return $icons[$type] ?? 'solar:bell-outline';
}

// Handle notification creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_notification'])) {
     $title = mysqli_real_escape_string($conn, $_POST['title']);
     $message = mysqli_real_escape_string($conn, $_POST['message']);
     $type = mysqli_real_escape_string($conn, $_POST['type']);
     $targetType = mysqli_real_escape_string($conn, $_POST['target_type']); // all, agents, clients
     $link = mysqli_real_escape_string($conn, $_POST['link'] ?? '');

     // Send notification based on target type
     if ($targetType === 'agents' || $targetType === 'all') {
          // Send to all agents
          $agents = mysqli_query($conn, "SELECT id FROM agents WHERE status = 'verified'");
          while ($agent = mysqli_fetch_assoc($agents)) {
               mysqli_query($conn, "
                INSERT INTO notifications (user_id, user_type, title, message, type, link, created_at)
                VALUES ('{$agent['id']}', 'agent', '$title', '$message', '$type', '$link', NOW())
            ");
          }
     }

     if ($targetType === 'clients' || $targetType === 'all') {
          // Send to all clients (users)
          $clients = mysqli_query($conn, "SELECT id FROM users");
          while ($client = mysqli_fetch_assoc($clients)) {
               mysqli_query($conn, "
                INSERT INTO notifications (user_id, user_type, title, message, type, link, created_at)
                VALUES ('{$client['id']}', 'client', '$title', '$message', '$type', '$link', NOW())
            ");
          }
     }

     echo "<script>alert('Notification sent successfully!'); location.href = 'notifications.php';</script>";
}

// Handle mark all as read
if (isset($_POST['mark_all_read'])) {
     mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE user_type = 'manager' OR user_type = 'admin'");
}

// Handle delete notification
if (isset($_GET['delete'])) {
     $notifId = intval($_GET['delete']);
     mysqli_query($conn, "DELETE FROM notifications WHERE id = '$notifId'");
     header("Location: notifications.php");
     exit;
}

// Get notifications
$getNotifications = mysqli_query($conn, "
    SELECT * FROM notifications
    WHERE user_type IN ('manager', 'admin') OR user_type = 'all'
    ORDER BY created_at DESC
    LIMIT 50
");

$unreadCount = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as c FROM notifications
    WHERE (user_type IN ('manager', 'admin') OR user_type = 'all') AND is_read = 0
"))['c'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="utf-8" />
     <title>Notifications | ApplyBoard Africa</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <link rel="shortcut icon" href="../images/favicon.png">
     <meta name="theme-color" content="#1e3a5f">

     <!-- Google Fonts - Inter -->
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
     <link href="https://fonts.googleapis.com/css2c4ad.css?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
          rel="stylesheet">
     <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />

     <!-- Custom Dashboard css (mobile fixes) -->
     <link href="assets/css/dashboard.css" rel="stylesheet" type="text/css" />
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
     </style>
</head>

<body>
     <div class="app-wrapper">
          <?php include "partials/header.php"; ?>
          <?php include "partials/sidebar.php"; ?>

          <div class="page-content">
               <div class="container-fluid">

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
                                             <button type="submit" name="mark_all_read"
                                                  class="btn btn-outline-primary btn-sm">
                                                  <iconify-icon icon="solar:check-read-outline"></iconify-icon> Mark All as
                                                  Read
                                             </button>
                                        </form>
                                   <?php endif; ?>
                              </div>
                         </div>
                    </div>

                    <!-- Stats -->
                    <div class="row g-3 mb-4">
                         <div class="col-6 col-lg-4">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">Total</p>
                                                  <h3 class="stat-value mb-1"><?= mysqli_num_rows($getNotifications) ?>
                                                  </h3>
                                             </div>
                                             <div class="stat-icon primary">
                                                  <iconify-icon icon="solar:bell-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-6 col-lg-4">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">Unread</p>
                                                  <h3 class="stat-value mb-1"><?= $unreadCount ?></h3>
                                             </div>
                                             <div class="stat-icon warning">
                                                  <iconify-icon icon="solar:bell-minimalistic-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-12 col-lg-4">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">This Week</p>
                                                  <?php
                                                  $weekCount = mysqli_fetch_assoc(mysqli_query($conn, "
                                                      SELECT COUNT(*) as c FROM notifications
                                                      WHERE (user_type IN ('manager', 'admin') OR user_type = 'all')
                                                      AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                                                  "))['c'];
                                                  ?>
                                                  <h3 class="stat-value mb-1"><?= $weekCount ?></h3>
                                             </div>
                                             <div class="stat-icon info">
                                                  <iconify-icon icon="solar:calendar-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="row">
                         <!-- Notifications List -->
                         <div class="col-lg-8">
                              <div class="card">
                                   <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Recent Notifications</h5>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                             data-bs-target="#createNotifModal">
                                             <iconify-icon icon="solar:add-circle-outline"></iconify-icon> Send
                                             Notification
                                        </button>
                                   </div>
                                   <div class="card-body p-0">
                                        <div class="list-group list-group-flush">
                                             <?php if (mysqli_num_rows($getNotifications) > 0): ?>
                                                  <?php while ($notif = mysqli_fetch_assoc($getNotifications)): ?>
                                                       <div
                                                            class="list-group-item notification-item <?= !$notif['is_read'] ? 'unread' : '' ?>">
                                                            <div class="d-flex align-items-start">
                                                                 <div class="flex-shrink-0">
                                                                      <div
                                                                           class="p-2 rounded bg-<?= getNotificationTypeColor($notif['type']) ?> bg-opacity-10 text-<?= getNotificationTypeColor($notif['type']) ?>">
                                                                           <iconify-icon
                                                                                icon="<?= getNotificationIcon($notif['type']) ?>"
                                                                                class="fs-20"></iconify-icon>
                                                                      </div>
                                                                 </div>
                                                                 <div class="flex-grow-1 ms-3">
                                                                      <div class="d-flex justify-content-between">
                                                                           <h6
                                                                                class="mb-1 <?= !$notif['is_read'] ? 'fw-bold' : '' ?>">
                                                                                <?= htmlspecialchars($notif['title']) ?>
                                                                           </h6>
                                                                           <small class="text-muted">
                                                                                <?= date('M d, H:i', strtotime($notif['created_at'])) ?>
                                                                                <?php if (!$notif['is_read']): ?>
                                                                                     <span class="badge bg-warning ms-2">New</span>
                                                                                <?php endif; ?>
                                                                           </small>
                                                                      </div>
                                                                      <p class="mb-1 text-muted">
                                                                           <?= htmlspecialchars($notif['message']) ?>
                                                                      </p>
                                                                      <?php if ($notif['link']): ?>
                                                                           <a href="<?= htmlspecialchars($notif['link']) ?>"
                                                                                class="btn btn-sm btn-link ps-0">
                                                                                View <iconify-icon
                                                                                     icon="solar:alt-arrow-right-outline"></iconify-icon>
                                                                           </a>
                                                                      <?php endif; ?>
                                                                 </div>
                                                                 <div class="flex-shrink-0">
                                                                      <div class="dropdown">
                                                                           <button class="btn btn-light btn-sm"
                                                                                data-bs-toggle="dropdown">
                                                                                <iconify-icon
                                                                                     icon="solar:alt-arrow-down-outline"></iconify-icon>
                                                                           </button>
                                                                           <ul class="dropdown-menu">
                                                                                <li><a class="dropdown-item"
                                                                                          href="?delete=<?= $notif['id'] ?>">Delete</a>
                                                                                </li>
                                                                           </ul>
                                                                      </div>
                                                                 </div>
                                                            </div>
                                                       </div>
                                                  <?php endwhile; ?>
                                             <?php else: ?>
                                                  <div class="text-center py-5">
                                                       <iconify-icon icon="solar:bell-off-outline"
                                                            class="fs-48 text-muted mb-2"></iconify-icon>
                                                       <p class="text-muted mb-0">No notifications</p>
                                                  </div>
                                             <?php endif; ?>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- Quick Actions -->
                         <div class="col-lg-4">
                              <div class="card mb-3">
                                   <div class="card-header">
                                        <h5 class="mb-0">Quick Send</h5>
                                   </div>
                                   <div class="card-body">
                                        <div class="d-grid gap-2">
                                             <button class="btn btn-outline-primary text-start" data-bs-toggle="modal"
                                                  data-bs-target="#createNotifModal">
                                                  <iconify-icon icon="solar:send-outline"></iconify-icon> Send to All
                                             </button>
                                             <button class="btn btn-outline-info text-start"
                                                  onclick="quickSend('agents')">
                                                  <iconify-icon icon="solar:users-group-rounded-outline"></iconify-icon>
                                                  Notify Agents
                                             </button>
                                             <button class="btn btn-outline-success text-start"
                                                  onclick="quickSend('clients')">
                                                  <iconify-icon icon="solar:user-plus-rounded"></iconify-icon> Notify
                                                  Clients
                                             </button>
                                        </div>
                                   </div>
                              </div>

                              <div class="card">
                                   <div class="card-header">
                                        <h5 class="mb-0">Notification Templates</h5>
                                   </div>
                                   <div class="card-body">
                                        <div class="list-group list-group-flush">
                                             <a href="#" class="list-group-item list-group-item-action"
                                                  onclick="useTemplate('system_update')">
                                                  <iconify-icon icon="solar:refresh-circle-outline"
                                                       class="text-primary"></iconify-icon>
                                                  System Update
                                             </a>
                                             <a href="#" class="list-group-item list-group-item-action"
                                                  onclick="useTemplate('commission_update')">
                                                  <iconify-icon icon="solar:wallet-money-outline"
                                                       class="text-success"></iconify-icon>
                                                  Commission Update
                                             </a>
                                             <a href="#" class="list-group-item list-group-item-action"
                                                  onclick="useTemplate('new_feature')">
                                                  <iconify-icon icon="solar:star-outline"
                                                       class="text-warning"></iconify-icon>
                                                  New Feature
                                             </a>
                                             <a href="#" class="list-group-item list-group-item-action"
                                                  onclick="useTemplate('maintenance')">
                                                  <iconify-icon icon="solar:tool-outline"
                                                       class="text-danger"></iconify-icon>
                                                  Maintenance Notice
                                             </a>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

               </div>

               <footer class="footer">
                    <div class="container-fluid">
                         <div class="row">
                              <div class="col-12 text-center">
                                   <p>
                                        <script>document.write(new Date().getFullYear())</script> &copy; ApplyBoard
                                        Africa Ltd.
                                   </p>
                              </div>
                         </div>
                    </div>
               </footer>

          </div>
     </div>

     <!-- Create Notification Modal -->
     <div class="modal fade" id="createNotifModal" tabindex="-1">
          <div class="modal-dialog">
               <div class="modal-content">
                    <form method="POST">
                         <div class="modal-header">
                              <h5 class="modal-title">Send Notification</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                         </div>
                         <div class="modal-body">
                              <div class="mb-3">
                                   <label class="form-label">Title <span class="text-danger">*</span></label>
                                   <input type="text" name="title" class="form-control" required
                                        placeholder="e.g., System Update">
                              </div>
                              <div class="mb-3">
                                   <label class="form-label">Message <span class="text-danger">*</span></label>
                                   <textarea name="message" class="form-control" rows="3" required
                                        placeholder="Enter your message here..."></textarea>
                              </div>
                              <div class="row">
                                   <div class="col-md-6 mb-3">
                                        <label class="form-label">Type</label>
                                        <select name="type" class="form-select">
                                             <option value="info">Info</option>
                                             <option value="success">Success</option>
                                             <option value="warning">Warning</option>
                                             <option value="danger">Alert</option>
                                        </select>
                                   </div>
                                   <div class="col-md-6 mb-3">
                                        <label class="form-label">Send To</label>
                                        <select name="target_type" class="form-select">
                                             <option value="all">All Users</option>
                                             <option value="agents">Agents Only</option>
                                             <option value="clients">Clients Only</option>
                                        </select>
                                   </div>
                              </div>
                              <div class="mb-3">
                                   <label class="form-label">Link (Optional)</label>
                                   <input type="text" name="link" class="form-control"
                                        placeholder="e.g., /manager/reports">
                              </div>
                         </div>
                         <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                              <button type="submit" name="create_notification" class="btn btn-primary">Send
                                   Notification</button>
                         </div>
                    </form>
               </div>
          </div>
     </div>

     <script src="assets/js/vendor.min.js"></script>
     <script src="assets/js/app.js"></script>

     <script>
          function getNotificationIcon($type) {
               const icons = {
                    'success': 'solar:check-circle-outline',
                    'info': 'solar:info-circle-outline',
                    'warning': 'solar:danger-triangle-outline',
                    'danger': 'solar:danger-circle-outline',
                    'case': 'solar:folder-with-files-outline',
                    'document': 'solar:document-text-outline',
                    'message': 'solar:letter-outline',
                    'payment': 'solar:wallet-money-outline'
               };
               return icons[$type] || 'solar:bell-outline';
          }

          function getNotificationTypeColor($type) {
               const colors = {
                    'success': 'success',
                    'info': 'primary',
                    'warning': 'warning',
                    'danger': 'danger'
               };
               return colors[$type] || 'secondary';
          }

          function quickSend(target) {
               const templates = {
                    'agents': {
                         title: 'Agent Alert',
                         message: 'Please check your dashboard for important updates.',
                         type: 'info'
                    },
                    'clients': {
                         title: 'Client Update',
                         message: 'New features have been added to your portal.',
                         type: 'info'
                    }
               };

               document.querySelector('[name="title"]').value = templates[target].title;
               document.querySelector('[name="message"]').value = templates[target].message;
               document.querySelector('[name="type"]').value = templates[target].type;
               document.querySelector('[name="target_type"]').value = target;

               var modal = new bootstrap.Modal(document.getElementById('createNotifModal'));
               modal.show();
          }

          function useTemplate(type) {
               const templates = {
                    'system_update': {
                         title: 'System Maintenance Scheduled',
                         message: 'The system will undergo maintenance on [date]. Please save your work before this time.',
                         type: 'warning'
                    },
                    'commission_update': {
                         title: 'Commission Processed',
                         message: 'Your commission for [period] has been processed and is now available in your dashboard.',
                         type: 'success'
                    },
                    'new_feature': {
                         title: 'New Feature Available',
                         message: 'We have added a new feature to help you manage your applications more efficiently.',
                         type: 'info'
                    },
                    'maintenance': {
                         title: 'Scheduled Maintenance',
                         message: 'Our platform will be under maintenance on [date] from [time] to [time].',
                         type: 'danger'
                    }
               };

               document.querySelector('[name="title"]').value = templates[type].title;
               document.querySelector('[name="message"]').value = templates[type].message;
               document.querySelector('[name="type"]').value = templates[type].type;

               var modal = new bootstrap.Modal(document.getElementById('createNotifModal'));
               modal.show();
          }

          // Auto-refresh notifications every 30 seconds
          setInterval(function () {
               location.reload();
          }, 30000);
     </script>

</body>

</html>
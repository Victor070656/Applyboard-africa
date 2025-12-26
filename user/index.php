<?php
include "../config/config.php";
include "../config/case_helper.php";
if (!isLoggedIn('user')) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

$user = auth('user');
$userId = $user['id'];

// Get user statistics
$totalCases = countCases(['client_id' => $userId]);
$activeCases = countCases(['client_id' => $userId, 'status' => 'active']);
$completedCases = countCases(['client_id' => $userId, 'status' => 'completed']);
$pendingCases = countCases(['client_id' => $userId, 'stage' => 'assessment']);

// Get recent cases
$recentCases = getCases(['client_id' => $userId, 'limit' => 5, 'order_by' => 'c.created_at']);

// Get notifications
$notifications = getUserNotifications($userId, 'client', true);
$unreadCount = getUnreadNotificationCount($userId, 'client');

// Get unread notifications for display
$recentNotifications = array_slice($notifications, 0, 3);

// Helper function for stage badge color
function getStageBadge($stage) {
    $badges = [
        'assessment' => 'bg-primary',
        'options' => 'bg-info',
        'application' => 'bg-secondary',
        'submission' => 'bg-warning',
        'offer' => 'bg-success',
        'visa' => 'bg-danger',
        'travel' => 'bg-purple',
        'booking' => 'bg-orange',
        'completed' => 'bg-success',
        'closed' => 'bg-dark',
        'requirements' => 'bg-primary',
        'processing' => 'bg-info',
        'decision' => 'bg-warning'
    ];
    return isset($badges[$stage]) ? $badges[$stage] : 'bg-secondary';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
     <!-- Title Meta -->
     <meta charset="utf-8" />
     <title>ApplyBoard Africa Ltd User || Dashboard</title>
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
        .stat-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .quick-action-card {
            border: 2px dashed #e0e0e0;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: block;
            color: inherit;
        }
        .quick-action-card:hover {
            border-color: #0F4C75;
            background: rgba(15, 76, 117, 0.05);
            color: inherit;
        }
        .quick-action-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 22px;
        }
        @media (max-width: 576px) {
            .quick-action-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
                margin-bottom: 8px;
            }
            .quick-action-card {
                padding: 10px;
            }
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
                                       <h4 class="mb-0">Dashboard</h4>
                                       <ol class="breadcrumb mb-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">ApplyBoard Africa Ltd</a></li>
                                            <li class="breadcrumb-item active">Dashboard</li>
                                       </ol>
                                   </div>
                                   <?php if ($unreadCount > 0): ?>
                                   <a href="notifications.php" class="btn btn-outline-primary btn-sm">
                                       <iconify-icon icon="solar:bell-outline"></iconify-icon> <?= $unreadCount ?> New Notifications
                                   </a>
                                   <?php endif; ?>
                              </div>
                         </div>
                    </div>
                    <!-- ========== Page Title End ========== -->

                    <!-- Welcome Banner -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card bg-primary bg-opacity-10 border-primary">
                                <div class="card-body">
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <div class="flex-shrink-0">
                                            <div class="quick-action-icon bg-primary text-white">
                                                <iconify-icon icon="solar:waving-hand-outline"></iconify-icon>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-1">Welcome back, <?= htmlspecialchars($user['fullname'] ?? 'Student') ?>!</h5>
                                            <p class="mb-0 text-muted d-none d-sm-block">Track your applications, upload documents, and stay updated with your progress.</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <a href="new_application.php" class="btn btn-primary btn-sm">
                                                <iconify-icon icon="solar:add-circle-outline"></iconify-icon> <span class="d-none d-md-inline">New Application</span><span class="d-md-none">New</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mt-3">
                        <div class="col-6 col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="quick-action-icon bg-primary bg-opacity-10 text-primary" style="width: 40px; height: 40px; font-size: 18px; margin: 0;">
                                                <iconify-icon icon="solar:folder-with-files-outline"></iconify-icon>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h6 class="text-muted mb-0 small">Total</h6>
                                            <h4 class="mb-0"><?= $totalCases ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="quick-action-icon bg-warning bg-opacity-10 text-warning" style="width: 40px; height: 40px; font-size: 18px; margin: 0;">
                                                <iconify-icon icon="solar:clock-circle-outline"></iconify-icon>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h6 class="text-muted mb-0 small">Active</h6>
                                            <h4 class="mb-0"><?= $activeCases ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mt-2 mt-md-0">
                            <div class="card stat-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="quick-action-icon bg-success bg-opacity-10 text-success" style="width: 40px; height: 40px; font-size: 18px; margin: 0;">
                                                <iconify-icon icon="solar:check-circle-outline"></iconify-icon>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h6 class="text-muted mb-0 small">Completed</h6>
                                            <h4 class="mb-0"><?= $completedCases ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mt-2 mt-md-0">
                            <div class="card stat-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="quick-action-icon bg-info bg-opacity-10 text-info" style="width: 40px; height: 40px; font-size: 18px; margin: 0;">
                                                <iconify-icon icon="solar:document-text-outline"></iconify-icon>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h6 class="text-muted mb-0 small">Pending</h6>
                                            <h4 class="mb-0"><?= $pendingCases ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <!-- Quick Actions -->
                        <div class="col-lg-4 order-2 order-lg-1">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <a href="new_application.php" class="quick-action-card">
                                                <div class="quick-action-icon bg-primary bg-opacity-10 text-primary">
                                                    <iconify-icon icon="solar:add-circle-outline"></iconify-icon>
                                                </div>
                                                <h6 class="mb-0 small">New App</h6>
                                            </a>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <a href="documents.php" class="quick-action-card">
                                                <div class="quick-action-icon bg-info bg-opacity-10 text-info">
                                                    <iconify-icon icon="solar:document-upload-outline"></iconify-icon>
                                                </div>
                                                <h6 class="mb-0 small">Upload</h6>
                                            </a>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <a href="cases.php" class="quick-action-card">
                                                <div class="quick-action-icon bg-warning bg-opacity-10 text-warning">
                                                    <iconify-icon icon="solar:folder-open-outline"></iconify-icon>
                                                </div>
                                                <h6 class="mb-0 small">My Cases</h6>
                                            </a>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <a href="profile.php" class="quick-action-card">
                                                <div class="quick-action-icon bg-success bg-opacity-10 text-success">
                                                    <iconify-icon icon="solar:user-edit-outline"></iconify-icon>
                                                </div>
                                                <h6 class="mb-0 small">Profile</h6>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Notifications -->
                            <div class="card mt-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Recent Notifications</h5>
                                    <a href="notifications.php" class="btn btn-sm btn-link">View All</a>
                                </div>
                                <div class="card-body p-0">
                                    <?php if (empty($recentNotifications)): ?>
                                        <div class="text-center py-4">
                                            <p class="text-muted mb-0">No recent notifications</p>
                                        </div>
                                    <?php else: ?>
                                        <div class="list-group list-group-flush">
                                            <?php foreach ($recentNotifications as $notif): ?>
                                                <div class="list-group-item px-3">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1 small"><?= htmlspecialchars($notif['title']) ?></h6>
                                                            <p class="mb-0 small text-muted"><?= htmlspecialchars(substr($notif['message'], 0, 60)) ?>...</p>
                                                            <small class="text-muted"><?= date('d M', strtotime($notif['created_at'])) ?></small>
                                                        </div>
                                                        <?php if ($notif['link']): ?>
                                                        <a href="<?= htmlspecialchars($notif['link']) ?>" class="btn btn-sm btn-link">
                                                            <iconify-icon icon="solar:alt-arrow-right-outline"></iconify-icon>
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

                        <!-- Recent Cases -->
                        <div class="col-lg-8 order-1 order-lg-2">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Recent Applications</h5>
                                    <a href="cases.php" class="btn btn-sm btn-outline-primary">View All</a>
                                </div>
                                <div class="card-body p-0">
                                    <?php if (empty($recentCases)): ?>
                                        <div class="text-center py-5">
                                            <div class="quick-action-icon bg-secondary bg-opacity-10 text-secondary mx-auto mb-3">
                                                <iconify-icon icon="solar:folder-open-outline"></iconify-icon>
                                            </div>
                                            <h5>No Applications Yet</h5>
                                            <p class="text-muted mb-3">Start your journey by creating your first application.</p>
                                            <a href="new_application.php" class="btn btn-primary">
                                                <iconify-icon icon="solar:add-circle-outline"></iconify-icon> Create Application
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Case #</th>
                                                        <th>Title</th>
                                                        <th>Stage</th>
                                                        <th>Status</th>
                                                        <th>Created</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($recentCases as $case): ?>
                                                        <tr>
                                                            <td><strong><?= htmlspecialchars($case['case_number']) ?></strong></td>
                                                            <td><?= htmlspecialchars(substr($case['title'], 0, 30)) ?></td>
                                                            <td>
                                                                <span class="badge <?= getStageBadge($case['stage']) ?>">
                                                                    <?= getStageLabelFromStage($case['stage']) ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="badge <?= $case['status'] === 'active' ? 'bg-success' : ($case['status'] === 'completed' ? 'bg-primary' : 'bg-warning') ?>">
                                                                    <?= ucfirst($case['status']) ?>
                                                                </span>
                                                            </td>
                                                            <td><?= date('d M Y', strtotime($case['created_at'])) ?></td>
                                                            <td>
                                                                <a href="?view=<?= $case['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Services Quick Links -->
                            <div class="card mt-3 d-none d-lg-block">
                                <div class="card-header">
                                    <h5 class="mb-0">Our Services</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <iconify-icon icon="solar:graduation-cap-outline" class="fs-24 text-primary"></iconify-icon>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h6 class="mb-0">Study Abroad</h6>
                                                    <small class="text-muted">Apply to universities</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <iconify-icon icon="solar:passport-outline" class="fs-24 text-success"></iconify-icon>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h6 class="mb-0">Visa Assistance</h6>
                                                    <small class="text-muted">Student & Tourist visas</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <iconify-icon icon="solar:airplane-outline" class="fs-24 text-warning"></iconify-icon>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h6 class="mb-0">Travel Booking</h6>
                                                    <small class="text-muted">Flights & Hotels</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-2">
                                        <a href="../services.php" class="btn btn-sm btn-outline-primary">View All Services</a>
                                    </div>
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

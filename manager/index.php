<?php
include "../config/config.php";
// session_start();
if (!isset($_SESSION['sdtravels_manager'])) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

// Get manager info
$manager_name = $_SESSION['manager_name'] ?? 'Admin';
$manager_id = $_SESSION['manager_id'] ?? 0;

// Comprehensive Stats
$getAgents = mysqli_query($conn, "SELECT * FROM `agents`");
$getPendingAgents = mysqli_query($conn, "SELECT * FROM `agents` WHERE `status` = 'pending'");
$getVerifiedAgents = mysqli_query($conn, "SELECT * FROM `agents` WHERE `status` = 'approved'");
$getInquiries = mysqli_query($conn, "SELECT * FROM `inquiries`");
$getNewInquiries = mysqli_query($conn, "SELECT * FROM `inquiries` WHERE `status` = 'new'");

// Try to get cases and other counts (if tables exist)
$getCases = mysqli_query($conn, "SELECT * FROM `cases`");
$cases_count = $getCases ? $getCases->num_rows : 0;
$pending_cases = mysqli_query($conn, "SELECT * FROM `cases` WHERE `status` IN ('pending', 'in_progress')");
$pending_cases_count = $pending_cases ? $pending_cases->num_rows : 0;

// Get clients count (users table)
$getClients = mysqli_query($conn, "SELECT * FROM `users`");
$clients_count = $getClients ? $getClients->num_rows : 0;

// Get payments
$getPayments = mysqli_query($conn, "SELECT SUM(amount) as total FROM `payments` WHERE `status` = 'verified'");
$payments_total = 0;
if ($getPayments) {
     $pay_row = $getPayments->fetch_assoc();
     $payments_total = $pay_row['total'] ?? 0;
}

// Get commissions
$getCommissions = mysqli_query($conn, "SELECT SUM(amount) as total FROM `commissions`");
$commissions_total = 0;
if ($getCommissions) {
     $comm_row = $getCommissions->fetch_assoc();
     $commissions_total = $comm_row['total'] ?? 0;
}

// Get recent activity (recent cases)
$recent_cases = mysqli_query($conn, "SELECT c.*, u.fullname as client_name, a.fullname as agent_name 
                                     FROM `cases` c 
                                     LEFT JOIN `users` u ON c.client_id = u.id 
                                     LEFT JOIN `agents` a ON c.agent_id = a.id 
                                     ORDER BY c.created_at DESC LIMIT 5");

// Get recent agents
$recent_agents = mysqli_query($conn, "SELECT * FROM `agents` ORDER BY `created_at` DESC LIMIT 5");

// Get time of day for greeting
$hour = date('H');
$greeting = 'Good Morning';
if ($hour >= 12 && $hour < 17) {
     $greeting = 'Good Afternoon';
} elseif ($hour >= 17) {
     $greeting = 'Good Evening';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
     <!-- Title Meta -->
     <meta charset="utf-8" />
     <title>Admin Dashboard | ApplyBoard Africa</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <meta name="robots" content="index, follow" />
     <meta name="theme-color" content="#1e3a5f">

     <!-- App favicon -->
     <link rel="shortcut icon" href="../images/favicon.png">

     <!-- Google Font Family -->
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

     <!-- Vendor css -->
     <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />

     <!-- Icons css -->
     <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

     <!-- App css -->
     <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />

     <!-- Custom Dashboard css -->
     <link href="assets/css/dashboard.css" rel="stylesheet" type="text/css" />

     <!-- Theme Config js -->
     <script src="assets/js/config.js"></script>
     <!-- Iconify -->
     <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
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

          <!-- ==================================================== -->
          <!-- Start right Content here -->
          <!-- ==================================================== -->
          <div class="page-content">

               <!-- Start Container Fluid -->
               <div class="container-fluid">

                    <!-- Welcome Banner -->
                    <div class="welcome-banner">
                         <div class="welcome-content">
                              <h4 class="text-white"><?php echo $greeting; ?>, <?php echo htmlspecialchars($manager_name); ?>! 👋</h4>
                              <p>Welcome to your admin dashboard. Manage agents, track applications, and monitor
                                   platform performance all from one place.</p>
                              <div class="d-flex gap-2 mt-3">
                                   <a href="agents.php?status=pending" class="btn btn-light">
                                        <iconify-icon icon="solar:user-check-outline" class="me-1"></iconify-icon>
                                        Review Agents
                                   </a>
                                   <a href="inquiries.php" class="btn btn-light">
                                        <iconify-icon icon="solar:chat-round-line-broken" class="me-1"></iconify-icon>
                                        View Inquiries
                                   </a>
                              </div>
                         </div>
                    </div>

                    <!-- Stats Cards Row 1 -->
                    <div class="row g-3 mb-4">
                         <!-- Total Agents -->
                         <div class="col-6 col-lg-3">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">Total Agents</p>
                                                  <h3 class="stat-value mb-1"><?php echo $getAgents->num_rows; ?></h3>
                                                  <span class="stat-trend up">
                                                       <iconify-icon icon="solar:arrow-up-outline"></iconify-icon>
                                                       <?php echo $getVerifiedAgents->num_rows; ?> active
                                                  </span>
                                             </div>
                                             <div class="stat-icon primary">
                                                  <iconify-icon
                                                       icon="solar:users-group-two-rounded-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- Pending Agents -->
                         <div class="col-6 col-lg-3">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">Pending Agents</p>
                                                  <h3 class="stat-value mb-1"><?php echo $getPendingAgents->num_rows; ?>
                                                  </h3>
                                                  <span class="stat-trend down">
                                                       <iconify-icon icon="solar:hourglass-outline"></iconify-icon>
                                                       Needs Review
                                                  </span>
                                             </div>
                                             <div class="stat-icon warning">
                                                  <iconify-icon icon="solar:user-check-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- Inquiries -->
                         <div class="col-6 col-lg-3">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">Total Inquiries</p>
                                                  <h3 class="stat-value mb-1"><?php echo $getInquiries->num_rows; ?>
                                                  </h3>
                                                  <span class="stat-trend up">
                                                       <iconify-icon icon="solar:chat-round-line-broken"></iconify-icon>
                                                       <?php echo $getNewInquiries->num_rows; ?> new
                                                  </span>
                                             </div>
                                             <div class="stat-icon info">
                                                  <iconify-icon icon="solar:chat-round-line-broken"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- Cases -->
                         <div class="col-6 col-lg-3">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">Total Cases</p>
                                                  <h3 class="stat-value mb-1"><?php echo $cases_count; ?></h3>
                                                  <span class="stat-trend up">
                                                       <iconify-icon icon="solar:folder-check-outline"></iconify-icon>
                                                       <?php echo $pending_cases_count; ?> in progress
                                                  </span>
                                             </div>
                                             <div class="stat-icon success">
                                                  <iconify-icon icon="solar:folder-open-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <!-- Stats Cards Row 2 -->
                    <div class="row g-3 mb-4">
                         <!-- Total Clients -->
                         <div class="col-6 col-lg-3">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">Total Clients</p>
                                                  <h3 class="stat-value mb-1"><?php echo $clients_count; ?></h3>
                                                  <span class="stat-trend up">
                                                       <iconify-icon icon="solar:user-outline"></iconify-icon>
                                                       Registered
                                                  </span>
                                             </div>
                                             <div class="stat-icon primary">
                                                  <iconify-icon icon="solar:user-rounded-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- Payments -->
                         <div class="col-6 col-lg-3">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">Total Payments</p>
                                                  <h3 class="stat-value mb-1">
                                                       ₦<?php echo number_format($payments_total); ?></h3>
                                                  <span class="stat-trend up">
                                                       <iconify-icon icon="solar:check-circle-outline"></iconify-icon>
                                                       Verified
                                                  </span>
                                             </div>
                                             <div class="stat-icon success">
                                                  <iconify-icon icon="solar:wallet-money-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- Commissions -->
                         <div class="col-6 col-lg-3">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">Commissions</p>
                                                  <h3 class="stat-value mb-1">
                                                       ₦<?php echo number_format($commissions_total); ?></h3>
                                                  <span class="stat-trend up">
                                                       <iconify-icon icon="solar:hand-money-outline"></iconify-icon>
                                                       Earned
                                                  </span>
                                             </div>
                                             <div class="stat-icon warning">
                                                  <iconify-icon icon="solar:hand-money-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- New Inquiries -->
                         <div class="col-6 col-lg-3">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">New Inquiries</p>
                                                  <h3 class="stat-value mb-1"><?php echo $getNewInquiries->num_rows; ?>
                                                  </h3>
                                                  <span class="stat-trend down">
                                                       <iconify-icon icon="solar:bell-outline"></iconify-icon>
                                                       Unread
                                                  </span>
                                             </div>
                                             <div class="stat-icon danger">
                                                  <iconify-icon icon="solar:inbox-unread-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mb-4">
                         <div class="card-header">
                              <h5><iconify-icon icon="solar:bolt-outline" class="me-2"></iconify-icon>Quick Actions</h5>
                         </div>
                         <div class="card-body">
                              <div class="row g-3">
                                   <div class="col-6 col-md-4 col-lg-2">
                                        <a href="agents.php" class="quick-action-card">
                                             <div class="quick-action-icon"
                                                  style="background: rgba(49, 130, 206, 0.15); color: #3182ce;">
                                                  <iconify-icon
                                                       icon="solar:users-group-two-rounded-outline"></iconify-icon>
                                             </div>
                                             <h6 class="mb-0">Manage Agents</h6>
                                        </a>
                                   </div>
                                   <div class="col-6 col-md-4 col-lg-2">
                                        <a href="cases.php" class="quick-action-card">
                                             <div class="quick-action-icon"
                                                  style="background: rgba(56, 161, 105, 0.15); color: #38a169;">
                                                  <iconify-icon icon="solar:folder-open-outline"></iconify-icon>
                                             </div>
                                             <h6 class="mb-0">View Cases</h6>
                                        </a>
                                   </div>
                                   <div class="col-6 col-md-4 col-lg-2">
                                        <a href="clients.php" class="quick-action-card">
                                             <div class="quick-action-icon"
                                                  style="background: rgba(128, 90, 213, 0.15); color: #805ad5;">
                                                  <iconify-icon icon="solar:user-rounded-outline"></iconify-icon>
                                             </div>
                                             <h6 class="mb-0">All Clients</h6>
                                        </a>
                                   </div>
                                   <div class="col-6 col-md-4 col-lg-2">
                                        <a href="payments.php" class="quick-action-card">
                                             <div class="quick-action-icon"
                                                  style="background: rgba(221, 107, 32, 0.15); color: #dd6b20;">
                                                  <iconify-icon icon="solar:wallet-money-outline"></iconify-icon>
                                             </div>
                                             <h6 class="mb-0">Payments</h6>
                                        </a>
                                   </div>
                                   <div class="col-6 col-md-4 col-lg-2">
                                        <a href="reports.php" class="quick-action-card">
                                             <div class="quick-action-icon"
                                                  style="background: rgba(229, 62, 62, 0.15); color: #e53e3e;">
                                                  <iconify-icon icon="solar:chart-2-outline"></iconify-icon>
                                             </div>
                                             <h6 class="mb-0">Reports</h6>
                                        </a>
                                   </div>
                                   <div class="col-6 col-md-4 col-lg-2">
                                        <a href="settings.php" class="quick-action-card">
                                             <div class="quick-action-icon"
                                                  style="background: rgba(113, 128, 150, 0.15); color: #718096;">
                                                  <iconify-icon icon="solar:settings-outline"></iconify-icon>
                                             </div>
                                             <h6 class="mb-0">Settings</h6>
                                        </a>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <!-- Recent Activity Row -->
                    <div class="row g-4">
                         <!-- Recent Cases -->
                         <div class="col-lg-8">
                              <div class="card">
                                   <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                             <iconify-icon icon="solar:folder-open-outline" class="me-2"></iconify-icon>
                                             Recent Cases
                                        </h5>
                                        <a href="cases.php" class="btn btn-sm btn-outline-primary">View All</a>
                                   </div>
                                   <div class="card-body p-0">
                                        <?php if ($recent_cases && $recent_cases->num_rows > 0): ?>
                                             <div class="table-responsive">
                                                  <table class="table mb-0">
                                                       <thead>
                                                            <tr>
                                                                 <th>Client</th>
                                                                 <th>Agent</th>
                                                                 <th>Type</th>
                                                                 <th>Status</th>
                                                                 <th>Date</th>
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                            <?php while ($case = $recent_cases->fetch_assoc()): ?>
                                                                 <tr>
                                                                      <td>
                                                                           <div class="d-flex align-items-center gap-2">
                                                                                <div class="avatar-placeholder">
                                                                                     <?php echo strtoupper(substr($case['client_name'] ?? 'U', 0, 1)); ?>
                                                                                </div>
                                                                                <div>
                                                                                     <strong><?php echo htmlspecialchars($case['client_name'] ?? 'Unknown'); ?></strong>
                                                                                </div>
                                                                           </div>
                                                                      </td>
                                                                      <td><?php echo htmlspecialchars($case['agent_name'] ?? 'Unassigned'); ?>
                                                                      </td>
                                                                      <td><?php echo htmlspecialchars($case['case_type'] ?? 'N/A'); ?>
                                                                      </td>
                                                                      <td>
                                                                           <?php
                                                                           $status = $case['status'] ?? 'pending';
                                                                           $badge_class = 'bg-secondary';
                                                                           if ($status == 'approved' || $status == 'completed')
                                                                                $badge_class = 'bg-success';
                                                                           elseif ($status == 'pending')
                                                                                $badge_class = 'bg-warning';
                                                                           elseif ($status == 'in_progress')
                                                                                $badge_class = 'bg-info';
                                                                           elseif ($status == 'rejected')
                                                                                $badge_class = 'bg-danger';
                                                                           ?>
                                                                           <span
                                                                                class="badge <?php echo $badge_class; ?>"><?php echo ucfirst(str_replace('_', ' ', $status)); ?></span>
                                                                      </td>
                                                                      <td><?php echo date('M j, Y', strtotime($case['created_at'] ?? 'now')); ?>
                                                                      </td>
                                                                 </tr>
                                                            <?php endwhile; ?>
                                                       </tbody>
                                                  </table>
                                             </div>
                                        <?php else: ?>
                                             <div class="text-center py-5">
                                                  <iconify-icon icon="solar:folder-open-outline"
                                                       class="fs-48 text-muted"></iconify-icon>
                                                  <p class="text-muted mt-2">No cases found</p>
                                             </div>
                                        <?php endif; ?>
                                   </div>
                              </div>
                         </div>

                         <!-- Recent Agents -->
                         <div class="col-lg-4">
                              <div class="card">
                                   <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                             <iconify-icon icon="solar:users-group-two-rounded-outline"
                                                  class="me-2"></iconify-icon>
                                             Recent Agents
                                        </h5>
                                        <a href="agents.php" class="btn btn-sm btn-outline-primary">View All</a>
                                   </div>
                                   <div class="card-body p-0">
                                        <ul class="list-group list-group-flush">
                                             <?php if ($recent_agents && $recent_agents->num_rows > 0): ?>
                                                  <?php while ($agent = $recent_agents->fetch_assoc()): ?>
                                                       <li class="list-group-item">
                                                            <div class="d-flex align-items-center gap-3">
                                                                 <div class="avatar-placeholder">
                                                                      <?php echo strtoupper(substr($agent['fullname'] ?? 'A', 0, 1)); ?>
                                                                 </div>
                                                                 <div class="flex-grow-1">
                                                                      <h6 class="mb-0">
                                                                           <?php echo htmlspecialchars($agent['fullname'] ?? 'Unknown'); ?>
                                                                      </h6>
                                                                      <small
                                                                           class="text-muted"><?php echo htmlspecialchars($agent['email'] ?? ''); ?></small>
                                                                 </div>
                                                                 <?php
                                                                 $status = $agent['status'] ?? 'pending';
                                                                 $badge_class = 'bg-secondary';
                                                                 if ($status == 'approved')
                                                                      $badge_class = 'bg-success';
                                                                 elseif ($status == 'pending')
                                                                      $badge_class = 'bg-warning';
                                                                 elseif ($status == 'rejected')
                                                                      $badge_class = 'bg-danger';
                                                                 ?>
                                                                 <span
                                                                      class="badge <?php echo $badge_class; ?>"><?php echo ucfirst($status); ?></span>
                                                            </div>
                                                       </li>
                                                  <?php endwhile; ?>
                                             <?php else: ?>
                                                  <li class="list-group-item text-center py-4">
                                                       <iconify-icon icon="solar:users-group-two-rounded-outline"
                                                            class="fs-32 text-muted"></iconify-icon>
                                                       <p class="text-muted mt-2 mb-0">No agents found</p>
                                                  </li>
                                             <?php endif; ?>
                                        </ul>
                                   </div>
                              </div>
                         </div>
                    </div>

               </div>
               <!-- End Container Fluid -->

               <!-- Footer Start -->
               <footer class="footer">
                    <div class="container-fluid">
                         <div class="row">
                              <div class="col-12 text-center">
                                   <p>&copy;
                                        <script>document.write(new Date().getFullYear())</script> ApplyBoard Africa Ltd.
                                        All rights reserved.
                                   </p>
                              </div>
                         </div>
                    </div>
               </footer>
               <!-- Footer End -->

          </div>
          <!-- ==================================================== -->
          <!-- End Page Content -->
          <!-- ==================================================== -->

     </div>
     <!-- END Wrapper -->

     <!-- Vendor Javascript -->
     <script src="assets/js/vendor.min.js"></script>

     <!-- App Javascript -->
     <script src="assets/js/app.js"></script>

</body>

</html>
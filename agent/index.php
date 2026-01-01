<?php
include "../config/config.php";
include "../config/case_helper.php";

if (!isLoggedIn('agent')) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
     exit;
}

$agent = auth('agent');
$agent_id = $agent['id'];
$agent_code = $agent['agent_code'];
$agent_status = $agent['status'];
$pageTitle = 'Dashboard';

// Clients summary
$usersCount = (int) mysqli_fetch_assoc(mysqli_query(
     $conn,
     "SELECT COUNT(*) as total FROM `users` WHERE `agent_id` = '$agent_id'"
))['total'];
$recentClientsResult = mysqli_query(
     $conn,
     "SELECT fullname, email, phone, created_at FROM `users` WHERE `agent_id` = '$agent_id' ORDER BY `created_at` DESC LIMIT 4"
);
$recentClients = $recentClientsResult ? mysqli_fetch_all($recentClientsResult, MYSQLI_ASSOC) : [];
$latestClientDate = (!empty($recentClients) && !empty($recentClients[0]['created_at'])) ? date('M d', strtotime($recentClients[0]['created_at'])) : null;

// Inquiry summary
$inquiriesCount = (int) mysqli_fetch_assoc(mysqli_query(
     $conn,
     "SELECT COUNT(*) as total FROM `inquiries` WHERE `agent_id` = '$agent_id'"
))['total'];
$recentInquiriesResult = mysqli_query(
     $conn,
     "SELECT name, email, service_type, status, created_at FROM `inquiries` WHERE `agent_id` = '$agent_id' ORDER BY `created_at` DESC LIMIT 4"
);
$recentInquiries = $recentInquiriesResult ? mysqli_fetch_all($recentInquiriesResult, MYSQLI_ASSOC) : [];

// Case stats
$casesCount = countCases(['agent_id' => $agent_id]);
$activeCasesCount = countCases(['agent_id' => $agent_id, 'status' => 'active']);
$completedCases = countCases(['agent_id' => $agent_id, 'status' => 'completed']);
$pendingCases = countCases(['agent_id' => $agent_id, 'stage' => 'assessment']);

// Commission stats
$pendingPayout = (float) mysqli_fetch_assoc(mysqli_query(
     $conn,
     "SELECT COALESCE(SUM(amount), 0) as total FROM `commissions` WHERE `agent_id` = '$agent_id' AND `status` IN ('pending', 'approved')"
))['total'];
$totalEarned = (float) mysqli_fetch_assoc(mysqli_query(
     $conn,
     "SELECT COALESCE(SUM(amount), 0) as total FROM `commissions` WHERE `agent_id` = '$agent_id' AND `status` = 'paid'"
))['total'];
$lifetimeEarnings = (float) mysqli_fetch_assoc(mysqli_query(
     $conn,
     "SELECT COALESCE(SUM(amount), 0) as total FROM `commissions` WHERE `agent_id` = '$agent_id' AND `status` IN ('pending', 'approved', 'paid')"
))['total'];

// Persist balances
mysqli_query($conn, "UPDATE `agents` SET `wallet_balance` = '$pendingPayout', `total_earned` = '$totalEarned' WHERE `id` = '$agent_id'");

// Performance profile
updateAgentPerformance($agent_id);
$performance = getAgentPerformance($agent_id);
$conversionRate = $casesCount > 0 ? round(($completedCases / max($casesCount, 1)) * 100, 1) : 0;
$agentTier = $performance['tier'] ?? 'bronze';
$agentRating = $performance['rating_overall'] ?? 0;

// Recent cases & commissions
$recentCases = getCases([
     'agent_id' => $agent_id,
     'limit' => 5,
     'order_by' => 'c.created_at',
     'order_dir' => 'DESC'
]);
$recentCommissionsResult = mysqli_query(
     $conn,
     "SELECT payment_reference, amount, status, created_at FROM `commissions` WHERE `agent_id` = '$agent_id' ORDER BY `created_at` DESC LIMIT 4"
);
$recentCommissions = $recentCommissionsResult ? mysqli_fetch_all($recentCommissionsResult, MYSQLI_ASSOC) : [];

// Notifications
$agentUnreadCount = getUnreadNotificationCount($agent_id, 'agent');
$agentRecentNotifications = array_slice(getUserNotifications($agent_id, 'agent', true) ?? [], 0, 3);
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="utf-8" />
     <title>Dashboard | Agent Portal - ApplyBoard Africa</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <meta name="theme-color" content="#0F4C75">

     <link rel="shortcut icon" href="../images/favicon.png">

     <!-- Google Fonts - Inter -->
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

     <!-- Vendor css -->
     <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/dashboard.css" rel="stylesheet" type="text/css" />

     <script src="assets/js/config.js"></script>
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
                                             <li class="breadcrumb-item"><a href="javascript: void(0);">ApplyBoard
                                                       Africa Ltd</a></li>
                                             <li class="breadcrumb-item active">Agent Dashboard</li>
                                        </ol>
                                   </div>
                                   <?php if ($agentUnreadCount > 0): ?>
                                        <a href="notifications.php" class="btn btn-outline-primary btn-sm">
                                             <iconify-icon icon="solar:bell-outline"></iconify-icon> <?= $agentUnreadCount ?>
                                             New Notifications
                                        </a>
                                   <?php endif; ?>
                              </div>
                         </div>
                    </div>
                    <!-- ========== Page Title End ========== -->

                    <!-- Welcome Banner -->
                    <div class="row mb-4">
                         <div class="col-12">
                              <div class="welcome-banner">
                                   <div class="welcome-content d-flex align-items-center flex-wrap gap-3">
                                        <div class="flex-grow-1">
                                             <span class="badge bg-primary bg-opacity-25 text-primary mb-2">
                                                  <iconify-icon icon="solar:badge-check-outline"
                                                       class="fs-14 me-1"></iconify-icon>
                                                  <?= htmlspecialchars(ucfirst($agent_status)); ?> Agent • Code:
                                                  <?= htmlspecialchars($agent_code); ?>
                                             </span>
                                             <h4>Welcome back, <?= htmlspecialchars($agent['fullname'] ?? 'Agent'); ?>!
                                                  👋</h4>
                                             <p class="d-none d-sm-block">Track your clients, nurture new inquiries, and
                                                  keep an eye on your commissions in one modern workspace.</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                             <a href="clients.php" class="btn btn-light me-2">
                                                  <iconify-icon
                                                       icon="solar:users-group-two-rounded-outline"></iconify-icon>
                                                  <span class="d-none d-md-inline">View Clients</span>
                                             </a>
                                             <a href="commissions.php" class="btn">
                                                  <iconify-icon icon="solar:wallet-money-outline"></iconify-icon>
                                                  <span class="d-none d-md-inline">Commission Center</span>
                                             </a>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row g-3 mb-4">
                         <div class="col-6 col-lg-3">
                              <div class="card stat-card h-100">
                                   <div class="card-body">
                                        <div class="d-flex align-items-center gap-3">
                                             <div class="stat-icon primary">
                                                  <iconify-icon
                                                       icon="solar:users-group-two-rounded-outline"></iconify-icon>
                                             </div>
                                             <div>
                                                  <div class="stat-value"><?= number_format($usersCount) ?></div>
                                                  <div class="stat-label">Clients</div>
                                             </div>
                                        </div>
                                        <div class="mt-2">
                                             <small class="text-muted">Latest: <?= $latestClientDate ?? 'N/A' ?></small>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-6 col-lg-3">
                              <div class="card stat-card h-100">
                                   <div class="card-body">
                                        <div class="d-flex align-items-center gap-3">
                                             <div class="stat-icon warning">
                                                  <iconify-icon icon="solar:folder-with-files-outline"></iconify-icon>
                                             </div>
                                             <div>
                                                  <div class="stat-value"><?= number_format($activeCasesCount) ?></div>
                                                  <div class="stat-label">Active Cases</div>
                                             </div>
                                        </div>
                                        <div class="mt-2">
                                             <small class="<?= $pendingCases > 0 ? 'text-warning' : 'text-success' ?>">
                                                  <iconify-icon icon="solar:chart-linear-outline"></iconify-icon>
                                                  <?= $pendingCases > 0 ? $pendingCases . ' pending' : 'All caught up' ?>
                                             </small>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-6 col-lg-3">
                              <div class="card stat-card h-100">
                                   <div class="card-body">
                                        <div class="d-flex align-items-center gap-3">
                                             <div class="stat-icon success">
                                                  <iconify-icon icon="solar:wallet-money-outline"></iconify-icon>
                                             </div>
                                             <div>
                                                  <div class="stat-value">₦<?= number_format($pendingPayout) ?></div>
                                                  <div class="stat-label">Pending Payout</div>
                                             </div>
                                        </div>
                                        <div class="mt-2">
                                             <small class="text-muted">Approved commissions</small>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-6 col-lg-3">
                              <div class="card stat-card h-100">
                                   <div class="card-body">
                                        <div class="d-flex align-items-center gap-3">
                                             <div class="stat-icon info">
                                                  <iconify-icon icon="solar:stars-outline"></iconify-icon>
                                             </div>
                                             <div>
                                                  <div class="stat-value">₦<?= number_format($lifetimeEarnings) ?></div>
                                                  <div class="stat-label">Lifetime</div>
                                             </div>
                                        </div>
                                        <div class="mt-2">
                                             <small class="text-muted"><?= number_format($conversionRate, 1) ?>%
                                                  conversion</small>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="row">
                         <!-- Quick Actions & Notifications -->
                         <div class="col-lg-4 order-2 order-lg-1">
                              <div class="card">
                                   <div class="card-header">
                                        <h5 class="mb-0">Quick Actions</h5>
                                   </div>
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-6 mb-3">
                                                  <a href="clients.php" class="quick-action-card">
                                                       <div
                                                            class="quick-action-icon bg-primary bg-opacity-10 text-primary">
                                                            <iconify-icon
                                                                 icon="solar:users-group-two-rounded-outline"></iconify-icon>
                                                       </div>
                                                       <h6 class="mb-0 small">Clients</h6>
                                                  </a>
                                             </div>
                                             <div class="col-6 mb-3">
                                                  <a href="inquiries.php" class="quick-action-card">
                                                       <div class="quick-action-icon bg-info bg-opacity-10 text-info">
                                                            <iconify-icon
                                                                 icon="solar:question-circle-outline"></iconify-icon>
                                                       </div>
                                                       <h6 class="mb-0 small">Inquiries</h6>
                                                  </a>
                                             </div>
                                             <div class="col-6 mb-3">
                                                  <a href="cases.php" class="quick-action-card">
                                                       <div
                                                            class="quick-action-icon bg-warning bg-opacity-10 text-warning">
                                                            <iconify-icon
                                                                 icon="solar:folder-open-outline"></iconify-icon>
                                                       </div>
                                                       <h6 class="mb-0 small">Cases</h6>
                                                  </a>
                                             </div>
                                             <div class="col-6 mb-3">
                                                  <a href="commissions.php" class="quick-action-card">
                                                       <div
                                                            class="quick-action-icon bg-success bg-opacity-10 text-success">
                                                            <iconify-icon
                                                                 icon="solar:wallet-money-outline"></iconify-icon>
                                                       </div>
                                                       <h6 class="mb-0 small">Commissions</h6>
                                                  </a>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <!-- Performance Snapshot -->
                              <div class="card mt-3">
                                   <div class="card-header">
                                        <h5 class="mb-0">Performance Snapshot</h5>
                                   </div>
                                   <div class="card-body">
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                             <div class="avatar-placeholder"
                                                  style="width: 52px; height: 52px; font-size: 20px;">
                                                  <?= strtoupper(substr($agent['fullname'] ?? 'AG', 0, 2)); ?>
                                             </div>
                                             <div>
                                                  <div class="fw-semibold">Overall Rating</div>
                                                  <div class="display-6 fw-bold mb-0">
                                                       <?= number_format($agentRating, 1); ?> <span
                                                            class="fs-6 text-muted">/ 5</span>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="row g-3">
                                             <div class="col-6">
                                                  <div class="bg-primary-subtle rounded p-3">
                                                       <small class="text-muted d-block mb-1">Tier</small>
                                                       <div class="fw-semibold text-primary text-uppercase">
                                                            <?= strtoupper($agentTier); ?>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="col-6">
                                                  <div class="bg-success-subtle rounded p-3">
                                                       <small class="text-muted d-block mb-1">Completed</small>
                                                       <div class="fw-semibold text-success">
                                                            <?= number_format($completedCases); ?> cases
                                                       </div>
                                                  </div>
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
                                        <?php if (empty($agentRecentNotifications)): ?>
                                             <div class="text-center py-4">
                                                  <p class="text-muted mb-0">No recent notifications</p>
                                             </div>
                                        <?php else: ?>
                                             <div class="list-group list-group-flush">
                                                  <?php foreach ($agentRecentNotifications as $notif): ?>
                                                       <div class="list-group-item px-3">
                                                            <div class="d-flex align-items-start">
                                                                 <div class="flex-grow-1">
                                                                      <h6 class="mb-1 small">
                                                                           <?= htmlspecialchars($notif['title']) ?>
                                                                      </h6>
                                                                      <p class="mb-0 small text-muted">
                                                                           <?= htmlspecialchars(substr($notif['message'], 0, 60)) ?>...
                                                                      </p>
                                                                      <small
                                                                           class="text-muted"><?= date('d M', strtotime($notif['created_at'])) ?></small>
                                                                 </div>
                                                                 <?php if (!empty($notif['link'])): ?>
                                                                      <a href="<?= htmlspecialchars($notif['link']) ?>"
                                                                           class="btn btn-sm btn-link">
                                                                           <iconify-icon
                                                                                icon="solar:alt-arrow-right-outline"></iconify-icon>
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
                                        <h5 class="mb-0">Recent Cases</h5>
                                        <a href="cases.php" class="btn btn-sm btn-outline-primary">View All</a>
                                   </div>
                                   <div class="card-body p-0">
                                        <?php if (empty($recentCases)): ?>
                                             <div class="text-center py-5">
                                                  <div
                                                       class="quick-action-icon bg-secondary bg-opacity-10 text-secondary mx-auto mb-3">
                                                       <iconify-icon icon="solar:folder-open-outline"></iconify-icon>
                                                  </div>
                                                  <h5>No Cases Yet</h5>
                                                  <p class="text-muted mb-3">Once you start onboarding clients, their
                                                       applications will appear here.</p>
                                                  <a href="clients.php" class="btn btn-primary">
                                                       <iconify-icon
                                                            icon="solar:users-group-two-rounded-outline"></iconify-icon> View
                                                       Clients
                                                  </a>
                                             </div>
                                        <?php else: ?>
                                             <div class="table-responsive">
                                                  <table class="table table-hover mb-0">
                                                       <thead class="table-light">
                                                            <tr>
                                                                 <th>Case #</th>
                                                                 <th>Client</th>
                                                                 <th>Status</th>
                                                                 <th>Created</th>
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                            <?php foreach ($recentCases as $case): ?>
                                                                 <tr>
                                                                      <td>
                                                                           <strong><?= htmlspecialchars($case['case_number']); ?></strong>
                                                                           <div class="small text-muted">
                                                                                <?= htmlspecialchars(substr($case['title'], 0, 25)); ?>
                                                                           </div>
                                                                      </td>
                                                                      <td><?= htmlspecialchars($case['client_name'] ?? '—'); ?>
                                                                      </td>
                                                                      <td>
                                                                           <span
                                                                                class="badge <?= $case['status'] === 'active' ? 'bg-success' : ($case['status'] === 'completed' ? 'bg-primary' : 'bg-warning') ?>">
                                                                                <?= ucfirst($case['status'] ?? 'pending') ?>
                                                                           </span>
                                                                      </td>
                                                                      <td><?= isset($case['created_at']) ? date('d M Y', strtotime($case['created_at'])) : '—'; ?>
                                                                      </td>
                                                                 </tr>
                                                            <?php endforeach; ?>
                                                       </tbody>
                                                  </table>
                                             </div>
                                        <?php endif; ?>
                                   </div>
                              </div>

                              <!-- Latest Inquiries -->
                              <div class="card mt-3">
                                   <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Latest Inquiries</h5>
                                        <a href="inquiries.php" class="btn btn-sm btn-outline-primary">Manage</a>
                                   </div>
                                   <div class="card-body p-0">
                                        <?php if (empty($recentInquiries)): ?>
                                             <div class="text-center py-5">
                                                  <div
                                                       class="quick-action-icon bg-secondary bg-opacity-10 text-secondary mx-auto mb-3">
                                                       <iconify-icon icon="solar:inbox-outline"></iconify-icon>
                                                  </div>
                                                  <h5>No Inquiries Yet</h5>
                                                  <p class="text-muted mb-0">Share your referral link to start collecting
                                                       inquiries from prospects.</p>
                                             </div>
                                        <?php else: ?>
                                             <div class="table-responsive">
                                                  <table class="table table-hover mb-0">
                                                       <thead class="table-light">
                                                            <tr>
                                                                 <th>Prospect</th>
                                                                 <th>Service</th>
                                                                 <th>Status</th>
                                                                 <th>Received</th>
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                            <?php foreach ($recentInquiries as $inquiry): ?>
                                                                 <tr>
                                                                      <td>
                                                                           <div class="fw-semibold">
                                                                                <?= htmlspecialchars($inquiry['name']); ?>
                                                                           </div>
                                                                           <small
                                                                                class="text-muted"><?= htmlspecialchars($inquiry['email']); ?></small>
                                                                      </td>
                                                                      <td><?= htmlspecialchars($inquiry['service_type']); ?></td>
                                                                      <td>
                                                                           <?php
                                                                           $inqStatus = strtolower($inquiry['status'] ?? 'pending');
                                                                           $inqBadge = match ($inqStatus) {
                                                                                'converted' => 'bg-success',
                                                                                'contacted' => 'bg-info',
                                                                                'pending' => 'bg-warning',
                                                                                default => 'bg-secondary'
                                                                           };
                                                                           ?>
                                                                           <span class="badge <?= $inqBadge ?>">
                                                                                <?= ucfirst($inqStatus) ?>
                                                                           </span>
                                                                      </td>
                                                                      <td><?= isset($inquiry['created_at']) ? date('d M Y', strtotime($inquiry['created_at'])) : '—'; ?>
                                                                      </td>
                                                                 </tr>
                                                            <?php endforeach; ?>
                                                       </tbody>
                                                  </table>
                                             </div>
                                        <?php endif; ?>
                                   </div>
                              </div>

                              <!-- Latest Clients & Commissions Row -->
                              <div class="row mt-3">
                                   <div class="col-md-6">
                                        <div class="card h-100">
                                             <div class="card-header d-flex justify-content-between align-items-center">
                                                  <h5 class="mb-0">Latest Clients</h5>
                                                  <a href="clients.php" class="btn btn-sm btn-link">See all</a>
                                             </div>
                                             <div class="card-body p-0">
                                                  <?php if (!empty($recentClients)): ?>
                                                       <div class="list-group list-group-flush">
                                                            <?php foreach ($recentClients as $client): ?>
                                                                 <div
                                                                      class="list-group-item d-flex align-items-center justify-content-between">
                                                                      <div>
                                                                           <div class="fw-semibold">
                                                                                <?= htmlspecialchars($client['fullname']); ?>
                                                                           </div>
                                                                           <small
                                                                                class="text-muted"><?= htmlspecialchars($client['email']); ?></small>
                                                                      </div>
                                                                      <span
                                                                           class="badge bg-light text-primary"><?= isset($client['created_at']) ? date('M d', strtotime($client['created_at'])) : '—'; ?></span>
                                                                 </div>
                                                            <?php endforeach; ?>
                                                       </div>
                                                  <?php else: ?>
                                                       <div class="text-center py-4">
                                                            <p class="text-muted mb-0">No clients yet</p>
                                                       </div>
                                                  <?php endif; ?>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="card h-100">
                                             <div class="card-header d-flex justify-content-between align-items-center">
                                                  <h5 class="mb-0">Recent Commissions</h5>
                                                  <a href="commissions.php" class="btn btn-sm btn-link">Details</a>
                                             </div>
                                             <div class="card-body p-0">
                                                  <?php if (!empty($recentCommissions)): ?>
                                                       <div class="list-group list-group-flush">
                                                            <?php foreach ($recentCommissions as $commission): ?>
                                                                 <div
                                                                      class="list-group-item d-flex justify-content-between align-items-center">
                                                                      <div>
                                                                           <div class="fw-semibold">
                                                                                ₦<?= number_format($commission['amount'], 2); ?>
                                                                           </div>
                                                                           <small class="text-muted">Ref
                                                                                <?= htmlspecialchars($commission['payment_reference'] ?? 'N/A'); ?></small>
                                                                      </div>
                                                                      <?php
                                                                      $cStatus = strtolower($commission['status'] ?? 'pending');
                                                                      $cBadge = match ($cStatus) {
                                                                           'paid' => 'bg-success',
                                                                           'approved' => 'bg-info',
                                                                           'pending' => 'bg-warning',
                                                                           default => 'bg-secondary'
                                                                      };
                                                                      ?>
                                                                      <span
                                                                           class="badge <?= $cBadge ?>"><?= ucfirst($cStatus); ?></span>
                                                                 </div>
                                                            <?php endforeach; ?>
                                                       </div>
                                                  <?php else: ?>
                                                       <div class="text-center py-4">
                                                            <p class="text-muted mb-0">No commissions yet</p>
                                                       </div>
                                                  <?php endif; ?>
                                             </div>
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
                                        <script>document.write(new Date().getFullYear())</script> &copy; ApplyBoard
                                        Africa Ltd.
                                   </p>
                              </div>
                         </div>
                    </div>
               </footer>
               <!-- Footer End -->

          </div>
          

          <!-- Vendor Javascript -->
          <script src="assets/js/vendor.min.js"></script>

          <!-- App Javascript -->
          <script src="assets/js/app.js"></script>

</body>

</html>
<?php
include "../config/config.php";

if (!isLoggedIn('agent')) {
     header("Location: login.php");
     exit;
}

$agent = auth('agent');
$agent_id = $agent['id'];
$pageTitle = 'Client Directory';

// Get client stats
$totalClients = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM `users` WHERE `agent_id` = '$agent_id'"))['total'];
$thisMonthClients = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM `users` WHERE `agent_id` = '$agent_id' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())"))['total'];

// Fetch clients
$clientsResult = mysqli_query($conn, "SELECT * FROM `users` WHERE `agent_id` = '$agent_id' ORDER BY `created_at` DESC");
$clients = $clientsResult ? mysqli_fetch_all($clientsResult, MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="utf-8" />
     <title>My Clients | Agent Portal - ApplyBoard Africa</title>
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
                                        <h4 class="mb-0">My Clients</h4>
                                        <ol class="breadcrumb mb-0">
                                             <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                             <li class="breadcrumb-item active">Clients</li>
                                        </ol>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <!-- Stats Cards -->
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
                                                  <div class="stat-value"><?= number_format($totalClients) ?></div>
                                                  <div class="stat-label">Total Clients</div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-6 col-lg-3">
                              <div class="card stat-card h-100">
                                   <div class="card-body">
                                        <div class="d-flex align-items-center gap-3">
                                             <div class="stat-icon success">
                                                  <iconify-icon icon="solar:user-plus-outline"></iconify-icon>
                                             </div>
                                             <div>
                                                  <div class="stat-value"><?= number_format($thisMonthClients) ?></div>
                                                  <div class="stat-label">This Month</div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <!-- Clients Table -->
                    <div class="row">
                         <div class="col-12">
                              <div class="card">
                                   <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Client Directory</h5>
                                        <span class="badge bg-primary"><?= $totalClients ?> total</span>
                                   </div>
                                   <div class="card-body p-0">
                                        <?php if (!empty($clients)): ?>
                                             <div class="table-responsive">
                                                  <table class="table table-hover mb-0">
                                                       <thead class="table-light">
                                                            <tr>
                                                                 <th>Client</th>
                                                                 <th>Email</th>
                                                                 <th>Phone</th>
                                                                 <th>Joined</th>
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                            <?php foreach ($clients as $client): ?>
                                                                 <tr>
                                                                      <td>
                                                                           <div class="d-flex align-items-center gap-2">
                                                                                <div class="avatar-placeholder"
                                                                                     style="width: 36px; height: 36px; font-size: 14px;">
                                                                                     <?= strtoupper(substr($client['fullname'] ?? 'U', 0, 1)) ?>
                                                                                </div>
                                                                                <div>
                                                                                     <div class="fw-semibold">
                                                                                          <?= htmlspecialchars($client['fullname']) ?>
                                                                                     </div>
                                                                                </div>
                                                                           </div>
                                                                      </td>
                                                                      <td><?= htmlspecialchars($client['email']) ?></td>
                                                                      <td><?= htmlspecialchars($client['phone'] ?? '—') ?></td>
                                                                      <td>
                                                                           <span
                                                                                class="text-muted"><?= date('d M Y', strtotime($client['created_at'])) ?></span>
                                                                      </td>
                                                                 </tr>
                                                            <?php endforeach; ?>
                                                       </tbody>
                                                  </table>
                                             </div>
                                        <?php else: ?>
                                             <div class="text-center py-5">
                                                  <div
                                                       class="quick-action-icon bg-secondary bg-opacity-10 text-secondary mx-auto mb-3">
                                                       <iconify-icon
                                                            icon="solar:users-group-two-rounded-outline"></iconify-icon>
                                                  </div>
                                                  <h5>No Clients Yet</h5>
                                                  <p class="text-muted mb-0">Share your referral link to start onboarding
                                                       clients!</p>
                                             </div>
                                        <?php endif; ?>
                                   </div>
                              </div>
                         </div>
                    </div>

               </div>

               <!-- Footer -->
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
          </div>
     </div>

     <script src="assets/js/vendor.min.js"></script>
     <script src="assets/js/app.js"></script>
</body>

</html>
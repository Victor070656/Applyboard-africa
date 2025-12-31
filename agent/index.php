<?php
include "../config/config.php";
include "../config/case_helper.php";
// include "../config/auth_helper.php";

if (!isLoggedIn('agent')) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
     exit;
}

$agent = auth('agent');
$agent_id = $agent['id'];
$agent_code = $agent['agent_code'];

// Queries filtered by Agent (if applicable, or just show totals if schema doesn't link yet)
// We added agent_id to users.
$getUsers = mysqli_query($conn, "SELECT * FROM `users` WHERE `agent_id` = '$agent_id'");
// Inquiries (we added inquiries table)
$getInquiries = mysqli_query($conn, "SELECT * FROM `inquiries` WHERE `agent_id` = '$agent_id'");

// Cases count
$getCases = mysqli_query($conn, "SELECT COUNT(*) as count FROM `cases` WHERE `agent_id` = '$agent_id'");
$casesData = mysqli_fetch_assoc($getCases);
$casesCount = $casesData['count'];

// Active cases count
$getActiveCases = mysqli_query($conn, "SELECT COUNT(*) as count FROM `cases` WHERE `agent_id` = '$agent_id' AND `status` = 'active'");
$activeCasesData = mysqli_fetch_assoc($getActiveCases);
$activeCasesCount = $activeCasesData['count'];

// Pending Payout (commissions awaiting payment - pending or approved)
$getPendingPayout = mysqli_query($conn, "SELECT COALESCE(SUM(amount), 0) as total FROM `commissions` WHERE `agent_id` = '$agent_id' AND `status` IN ('pending', 'approved')");
$pendingPayoutData = mysqli_fetch_assoc($getPendingPayout);
$pendingPayout = floatval($pendingPayoutData['total']);

// Total earned (only from paid commissions - already received)
$getTotalEarned = mysqli_query($conn, "SELECT COALESCE(SUM(amount), 0) as total FROM `commissions` WHERE `agent_id` = '$agent_id' AND `status` = 'paid'");
$totalEarnedData = mysqli_fetch_assoc($getTotalEarned);
$totalEarned = floatval($totalEarnedData['total']);

// Lifetime earnings (all commissions - paid + pending + approved)
$getLifetimeEarnings = mysqli_query($conn, "SELECT COALESCE(SUM(amount), 0) as total FROM `commissions` WHERE `agent_id` = '$agent_id' AND `status` IN ('pending', 'approved', 'paid')");
$lifetimeEarningsData = mysqli_fetch_assoc($getLifetimeEarnings);
$lifetimeEarnings = floatval($lifetimeEarningsData['total']);

// Update agents table with correct values
mysqli_query($conn, "UPDATE `agents` SET `wallet_balance` = '$pendingPayout', `total_earned` = '$totalEarned' WHERE `id` = '$agent_id'");

// Performance and rating
updateAgentPerformance($agent_id);
$performance = getAgentPerformance($agent_id);

$usersCount = mysqli_num_rows($getUsers);
$inquiriesCount = mysqli_num_rows($getInquiries);

?>
<!DOCTYPE html>
<html lang="en">

<head>
     <!-- Title Meta -->
     <meta charset="utf-8" />
     <title>ApplyBoard Africa Ltd Agent || Dashboard</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />

     <!-- App favicon -->
     <link rel="shortcut icon" href="../images/favicon.png">

     <!-- Vendor css -->
     <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
     <!-- Icons css -->
     <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
     <!-- App css -->
     <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />
     <!-- Theme Config js -->
     <script src="assets/js/config.js"></script>
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

          <div class="page-content">
               <div class="container-fluid">

                    <!-- ========== Page Title Start ========== -->
                    <div class="row">
                         <div class="col-12">
                              <div class="page-title-box">
                                   <div class="d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0">Agent Dashboard</h4>
                                        <div>
                                             <span class="badge bg-primary fs-14">Code: <?= $agent_code ?></span>
                                             <span class="badge bg-success fs-14">Status:
                                                  <?= auth('agent')['status'] ?></span>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
                    <!-- ========== Page Title End ========== -->

                    <div class="row">
                         <!-- Card 1: My Clients -->
                         <div class="col-md-6 col-xl-3">
                              <div class="card rounded-4">
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-6">
                                                  <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                                                       <iconify-icon icon="solar:users-group-two-rounded-outline"
                                                            class="fs-32 text-primary avatar-title"></iconify-icon>
                                                  </div>
                                             </div>
                                             <div class="col-6 text-end">
                                                  <p class="text-muted mb-0 text-truncate">My Clients</p>
                                                  <h3 class="text-dark mt-2 mb-0"><?= $usersCount; ?></h3>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- Card 2: Inquiries -->
                         <div class="col-md-6 col-xl-3">
                              <div class="card rounded-4">
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-6">
                                                  <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                                                       <iconify-icon icon="solar:question-circle-outline"
                                                            class="fs-32 text-primary avatar-title"></iconify-icon>
                                                  </div>
                                             </div>
                                             <div class="col-6 text-end">
                                                  <p class="text-muted mb-0 text-truncate">Inquiries</p>
                                                  <h3 class="text-dark mt-2 mb-0"><?= $inquiriesCount; ?></h3>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- Card 3: Active Cases -->
                         <div class="col-md-6 col-xl-3">
                              <div class="card rounded-4">
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-6">
                                                  <div class="avatar-md bg-success bg-opacity-10 rounded-circle">
                                                       <iconify-icon icon="solar:folder-with-files-outline"
                                                            class="fs-32 text-success avatar-title"></iconify-icon>
                                                  </div>
                                             </div>
                                             <div class="col-6 text-end">
                                                  <p class="text-muted mb-0 text-truncate">Active Cases</p>
                                                  <h3 class="text-dark mt-2 mb-0"><?= $activeCasesCount; ?></h3>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- Card 4: Pending Commissions -->
                         <div class="col-md-6 col-xl-3">
                              <div class="card rounded-4">
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-6">
                                                  <div class="avatar-md bg-warning bg-opacity-10 rounded-circle">
                                                       <iconify-icon icon="solar:wallet-money-outline"
                                                            class="fs-32 text-warning avatar-title"></iconify-icon>
                                                  </div>
                                             </div>
                                             <div class="col-6 text-end">
                                                  <p class="text-muted mb-0 text-truncate">Pending</p>
                                                  <h3 class="text-dark mt-2 mb-0">
                                                       ₦<?= number_format($pendingPayout, 0); ?></h3>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="row">
                         <!-- Card 5: Referral Link -->
                         <div class="col-md-6 col-xl-6">
                              <div class="card rounded-4">
                                   <div class="card-body">
                                        <h5 class="card-title">My Referral Link</h5>
                                        <div class="input-group">
                                             <input type="text" class="form-control"
                                                  value="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/smile-dove/user/register.php?ref=' . $agent_code ?>"
                                                  readonly id="refLink">
                                             <button class="btn btn-outline-primary" type="button"
                                                  onclick="navigator.clipboard.writeText(document.getElementById('refLink').value); alert('Copied!');">Copy</button>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- Card 6: Performance & Rating -->
                         <div class="col-md-6 col-xl-6">
                              <div class="card rounded-4">
                                   <div class="card-body">
                                        <h5 class="card-title">Your Performance</h5>
                                        <div class="row text-center">
                                             <div class="col-6">
                                                  <h4 class="mb-0">
                                                       <?= $performance && $performance['rating_overall'] > 0 ? number_format($performance['rating_overall'], 1) : 'N/A' ?>/5
                                                  </h4>
                                                  <p class="text-muted mb-0">Overall Rating</p>
                                             </div>
                                             <div class="col-6">
                                                  <h4 class="mb-0">
                                                       <?= ucfirst($performance ? $performance['tier'] : 'bronze') ?>
                                                  </h4>
                                                  <p class="text-muted mb-0">Tier</p>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="row">
                         <div class="col-12">
                              <div class="card rounded-4">
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-md-4">
                                                  <h4 class="card-title">Pending Payout</h4>
                                                  <h3 class="text-warning">₦<?= number_format($pendingPayout, 2) ?></h3>
                                                  <p class="text-muted mb-0">Awaiting payment</p>
                                             </div>
                                             <div class="col-md-4">
                                                  <h4 class="card-title">Total Received</h4>
                                                  <h3 class="text-success">₦<?= number_format($totalEarned, 2) ?></h3>
                                                  <p class="text-muted mb-0">Already paid out</p>
                                             </div>
                                             <div class="col-md-4">
                                                  <h4 class="card-title">Lifetime Earnings</h4>
                                                  <h3 class="text-primary">₦<?= number_format($lifetimeEarnings, 2) ?>
                                                  </h3>
                                                  <p class="text-muted mb-0">Total commissions earned</p>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="row">
                         <div class="col-lg-6">
                              <div class="card rounded-4">
                                   <div class="card-body">
                                        <h4 class="card-title">Recent Inquiries</h4>
                                   </div>
                                   <div class="table-responsive">
                                        <table class="table table-striped table-centered w-100">
                                             <thead>
                                                  <tr>
                                                       <th>Name</th>
                                                       <th>Email</th>
                                                       <th>Date</th>
                                                       <th>Status</th>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <?php
                                                  if ($inquiriesCount > 0) {
                                                       $getInquiriesRecent = mysqli_query($conn, "SELECT * FROM `inquiries` WHERE `agent_id` = '$agent_id' ORDER BY `created_at` DESC LIMIT 5");
                                                       while ($row = mysqli_fetch_assoc($getInquiriesRecent)) {
                                                            ?>
                                                            <tr>
                                                                 <td><?= $row['name'] ?></td>
                                                                 <td><?= $row['email'] ?></td>
                                                                 <td><?= date("d M Y", strtotime($row['created_at'])) ?></td>
                                                                 <td><span class="badge bg-secondary"><?= $row['status'] ?></span>
                                                                 </td>
                                                            </tr>
                                                            <?php
                                                       }
                                                  } else {
                                                       echo "<tr><td colspan='4' class='text-center'>No inquiries yet.</td></tr>";
                                                  }
                                                  ?>
                                             </tbody>
                                        </table>
                                   </div>
                              </div>
                         </div>

                         <div class="col-lg-6">
                              <div class="card rounded-4">
                                   <div class="card-body">
                                        <h4 class="card-title">Recent Cases</h4>
                                   </div>
                                   <div class="table-responsive">
                                        <table class="table table-striped table-centered w-100">
                                             <thead>
                                                  <tr>
                                                       <th>Case #</th>
                                                       <th>Title</th>
                                                       <th>Stage</th>
                                                       <th>Status</th>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <?php
                                                  if ($casesCount > 0) {
                                                       $getCasesRecent = mysqli_query($conn, "SELECT * FROM `cases` WHERE `agent_id` = '$agent_id' ORDER BY `created_at` DESC LIMIT 5");
                                                       while ($row = mysqli_fetch_assoc($getCasesRecent)) {
                                                            ?>
                                                            <tr>
                                                                 <td><strong><?= $row['case_number'] ?></strong></td>
                                                                 <td><?= htmlspecialchars(substr($row['title'], 0, 20)) ?></td>
                                                                 <td><span
                                                                           class="badge bg-info"><?= ucfirst($row['stage']) ?></span>
                                                                 </td>
                                                                 <td><span
                                                                           class="badge <?= $row['status'] == 'active' ? 'bg-success' : 'bg-warning' ?>"><?= ucfirst($row['status']) ?></span>
                                                                 </td>
                                                            </tr>
                                                            <?php
                                                       }
                                                  } else {
                                                       echo "<tr><td colspan='4' class='text-center'>No cases yet.</td></tr>";
                                                  }
                                                  ?>
                                             </tbody>
                                        </table>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="row">
                         <div class="col-12">
                              <div class="card rounded-4">
                                   <div class="card-body">
                                        <h4 class="card-title">Quick Actions</h4>
                                        <div class="row">
                                             <div class="col-md-3">
                                                  <a href="clients.php" class="btn btn-light btn-sm w-100 mb-2">
                                                       <iconify-icon icon="solar:users-group-two-rounded-outline"
                                                            class="fs-20"></iconify-icon>
                                                       View All Clients
                                                  </a>
                                             </div>
                                             <div class="col-md-3">
                                                  <a href="inquiries.php" class="btn btn-light btn-sm w-100 mb-2">
                                                       <iconify-icon icon="solar:question-circle-outline"
                                                            class="fs-20"></iconify-icon>
                                                       Manage Inquiries
                                                  </a>
                                             </div>
                                             <div class="col-md-3">
                                                  <a href="cases.php" class="btn btn-light btn-sm w-100 mb-2">
                                                       <iconify-icon icon="solar:folder-with-files-outline"
                                                            class="fs-20"></iconify-icon>
                                                       View Cases
                                                  </a>
                                             </div>
                                             <div class="col-md-3">
                                                  <a href="commissions.php" class="btn btn-light btn-sm w-100 mb-2">
                                                       <iconify-icon icon="solar:wallet-money-outline"
                                                            class="fs-20"></iconify-icon>
                                                       Check Commissions
                                                  </a>
                                             </div>
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
          <script src="assets/js/pages/dashboard.js"></script>
</body>

</html>
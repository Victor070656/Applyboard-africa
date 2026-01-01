<?php
include "../config/config.php";
if (!isset($_SESSION['sdtravels_manager'])) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

// Handle Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
     $id = intval($_GET['id']);
     $action = $_GET['action'];
     $status = 'new';
     if ($action == 'contacted')
          $status = 'contacted';
     if ($action == 'resolved')
          $status = 'resolved';

     $sql = "UPDATE `inquiries` SET `status` = '$status' WHERE `id` = $id";
     if (mysqli_query($conn, $sql)) {
          echo "<script>alert('Status updated to $status'); location.href = 'inquiries.php';</script>";
     } else {
          echo "<script>alert('Error updating status');</script>";
     }
}

// Get counts
$totalInquiries = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM inquiries"))['c'];
$newInquiries = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM inquiries WHERE status = 'new'"))['c'];
$contactedInquiries = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM inquiries WHERE status = 'contacted'"))['c'];
$resolvedInquiries = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM inquiries WHERE status = 'resolved'"))['c'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="utf-8" />
     <title>Inquiries Management | ApplyBoard Africa</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <meta name="theme-color" content="#1e3a5f">
     <link rel="shortcut icon" href="../images/favicon.png">

     <!-- Google Fonts - Inter -->
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
                    <div class="page-title-box">
                         <h4>Inquiries Management</h4>
                         <ol class="breadcrumb mb-0">
                              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                              <li class="breadcrumb-item active">Inquiries</li>
                         </ol>
                    </div>

                    <!-- Stats Cards -->
                    <div class="row g-3 mb-4">
                         <div class="col-6 col-lg-3">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">Total Inquiries</p>
                                                  <h3 class="stat-value mb-1"><?= number_format($totalInquiries) ?></h3>
                                             </div>
                                             <div class="stat-icon primary">
                                                  <iconify-icon icon="solar:chat-round-line-broken"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-6 col-lg-3">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">New</p>
                                                  <h3 class="stat-value mb-1"><?= number_format($newInquiries) ?></h3>
                                             </div>
                                             <div class="stat-icon danger">
                                                  <iconify-icon icon="solar:bell-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-6 col-lg-3">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">Contacted</p>
                                                  <h3 class="stat-value mb-1"><?= number_format($contactedInquiries) ?>
                                                  </h3>
                                             </div>
                                             <div class="stat-icon warning">
                                                  <iconify-icon icon="solar:phone-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-6 col-lg-3">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">Resolved</p>
                                                  <h3 class="stat-value mb-1"><?= number_format($resolvedInquiries) ?>
                                                  </h3>
                                             </div>
                                             <div class="stat-icon success">
                                                  <iconify-icon icon="solar:check-circle-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="card">
                         <div class="card-header d-flex justify-content-between align-items-center">
                              <h5 class="mb-0"><iconify-icon icon="solar:chat-round-line-broken"
                                        class="me-2"></iconify-icon>All Inquiries</h5>
                         </div>
                         <div class="card-body p-0">
                              <div class="table-responsive">
                                   <table class="table mb-0">
                                        <thead>
                                             <tr>
                                                  <th>Date</th>
                                                  <th>Name</th>
                                                  <th>Contact</th>
                                                  <th>Agent</th>
                                                  <th>Status</th>
                                                  <th>Message</th>
                                                  <th>Action</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                             $sql = "SELECT i.*, a.fullname as agent_name FROM `inquiries` i LEFT JOIN `agents` a ON i.agent_id = a.id ORDER BY i.created_at DESC";
                                             $result = mysqli_query($conn, $sql);
                                             while ($row = mysqli_fetch_assoc($result)) {
                                                  $statusBadge = $row['status'] == 'resolved' ? 'bg-success' : ($row['status'] == 'new' ? 'bg-danger' : 'bg-warning');
                                                  ?>
                                                  <tr>
                                                       <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                                                       <td>
                                                            <div class="d-flex align-items-center gap-2">
                                                                 <div class="avatar-placeholder"
                                                                      style="width: 32px; height: 32px; font-size: 12px;">
                                                                      <?= strtoupper(substr($row['name'], 0, 1)) ?>
                                                                 </div>
                                                                 <strong><?= htmlspecialchars($row['name']) ?></strong>
                                                            </div>
                                                       </td>
                                                       <td>
                                                            <div><?= htmlspecialchars($row['email']) ?></div>
                                                            <small
                                                                 class="text-muted"><?= htmlspecialchars($row['phone']) ?></small>
                                                       </td>
                                                       <td><?= $row['agent_name'] ? htmlspecialchars($row['agent_name']) : '<span class="text-muted">Direct</span>' ?>
                                                       </td>
                                                       <td><span
                                                                 class="badge <?= $statusBadge ?>"><?= ucfirst($row['status']) ?></span>
                                                       </td>
                                                       <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                                 data-bs-toggle="modal"
                                                                 data-bs-target="#msgModal<?= $row['id'] ?>">
                                                                 <iconify-icon icon="solar:eye-outline"></iconify-icon> View
                                                            </button>

                                                            <!-- Modal -->
                                                            <div class="modal fade" id="msgModal<?= $row['id'] ?>"
                                                                 tabindex="-1" aria-hidden="true">
                                                                 <div class="modal-dialog">
                                                                      <div class="modal-content">
                                                                           <div class="modal-header">
                                                                                <h5 class="modal-title">Message from
                                                                                     <?= htmlspecialchars($row['name']) ?>
                                                                                </h5>
                                                                                <button type="button" class="btn-close"
                                                                                     data-bs-dismiss="modal"
                                                                                     aria-label="Close"></button>
                                                                           </div>
                                                                           <div class="modal-body">
                                                                                <p><?= nl2br(htmlspecialchars($row['message'])) ?>
                                                                                </p>
                                                                           </div>
                                                                      </div>
                                                                 </div>
                                                            </div>
                                                       </td>
                                                       <td>
                                                            <div class="dropdown">
                                                                 <button
                                                                      class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                                      type="button" data-bs-toggle="dropdown">Action</button>
                                                                 <ul class="dropdown-menu">
                                                                      <li><a class="dropdown-item"
                                                                                href="?action=contacted&id=<?= $row['id'] ?>">
                                                                                <iconify-icon icon="solar:phone-outline"
                                                                                     class="me-1"></iconify-icon> Mark as
                                                                                Contacted
                                                                           </a></li>
                                                                      <li><a class="dropdown-item"
                                                                                href="?action=resolved&id=<?= $row['id'] ?>">
                                                                                <iconify-icon
                                                                                     icon="solar:check-circle-outline"
                                                                                     class="me-1"></iconify-icon> Mark as
                                                                                Resolved
                                                                           </a></li>
                                                                 </ul>
                                                            </div>
                                                       </td>
                                                  </tr>
                                                  <?php
                                             }
                                             ?>
                                        </tbody>
                                   </table>
                              </div>
                         </div>
                    </div>
               </div>

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

          </div>
     </div>

     <script src="assets/js/vendor.min.js"></script>
     <script src="assets/js/app.js"></script>
</body>

</html>
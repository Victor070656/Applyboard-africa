<?php
include "../config/config.php";

if (!isLoggedIn('agent')) {
     header("Location: login.php");
     exit;
}

$agent = auth('agent');
$agent_id = $agent['id'];
$pageTitle = 'Inquiries';

// Handle Actions (Agents can mark as contacted)
if (isset($_GET['action']) && isset($_GET['id'])) {
     $id = intval($_GET['id']);
     $action = $_GET['action'];
     $status = 'new';
     if ($action == 'contacted')
          $status = 'contacted';

     $sql = "UPDATE `inquiries` SET `status` = '$status' WHERE `id` = $id AND `agent_id` = '$agent_id'";
     if (mysqli_query($conn, $sql)) {
          echo "<script>alert('Status updated to $status'); location.href = 'inquiries.php';</script>";
     } else {
          echo "<script>alert('Error updating status');</script>";
     }
}

// Get stats
$totalInquiries = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM `inquiries` WHERE `agent_id` = '$agent_id'"))['total'];
$newInquiries = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM `inquiries` WHERE `agent_id` = '$agent_id' AND `status` = 'new'"))['total'];
$contactedInquiries = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM `inquiries` WHERE `agent_id` = '$agent_id' AND `status` = 'contacted'"))['total'];

// Fetch inquiries
$inquiriesResult = mysqli_query($conn, "SELECT * FROM `inquiries` WHERE `agent_id` = '$agent_id' ORDER BY `created_at` DESC");
$inquiries = $inquiriesResult ? mysqli_fetch_all($inquiriesResult, MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="utf-8" />
     <title>Inquiries | Agent Portal - ApplyBoard Africa</title>
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
                                        <h4 class="mb-0">Inquiries</h4>
                                        <ol class="breadcrumb mb-0">
                                             <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                             <li class="breadcrumb-item active">Inquiries</li>
                                        </ol>
                                   </div>
                                   <?php if ($newInquiries > 0): ?>
                                        <span class="badge bg-danger"><?= $newInquiries ?> New</span>
                                   <?php endif; ?>
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
                                                  <iconify-icon icon="solar:question-circle-outline"></iconify-icon>
                                             </div>
                                             <div>
                                                  <div class="stat-value"><?= number_format($totalInquiries) ?></div>
                                                  <div class="stat-label">Total Inquiries</div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-6 col-lg-3">
                              <div class="card stat-card h-100">
                                   <div class="card-body">
                                        <div class="d-flex align-items-center gap-3">
                                             <div class="stat-icon warning">
                                                  <iconify-icon icon="solar:bell-bing-outline"></iconify-icon>
                                             </div>
                                             <div>
                                                  <div class="stat-value"><?= number_format($newInquiries) ?></div>
                                                  <div class="stat-label">New</div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-6 col-lg-3">
                              <div class="card stat-card h-100">
                                   <div class="card-body">
                                        <div class="d-flex align-items-center gap-3">
                                             <div class="stat-icon info">
                                                  <iconify-icon icon="solar:phone-calling-outline"></iconify-icon>
                                             </div>
                                             <div>
                                                  <div class="stat-value"><?= number_format($contactedInquiries) ?>
                                                  </div>
                                                  <div class="stat-label">Contacted</div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <!-- Inquiries Table -->
                    <div class="row">
                         <div class="col-12">
                              <div class="card">
                                   <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Inquiry List</h5>
                                        <span class="badge bg-primary"><?= $totalInquiries ?> total</span>
                                   </div>
                                   <div class="card-body p-0">
                                        <?php if (!empty($inquiries)): ?>
                                             <div class="table-responsive">
                                                  <table class="table table-hover mb-0">
                                                       <thead class="table-light">
                                                            <tr>
                                                                 <th>Contact</th>
                                                                 <th>Phone</th>
                                                                 <th>Service</th>
                                                                 <th>Status</th>
                                                                 <th>Date</th>
                                                                 <th>Action</th>
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                            <?php foreach ($inquiries as $row): ?>
                                                                 <?php
                                                                 $statusBadge = match (strtolower($row['status'] ?? 'new')) {
                                                                      'resolved', 'converted' => 'bg-success',
                                                                      'contacted' => 'bg-info',
                                                                      'new' => 'bg-danger',
                                                                      default => 'bg-warning'
                                                                 };
                                                                 ?>
                                                                 <tr>
                                                                      <td>
                                                                           <div class="d-flex align-items-center gap-2">
                                                                                <div class="avatar-placeholder"
                                                                                     style="width: 36px; height: 36px; font-size: 14px;">
                                                                                     <?= strtoupper(substr($row['fullname'] ?? $row['name'] ?? 'I', 0, 1)) ?>
                                                                                </div>
                                                                                <div>
                                                                                     <div class="fw-semibold">
                                                                                          <?= htmlspecialchars($row['fullname'] ?? $row['name']) ?>
                                                                                     </div>
                                                                                     <small
                                                                                          class="text-muted"><?= htmlspecialchars($row['email']) ?></small>
                                                                                </div>
                                                                           </div>
                                                                      </td>
                                                                      <td><?= htmlspecialchars($row['phone'] ?? '—') ?></td>
                                                                      <td><?= htmlspecialchars($row['service_type'] ?? '—') ?>
                                                                      </td>
                                                                      <td><span
                                                                                class="badge <?= $statusBadge ?>"><?= ucfirst($row['status'] ?? 'new') ?></span>
                                                                      </td>
                                                                      <td><span
                                                                                class="text-muted"><?= date('d M Y', strtotime($row['created_at'])) ?></span>
                                                                      </td>
                                                                      <td>
                                                                           <div class="d-flex gap-1">
                                                                                <button type="button" class="btn btn-sm btn-light"
                                                                                     data-bs-toggle="modal"
                                                                                     data-bs-target="#msgModal<?= $row['id'] ?>">
                                                                                     <iconify-icon
                                                                                          icon="solar:eye-outline"></iconify-icon>
                                                                                </button>
                                                                                <?php if ($row['status'] == 'new'): ?>
                                                                                     <a href="?action=contacted&id=<?= $row['id'] ?>"
                                                                                          class="btn btn-sm btn-info">
                                                                                          <iconify-icon
                                                                                               icon="solar:phone-calling-outline"></iconify-icon>
                                                                                     </a>
                                                                                <?php endif; ?>
                                                                           </div>

                                                                           <!-- Message Modal -->
                                                                           <div class="modal fade" id="msgModal<?= $row['id'] ?>"
                                                                                tabindex="-1" aria-hidden="true">
                                                                                <div class="modal-dialog">
                                                                                     <div class="modal-content">
                                                                                          <div class="modal-header">
                                                                                               <h5 class="modal-title">Inquiry
                                                                                                    Details</h5>
                                                                                               <button type="button"
                                                                                                    class="btn-close"
                                                                                                    data-bs-dismiss="modal"
                                                                                                    aria-label="Close"></button>
                                                                                          </div>
                                                                                          <div class="modal-body">
                                                                                               <div class="mb-3">
                                                                                                    <label
                                                                                                         class="fw-semibold">From:</label>
                                                                                                    <p class="mb-1">
                                                                                                         <?= htmlspecialchars($row['fullname'] ?? $row['name']) ?>
                                                                                                    </p>
                                                                                                    <small
                                                                                                         class="text-muted"><?= htmlspecialchars($row['email']) ?>
                                                                                                         •
                                                                                                         <?= htmlspecialchars($row['phone'] ?? 'No phone') ?></small>
                                                                                               </div>
                                                                                               <div class="mb-3">
                                                                                                    <label
                                                                                                         class="fw-semibold">Service
                                                                                                         Interest:</label>
                                                                                                    <p><?= htmlspecialchars($row['service_type'] ?? 'Not specified') ?>
                                                                                                    </p>
                                                                                               </div>
                                                                                               <div>
                                                                                                    <label
                                                                                                         class="fw-semibold">Message:</label>
                                                                                                    <p class="mb-0">
                                                                                                         <?= nl2br(htmlspecialchars($row['message'] ?? 'No message')) ?>
                                                                                                    </p>
                                                                                               </div>
                                                                                          </div>
                                                                                          <div class="modal-footer">
                                                                                               <?php if ($row['status'] == 'new'): ?>
                                                                                                    <a href="?action=contacted&id=<?= $row['id'] ?>"
                                                                                                         class="btn btn-info">Mark as
                                                                                                         Contacted</a>
                                                                                               <?php endif; ?>
                                                                                               <button type="button"
                                                                                                    class="btn btn-secondary"
                                                                                                    data-bs-dismiss="modal">Close</button>
                                                                                          </div>
                                                                                     </div>
                                                                                </div>
                                                                           </div>
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
                                                       <iconify-icon icon="solar:inbox-outline"></iconify-icon>
                                                  </div>
                                                  <h5>No Inquiries Yet</h5>
                                                  <p class="text-muted mb-0">Share your referral link to start receiving
                                                       inquiries!</p>
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
</body>

</html>
<?php
include "../config/config.php";
include "../config/case_helper.php";

if (!isset($_SESSION['sdtravels_manager'])) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

$manager = auth('admin');
$view = isset($_GET['view']) ? intval($_GET['view']) : null;

// Handle Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
     $id = intval($_GET['id']);
     $action = $_GET['action'];
     $status = ($action == 'approve') ? 'verified' : 'rejected';

     $sql = "UPDATE `agents` SET `status` = '$status' WHERE `id` = $id";
     if (mysqli_query($conn, $sql)) {
          logActivity($manager['id'], 'admin', 'agent_' . $action, 'agent', $id, "Agent status changed to $status");
          echo "<script>alert('Agent status updated to $status'); location.href = 'agents.php';</script>";
     } else {
          echo "<script>alert('Error updating status');</script>";
     }
}

// Get agent details if viewing
$agentDetails = null;
$agentStats = null;
if ($view) {
     $agentDetails = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `agents` WHERE `id` = $view"));
     if ($agentDetails) {
          // Get agent statistics
          $agentStats = [
               'total_referrals' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE agent_id = $view"))['c'],
               'total_cases' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM cases WHERE agent_id = $view"))['c'],
               'active_cases' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM cases WHERE agent_id = $view AND status = 'active'"))['c'],
               'completed_cases' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM cases WHERE agent_id = $view AND (status = 'completed' OR stage = 'completed')"))['c'],
               'total_earned' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(amount), 0) as c FROM commissions WHERE agent_id = $view AND status = 'paid'"))['c'],
               'pending_commission' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(amount), 0) as c FROM commissions WHERE agent_id = $view AND status IN ('pending', 'approved')"))['c'],
               'total_inquiries' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM inquiries WHERE agent_id = $view"))['c']
          ];
     }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="utf-8" />
     <title>ApplyBoard Africa Ltd || Agents</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <link rel="shortcut icon" href="../images/favicon.png">
     <link href="https://fonts.googleapis.com/css2c4ad.css?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
          rel="stylesheet">
     <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />

     <!-- Custom Dashboard css (mobile fixes) -->
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
                    <div class="row">
                         <div class="col-12">
                              <div class="page-title-box">
                                   <h4 class="mb-0"><?= $view ? 'Agent Details' : 'Manage Agents' ?></h4>
                                   <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                        <?php if ($view): ?>
                                             <li class="breadcrumb-item"><a href="agents.php">Agents</a></li>
                                             <li class="breadcrumb-item active">View Agent</li>
                                        <?php else: ?>
                                             <li class="breadcrumb-item active">Agents</li>
                                        <?php endif; ?>
                                   </ol>
                              </div>
                         </div>
                    </div>

                    <?php if ($view && $agentDetails): ?>
                         <!-- Agent Detail View -->
                         <div class="row">
                              <div class="col-12 mb-3">
                                   <a href="agents.php" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i> Back
                                        to Agents</a>
                              </div>
                         </div>

                         <!-- Stats Cards -->
                         <div class="row">
                              <div class="col-md-3">
                                   <div class="card">
                                        <div class="card-body text-center">
                                             <h6 class="text-muted">Total Referrals</h6>
                                             <h3 class="text-primary"><?= number_format($agentStats['total_referrals']) ?>
                                             </h3>
                                        </div>
                                   </div>
                              </div>
                              <div class="col-md-3">
                                   <div class="card">
                                        <div class="card-body text-center">
                                             <h6 class="text-muted">Total Cases</h6>
                                             <h3 class="text-info"><?= number_format($agentStats['total_cases']) ?></h3>
                                        </div>
                                   </div>
                              </div>
                              <div class="col-md-3">
                                   <div class="card">
                                        <div class="card-body text-center">
                                             <h6 class="text-muted">Completed Cases</h6>
                                             <h3 class="text-success"><?= number_format($agentStats['completed_cases']) ?>
                                             </h3>
                                        </div>
                                   </div>
                              </div>
                              <div class="col-md-3">
                                   <div class="card">
                                        <div class="card-body text-center">
                                             <h6 class="text-muted">Total Received</h6>
                                             <h3 class="text-success">₦<?= number_format($agentStats['total_earned'], 2) ?>
                                             </h3>
                                             <small class="text-muted">Paid commissions</small>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <div class="row">
                              <!-- Agent Info -->
                              <div class="col-lg-6">
                                   <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                             <h5 class="mb-0">Agent Information</h5>
                                             <span
                                                  class="badge bg-<?= $agentDetails['status'] == 'verified' ? 'success' : ($agentDetails['status'] == 'rejected' ? 'danger' : 'warning') ?> fs-14">
                                                  <?= strtoupper($agentDetails['status']) ?>
                                             </span>
                                        </div>
                                        <div class="card-body">
                                             <table class="table table-borderless mb-0">
                                                  <tr>
                                                       <th width="35%">Agent Code:</th>
                                                       <td><code
                                                                 class="fs-16"><?= htmlspecialchars($agentDetails['agent_code']) ?></code>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <th>Full Name:</th>
                                                       <td><?= htmlspecialchars($agentDetails['fullname']) ?></td>
                                                  </tr>
                                                  <tr>
                                                       <th>Email:</th>
                                                       <td><a
                                                                 href="mailto:<?= $agentDetails['email'] ?>"><?= htmlspecialchars($agentDetails['email']) ?></a>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <th>Phone:</th>
                                                       <td><a
                                                                 href="tel:<?= $agentDetails['phone'] ?>"><?= htmlspecialchars($agentDetails['phone']) ?></a>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <th>Address:</th>
                                                       <td><?= htmlspecialchars($agentDetails['address'] ?? 'N/A') ?></td>
                                                  </tr>
                                                  <tr>
                                                       <th>City:</th>
                                                       <td><?= htmlspecialchars($agentDetails['city'] ?? 'N/A') ?></td>
                                                  </tr>
                                                  <tr>
                                                       <th>Country:</th>
                                                       <td><?= htmlspecialchars($agentDetails['country'] ?? 'N/A') ?></td>
                                                  </tr>
                                                  <tr>
                                                       <th>Commission Rate:</th>
                                                       <td><?= number_format($agentDetails['commission_rate'] ?? 0, 2) ?>%
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <th>Joined:</th>
                                                       <td><?= date('M d, Y h:i A', strtotime($agentDetails['created_at'])) ?>
                                                       </td>
                                                  </tr>
                                             </table>
                                        </div>
                                   </div>
                              </div>

                              <!-- Bank & Financial Info -->
                              <div class="col-lg-6">
                                   <div class="card">
                                        <div class="card-header">
                                             <h5 class="mb-0">Bank & Financial Information</h5>
                                        </div>
                                        <div class="card-body">
                                             <table class="table table-borderless mb-0">
                                                  <tr>
                                                       <th width="35%">Bank Name:</th>
                                                       <td><?= htmlspecialchars($agentDetails['bank_name'] ?? 'Not provided') ?>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <th>Account Number:</th>
                                                       <td><?= htmlspecialchars($agentDetails['account_number'] ?? 'Not provided') ?>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <th>Account Name:</th>
                                                       <td><?= htmlspecialchars($agentDetails['account_name'] ?? 'Not provided') ?>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <th>Pending Payout:</th>
                                                       <td class="text-warning fw-bold">
                                                            ₦<?= number_format($agentStats['pending_commission'], 2) ?>
                                                            <small class="text-muted">(awaiting payment)</small>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <th>Total Received:</th>
                                                       <td class="text-success fw-bold">
                                                            ₦<?= number_format($agentStats['total_earned'], 2) ?>
                                                            <small class="text-muted">(already paid)</small>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <th>Lifetime Earnings:</th>
                                                       <td class="text-primary fw-bold">
                                                            ₦<?= number_format($agentStats['total_earned'] + $agentStats['pending_commission'], 2) ?>
                                                            <small class="text-muted">(all time)</small>
                                                       </td>
                                                  </tr>
                                             </table>
                                        </div>
                                   </div>

                                   <!-- Documents -->
                                   <div class="card">
                                        <div class="card-header">
                                             <h5 class="mb-0">Documents</h5>
                                        </div>
                                        <div class="card-body">
                                             <?php if ($agentDetails['documents']): ?>
                                                  <a href="../uploads/<?= $agentDetails['documents'] ?>" target="_blank"
                                                       class="btn btn-info">
                                                       <i class="ti ti-file-text me-1"></i> View Uploaded Document
                                                  </a>
                                             <?php else: ?>
                                                  <p class="text-muted mb-0">No documents uploaded</p>
                                             <?php endif; ?>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- Quick Actions -->
                         <div class="row">
                              <div class="col-12">
                                   <div class="card">
                                        <div class="card-header">
                                             <h5 class="mb-0">Quick Actions</h5>
                                        </div>
                                        <div class="card-body">
                                             <div class="d-flex gap-2 flex-wrap">
                                                  <a href="cases.php?agent=<?= $view ?>" class="btn btn-primary">
                                                       <i class="ti ti-folder me-1"></i> View Cases
                                                       (<?= $agentStats['total_cases'] ?>)
                                                  </a>
                                                  <a href="clients.php?agent=<?= $view ?>" class="btn btn-info">
                                                       <i class="ti ti-users me-1"></i> View Referrals
                                                       (<?= $agentStats['total_referrals'] ?>)
                                                  </a>
                                                  <a href="commissions.php?agent_id=<?= $view ?>" class="btn btn-success">
                                                       <i class="ti ti-coin me-1"></i> View Commissions
                                                  </a>
                                                  <a href="inquiries.php?agent=<?= $view ?>" class="btn btn-secondary">
                                                       <i class="ti ti-message me-1"></i> View Inquiries
                                                       (<?= $agentStats['total_inquiries'] ?>)
                                                  </a>
                                                  <?php if ($agentDetails['status'] == 'pending'): ?>
                                                       <a href="?action=approve&id=<?= $view ?>" class="btn btn-success"
                                                            onclick="return confirm('Approve this agent?')">
                                                            <i class="ti ti-check me-1"></i> Approve Agent
                                                       </a>
                                                       <a href="?action=reject&id=<?= $view ?>" class="btn btn-danger"
                                                            onclick="return confirm('Reject this agent?')">
                                                            <i class="ti ti-x me-1"></i> Reject Agent
                                                       </a>
                                                  <?php elseif ($agentDetails['status'] == 'verified'): ?>
                                                       <a href="?action=reject&id=<?= $view ?>" class="btn btn-warning"
                                                            onclick="return confirm('Suspend this agent?')">
                                                            <i class="ti ti-ban me-1"></i> Suspend Agent
                                                       </a>
                                                  <?php else: ?>
                                                       <a href="?action=approve&id=<?= $view ?>" class="btn btn-success"
                                                            onclick="return confirm('Reactivate this agent?')">
                                                            <i class="ti ti-check me-1"></i> Reactivate Agent
                                                       </a>
                                                  <?php endif; ?>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- Recent Cases -->
                         <div class="row">
                              <div class="col-12">
                                   <div class="card">
                                        <div class="card-header">
                                             <h5 class="mb-0">Recent Cases</h5>
                                        </div>
                                        <div class="card-body">
                                             <div class="table-responsive">
                                                  <table class="table table-striped mb-0">
                                                       <thead>
                                                            <tr>
                                                                 <th>Case #</th>
                                                                 <th>Client</th>
                                                                 <th>Type</th>
                                                                 <th>Stage</th>
                                                                 <th>Status</th>
                                                                 <th>Date</th>
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                            <?php
                                                            $recentCases = mysqli_query($conn, "SELECT c.*, u.fullname as client_name 
                                                            FROM cases c 
                                                            LEFT JOIN users u ON c.client_id = u.id 
                                                            WHERE c.agent_id = $view 
                                                            ORDER BY c.created_at DESC LIMIT 5");
                                                            if (mysqli_num_rows($recentCases) > 0):
                                                                 while ($case = mysqli_fetch_assoc($recentCases)):
                                                                      ?>
                                                                      <tr>
                                                                           <td><a
                                                                                     href="cases.php?view=<?= $case['id'] ?>"><?= $case['case_number'] ?></a>
                                                                           </td>
                                                                           <td><?= htmlspecialchars($case['client_name'] ?? 'N/A') ?>
                                                                           </td>
                                                                           <td><?= getCaseTypeLabel($case['case_type']) ?></td>
                                                                           <td><span
                                                                                     class="badge bg-info"><?= ucfirst($case['stage']) ?></span>
                                                                           </td>
                                                                           <td><span
                                                                                     class="badge bg-<?= $case['status'] == 'active' ? 'success' : ($case['status'] == 'completed' ? 'primary' : 'secondary') ?>"><?= ucfirst($case['status']) ?></span>
                                                                           </td>
                                                                           <td><?= date('M d, Y', strtotime($case['created_at'])) ?>
                                                                           </td>
                                                                      </tr>
                                                                 <?php endwhile; else: ?>
                                                                 <tr>
                                                                      <td colspan="6" class="text-center text-muted">No cases
                                                                           found</td>
                                                                 </tr>
                                                            <?php endif; ?>
                                                       </tbody>
                                                  </table>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>

                    <?php else: ?>
                         <!-- Agents List -->
                         <div class="row">
                              <div class="col-12">
                                   <div class="card">
                                        <div class="card-body">
                                             <div class="table-responsive">
                                                  <table class="table table-striped table-centered mb-0">
                                                       <thead>
                                                            <tr>
                                                                 <th>Code</th>
                                                                 <th>Name</th>
                                                                 <th>Email</th>
                                                                 <th>Phone</th>
                                                                 <th>Referrals</th>
                                                                 <th>Cases</th>
                                                                 <th>Status</th>
                                                                 <th>Action</th>
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                            <?php
                                                            $sql = "SELECT a.*, 
                                                                 COALESCE((SELECT COUNT(*) FROM users WHERE agent_id = a.id), 0) as referral_count,
                                                                 COALESCE((SELECT COUNT(*) FROM cases WHERE agent_id = a.id), 0) as case_count
                                                            FROM `agents` a 
                                                            ORDER BY `created_at` DESC";
                                                            $result = mysqli_query($conn, $sql);
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                 $statusBadge = $row['status'] == 'verified' ? 'bg-success' : ($row['status'] == 'rejected' ? 'bg-danger' : 'bg-warning');
                                                                 ?>
                                                                 <tr>
                                                                      <td><code><?= $row['agent_code'] ?></code></td>
                                                                      <td><?= htmlspecialchars($row['fullname']) ?></td>
                                                                      <td><?= htmlspecialchars($row['email']) ?></td>
                                                                      <td><?= htmlspecialchars($row['phone']) ?></td>
                                                                      <td><span
                                                                                class="badge bg-info"><?= $row['referral_count'] ?></span>
                                                                      </td>
                                                                      <td><span
                                                                                class="badge bg-primary"><?= $row['case_count'] ?></span>
                                                                      </td>
                                                                      <td><span
                                                                                class="badge <?= $statusBadge ?>"><?= strtoupper($row['status']) ?></span>
                                                                      </td>
                                                                      <td>
                                                                           <a href="?view=<?= $row['id'] ?>"
                                                                                class="btn btn-sm btn-info" title="View Details">
                                                                                <i class="ti ti-eye"></i>
                                                                           </a>
                                                                           <?php if ($row['status'] == 'pending'): ?>
                                                                                <a href="?action=approve&id=<?= $row['id'] ?>"
                                                                                     class="btn btn-sm btn-success"
                                                                                     onclick="return confirm('Approve this agent?')"
                                                                                     title="Approve">
                                                                                     <i class="ti ti-check"></i>
                                                                                </a>
                                                                                <a href="?action=reject&id=<?= $row['id'] ?>"
                                                                                     class="btn btn-sm btn-danger"
                                                                                     onclick="return confirm('Reject this agent?')"
                                                                                     title="Reject">
                                                                                     <i class="ti ti-x"></i>
                                                                                </a>
                                                                           <?php endif; ?>
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
                         </div>
                    <?php endif; ?>
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
</body>

</html>
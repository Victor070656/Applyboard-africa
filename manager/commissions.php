<?php
include "../config/config.php";
if (!isset($_SESSION['sdtravels_manager'])) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}
include "../config/case_helper.php";

$manager = auth('admin');

// Handle Commission Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == 'approve') {
        if (updateCommissionStatus($id, 'approved', $manager['id'])) {
            $msg = "Commission approved successfully";
        } else {
            $err = "Failed to approve commission";
        }
    }
    elseif ($action == 'mark_paid') {
        if (updateCommissionStatus($id, 'paid', $manager['id'])) {
            $msg = "Commission marked as paid";

            // Update agent wallet
            $comm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `commissions` WHERE `id` = '$id'"));
            if ($comm) {
                mysqli_query($conn, "UPDATE `agents` SET `wallet_balance` = `wallet_balance` + '{$comm['amount']}' WHERE `id` = '{$comm['agent_id']}'");
            }
        } else {
            $err = "Failed to update commission";
        }
    }
    elseif ($action == 'reject') {
        if (updateCommissionStatus($id, 'rejected', $manager['id'])) {
            $msg = "Commission rejected";
        } else {
            $err = "Failed to reject commission";
        }
    }
}

// Get filter values
$statusFilter = isset($_GET['status']) ? $_GET['status'] : null;
$agentFilter = isset($_GET['agent_id']) ? intval($_GET['agent_id']) : null;

// Build query
$where = ["1=1"];
if ($statusFilter) {
    $where[] = "c.status = '" . mysqli_real_escape_string($conn, $statusFilter) . "'";
}
if ($agentFilter) {
    $where[] = "c.agent_id = '$agentFilter'";
}

$whereClause = implode(' AND ', $where);
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="utf-8" />
     <title>ApplyBoard Africa Ltd || Commissions</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <link rel="shortcut icon" href="../images/favicon.png">
     <link href="https://fonts.googleapis.com/css2c4ad.css?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
     <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />
     <script src="assets/js/config.js"></script>
     <!-- Iconify -->
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
                    <h4 class="mb-0">Commission Management</h4>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1">Pending Commissions</h6>
                                <h4 class="mb-0">
                                    <?php
                                    $pending = mysqli_fetch_assoc(mysqli_query($conn,
                                        "SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total FROM `commissions` WHERE `status` = 'pending'"));
                                    echo number_format($pending['count']);
                                    ?>
                                </h4>
                                <small class="text-muted">₦<?= number_format($pending['total'], 2) ?></small>
                            </div>
                            <div class="avatar-sm bg-warning bg-opacity-10 rounded">
                                <iconify-icon icon="solar:clock-circle-outline" class="fs-24 text-warning"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1">Approved</h6>
                                <h4 class="mb-0">
                                    <?php
                                    $approved = mysqli_fetch_assoc(mysqli_query($conn,
                                        "SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total FROM `commissions` WHERE `status` = 'approved'"));
                                    echo number_format($approved['count']);
                                    ?>
                                </h4>
                                <small class="text-muted">₦<?= number_format($approved['total'], 2) ?></small>
                            </div>
                            <div class="avatar-sm bg-info bg-opacity-10 rounded">
                                <iconify-icon icon="solar:check-circle-outline" class="fs-24 text-info"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1">Paid This Month</h6>
                                <h4 class="mb-0">
                                    <?php
                                    $paid = mysqli_fetch_assoc(mysqli_query($conn,
                                        "SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total FROM `commissions` WHERE `status` = 'paid' AND YEAR(paid_date) = YEAR(CURDATE()) AND MONTH(paid_date) = MONTH(CURDATE())"));
                                    echo number_format($paid['count']);
                                    ?>
                                </h4>
                                <small class="text-muted">₦<?= number_format($paid['total'], 2) ?></small>
                            </div>
                            <div class="avatar-sm bg-success bg-opacity-10 rounded">
                                <iconify-icon icon="solar:wallet-money-outline" class="fs-24 text-success"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1">Total Paid (All Time)</h6>
                                <h4 class="mb-0">
                                    <?php
                                    $totalPaid = mysqli_fetch_assoc(mysqli_query($conn,
                                        "SELECT COALESCE(SUM(amount), 0) as total FROM `commissions` WHERE `status` = 'paid'"));
                                    echo "₦" . number_format($totalPaid['total'], 0);
                                    ?>
                                </h4>
                            </div>
                            <div class="avatar-sm bg-primary bg-opacity-10 rounded">
                                <iconify-icon icon="solar:diagram-chart-outline" class="fs-24 text-primary"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($msg)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $msg ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (isset($err)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $err ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Commission Records</h5>
                    </div>
                    <div class="card-body">
                        <!-- Filter Form -->
                        <form method="GET" class="row g-3 mb-3">
                            <div class="col-md-4">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" <?= $statusFilter == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="approved" <?= $statusFilter == 'approved' ? 'selected' : '' ?>>Approved</option>
                                    <option value="paid" <?= $statusFilter == 'paid' ? 'selected' : '' ?>>Paid</option>
                                    <option value="rejected" <?= $statusFilter == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="agent_id" class="form-select">
                                    <option value="">All Agents</option>
                                    <?php
                                    $agents = mysqli_query($conn, "SELECT id, fullname, agent_code FROM `agents` WHERE status = 'verified' ORDER BY fullname ASC");
                                    while ($agent = mysqli_fetch_assoc($agents)):
                                    ?>
                                        <option value="<?= $agent['id'] ?>" <?= $agentFilter == $agent['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($agent['fullname']) ?> (<?= $agent['agent_code'] ?>)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Agent</th>
                                        <th>Client</th>
                                        <th>Case</th>
                                        <th>Type</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT c.*, ca.case_number, ca.title as case_title,
                                                   a.fullname as agent_name, a.agent_code,
                                                   u.fullname as client_name
                                            FROM `commissions` c
                                            LEFT JOIN `cases` ca ON c.case_id = ca.id
                                            LEFT JOIN `agents` a ON c.agent_id = a.id
                                            LEFT JOIN `users` u ON c.client_id = u.id
                                            WHERE $whereClause
                                            ORDER BY c.created_at DESC";

                                    $result = mysqli_query($conn, $sql);

                                    if (mysqli_num_rows($result) == 0):
                                    ?>
                                        <tr>
                                            <td colspan="9" class="text-center">No commission records found</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php while ($row = mysqli_fetch_assoc($result)):
                                            $statusBadge = [
                                                'pending' => 'bg-warning',
                                                'approved' => 'bg-info',
                                                'paid' => 'bg-success',
                                                'rejected' => 'bg-danger',
                                                'cancelled' => 'bg-secondary'
                                            ];
                                            $badge = isset($statusBadge[$row['status']]) ? $statusBadge[$row['status']] : 'bg-secondary';
                                        ?>
                                        <tr>
                                            <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                                            <td>
                                                <?= htmlspecialchars($row['agent_name']) ?><br>
                                                <small class="text-muted"><?= htmlspecialchars($row['agent_code']) ?></small>
                                            </td>
                                            <td><?= htmlspecialchars($row['client_name']) ?></td>
                                            <td>
                                                <?php if ($row['case_number']): ?>
                                                    <?= htmlspecialchars($row['case_number']) ?><br>
                                                    <small class="text-muted"><?= htmlspecialchars(substr($row['case_title'], 0, 30)) ?></small>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= ucfirst(str_replace('_', ' ', $row['commission_type'])) ?></td>
                                            <td><?= $row['rate_percentage'] ?>%</td>
                                            <td><strong>₦<?= number_format($row['amount'], 2) ?></strong></td>
                                            <td><span class="badge <?= $badge ?>"><?= ucfirst($row['status']) ?></span></td>
                                            <td>
                                                <?php if ($row['status'] === 'pending'): ?>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="?action=approve&id=<?= $row['id'] ?>" class="btn btn-outline-success">Approve</a>
                                                        <a href="?action=reject&id=<?= $row['id'] ?>" class="btn btn-outline-danger">Reject</a>
                                                    </div>
                                                <?php elseif ($row['status'] === 'approved'): ?>
                                                    <a href="?action=mark_paid&id=<?= $row['id'] ?>" class="btn btn-sm btn-success">Mark Paid</a>
                                                <?php else: ?>
                                                    <small class="text-muted">-</small>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agent Performance Summary -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Agent Performance Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Agent</th>
                                        <th>Code</th>
                                        <th>Total Referrals</th>
                                        <th>Active Cases</th>
                                        <th>Completed Cases</th>
                                        <th>Total Earned</th>
                                        <th>Rating</th>
                                        <th>Tier</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $agentsSql = "SELECT a.*, p.rating_overall, p.tier, p.total_referrals, p.active_cases, p.completed_cases, p.total_earnings
                                                FROM `agents` a
                                                LEFT JOIN `agent_performance` p ON a.id = p.agent_id
                                                WHERE a.status = 'verified'
                                                ORDER BY p.total_earnings DESC";

                                    $agentsResult = mysqli_query($conn, $agentsSql);

                                    while ($agent = mysqli_fetch_assoc($agentsResult)):
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($agent['fullname']) ?></td>
                                        <td><?= htmlspecialchars($agent['agent_code']) ?></td>
                                        <td><?= number_format($agent['total_referrals']) ?></td>
                                        <td><?= number_format($agent['active_cases']) ?></td>
                                        <td><?= number_format($agent['completed_cases']) ?></td>
                                        <td>₦<?= number_format($agent['total_earned'], 2) ?></td>
                                        <td>
                                            <?php if ($agent['rating_overall'] > 0): ?>
                                                <span class="badge bg-<?= $agent['rating_overall'] >= 4 ? 'success' : ($agent['rating_overall'] >= 3 ? 'info' : 'warning') ?>">
                                                    <?= number_format($agent['rating_overall'], 1) ?>/5
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($agent['tier']): ?>
                                                <span class="badge bg-<?= $agent['tier'] == 'platinum' ? 'primary' : ($agent['tier'] == 'gold' ? 'warning' : ($agent['tier'] == 'silver' ? 'info' : 'secondary')) ?>">
                                                    <?= ucfirst($agent['tier']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
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
                            <script>document.write(new Date().getFullYear())</script> &copy; ApplyBoard Africa Ltd.
                       </p>
                  </div>
             </div>
        </div>
   </footer>
</div>

<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>

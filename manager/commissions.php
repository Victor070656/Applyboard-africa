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
    } elseif ($action == 'mark_paid') {
        if (updateCommissionStatus($id, 'paid', $manager['id'])) {
            $msg = "Commission marked as paid";

            // Sync agent wallet balance with total paid commissions
            $comm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `commissions` WHERE `id` = '$id'"));
            if ($comm) {
                // Recalculate total from all paid commissions
                $totalPaid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(amount), 0) as total FROM commissions WHERE agent_id = '{$comm['agent_id']}' AND status = 'paid'"))['total'];
                mysqli_query($conn, "UPDATE `agents` SET `wallet_balance` = '$totalPaid', `total_earned` = '$totalPaid' WHERE `id` = '{$comm['agent_id']}'");
            }
        } else {
            $err = "Failed to update commission";
        }
    } elseif ($action == 'reject') {
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
    <title>Commission Management | ApplyBoard Africa</title>
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
                    <h4>Commission Management</h4>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Commissions</li>
                    </ol>
                </div>

                <!-- Stats Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-lg-3">
                        <div class="stat-card card">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div>
                                        <p class="stat-label mb-1">Pending</p>
                                        <?php
                                        $pending = mysqli_fetch_assoc(mysqli_query(
                                            $conn,
                                            "SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total FROM `commissions` WHERE `status` = 'pending'"
                                        ));
                                        ?>
                                        <h3 class="stat-value mb-1"><?= number_format($pending['count']) ?></h3>
                                        <span class="stat-trend down">₦<?= number_format($pending['total']) ?></span>
                                    </div>
                                    <div class="stat-icon warning">
                                        <iconify-icon icon="solar:clock-circle-outline"></iconify-icon>
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
                                        <p class="stat-label mb-1">Approved</p>
                                        <?php
                                        $approved = mysqli_fetch_assoc(mysqli_query(
                                            $conn,
                                            "SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total FROM `commissions` WHERE `status` = 'approved'"
                                        ));
                                        ?>
                                        <h3 class="stat-value mb-1"><?= number_format($approved['count']) ?></h3>
                                        <span class="stat-trend up">₦<?= number_format($approved['total']) ?></span>
                                    </div>
                                    <div class="stat-icon info">
                                        <iconify-icon icon="solar:check-circle-outline"></iconify-icon>
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
                                        <p class="stat-label mb-1">Paid This Month</p>
                                        <?php
                                        $paid = mysqli_fetch_assoc(mysqli_query(
                                            $conn,
                                            "SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total FROM `commissions` WHERE `status` = 'paid' AND YEAR(paid_date) = YEAR(CURDATE()) AND MONTH(paid_date) = MONTH(CURDATE())"
                                        ));
                                        ?>
                                        <h3 class="stat-value mb-1"><?= number_format($paid['count']) ?></h3>
                                        <span class="stat-trend up">₦<?= number_format($paid['total']) ?></span>
                                    </div>
                                    <div class="stat-icon success">
                                        <iconify-icon icon="solar:wallet-money-outline"></iconify-icon>
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
                                        <p class="stat-label mb-1">Total Paid (All Time)</p>
                                        <?php
                                        $totalPaid = mysqli_fetch_assoc(mysqli_query(
                                            $conn,
                                            "SELECT COALESCE(SUM(amount), 0) as total FROM `commissions` WHERE `status` = 'paid'"
                                        ));
                                        ?>
                                        <h3 class="stat-value mb-1">₦<?= number_format($totalPaid['total']) ?></h3>
                                    </div>
                                    <div class="stat-icon primary">
                                        <iconify-icon icon="solar:chart-2-outline"></iconify-icon>
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
                                            <option value="pending" <?= $statusFilter == 'pending' ? 'selected' : '' ?>>
                                                Pending</option>
                                            <option value="approved" <?= $statusFilter == 'approved' ? 'selected' : '' ?>>
                                                Approved</option>
                                            <option value="paid" <?= $statusFilter == 'paid' ? 'selected' : '' ?>>Paid
                                            </option>
                                            <option value="rejected" <?= $statusFilter == 'rejected' ? 'selected' : '' ?>>
                                                Rejected</option>
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
                                                    <?= htmlspecialchars($agent['fullname']) ?>
                                                    (<?= $agent['agent_code'] ?>)
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
                                                            <small
                                                                class="text-muted"><?= htmlspecialchars($row['agent_code']) ?></small>
                                                        </td>
                                                        <td><?= htmlspecialchars($row['client_name']) ?></td>
                                                        <td>
                                                            <?php if ($row['case_number']): ?>
                                                                <?= htmlspecialchars($row['case_number']) ?><br>
                                                                <small
                                                                    class="text-muted"><?= htmlspecialchars(substr($row['case_title'], 0, 30)) ?></small>
                                                            <?php else: ?>
                                                                <span class="text-muted">N/A</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?= ucfirst(str_replace('_', ' ', $row['commission_type'])) ?></td>
                                                        <td><?= $row['rate_percentage'] ?>%</td>
                                                        <td><strong>₦<?= number_format($row['amount'], 2) ?></strong></td>
                                                        <td><span
                                                                class="badge <?= $badge ?>"><?= ucfirst($row['status']) ?></span>
                                                        </td>
                                                        <td>
                                                            <?php if ($row['status'] === 'pending'): ?>
                                                                <div class="btn-group btn-group-sm">
                                                                    <a href="?action=approve&id=<?= $row['id'] ?>"
                                                                        class="btn btn-outline-success">Approve</a>
                                                                    <a href="?action=reject&id=<?= $row['id'] ?>"
                                                                        class="btn btn-outline-danger">Reject</a>
                                                                </div>
                                                            <?php elseif ($row['status'] === 'approved'): ?>
                                                                <a href="?action=mark_paid&id=<?= $row['id'] ?>"
                                                                    class="btn btn-sm btn-success">Mark Paid</a>
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
                                                <th>Referrals</th>
                                                <th>Active</th>
                                                <th>Completed</th>
                                                <th>Total Received</th>
                                                <th>Pending Payout</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Ensure total_earned column exists
                                            $checkColumn = mysqli_query($conn, "SHOW COLUMNS FROM `agents` LIKE 'total_earned'");
                                            if (mysqli_num_rows($checkColumn) == 0) {
                                                mysqli_query($conn, "ALTER TABLE `agents` ADD COLUMN `total_earned` decimal(12,2) NOT NULL DEFAULT 0.00");
                                            }

                                            // Ensure commissions table exists
                                            mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `commissions` (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
                                        `agent_id` int(11) NOT NULL,
                                        `case_id` int(11) DEFAULT NULL,
                                        `client_id` int(11) DEFAULT NULL,
                                        `commission_type` enum('referral','case_completion','service','bonus') NOT NULL DEFAULT 'case_completion',
                                        `amount` decimal(12,2) NOT NULL,
                                        `rate_percentage` decimal(5,2) DEFAULT NULL,
                                        `case_amount` decimal(12,2) DEFAULT 0.00,
                                        `status` enum('pending','approved','paid','rejected','cancelled') NOT NULL DEFAULT 'pending',
                                        `payment_method` varchar(100) DEFAULT NULL,
                                        `payment_reference` varchar(255) DEFAULT NULL,
                                        `paid_date` datetime DEFAULT NULL,
                                        `approved_by` int(11) DEFAULT NULL,
                                        `approved_at` datetime DEFAULT NULL,
                                        `notes` text DEFAULT NULL,
                                        `created_at` datetime NOT NULL DEFAULT current_timestamp(),
                                        PRIMARY KEY (`id`),
                                        KEY `idx_agent_id` (`agent_id`),
                                        KEY `idx_case_id` (`case_id`),
                                        KEY `idx_status` (`status`)
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

                                            // Get agent performance with real-time calculated stats
                                            $agentsSql = "SELECT a.id, a.fullname, a.agent_code, a.status,
                                                    COALESCE(a.total_earned, 0) as total_earned,
                                                    COALESCE((SELECT COUNT(*) FROM users u WHERE u.agent_id = a.id), 0) as referral_count,
                                                    COALESCE((SELECT COUNT(*) FROM cases c WHERE c.agent_id = a.id AND c.status = 'active'), 0) as active_count,
                                                    COALESCE((SELECT COUNT(*) FROM cases c WHERE c.agent_id = a.id AND (c.stage = 'completed' OR c.status = 'completed')), 0) as completed_count,
                                                    COALESCE((SELECT SUM(amount) FROM commissions cm WHERE cm.agent_id = a.id AND cm.status = 'paid'), 0) as total_paid,
                                                    COALESCE((SELECT SUM(amount) FROM commissions cm WHERE cm.agent_id = a.id AND cm.status IN ('pending', 'approved')), 0) as pending_amount,
                                                    COALESCE((SELECT SUM(commission_amount) FROM cases c WHERE c.agent_id = a.id AND (c.stage = 'completed' OR c.status = 'completed')), 0) as case_commission_total
                                                FROM `agents` a
                                                WHERE a.status = 'verified'
                                                ORDER BY total_earned DESC, a.fullname ASC";

                                            $agentsResult = mysqli_query($conn, $agentsSql);

                                            if (!$agentsResult): ?>
                                                <tr>
                                                    <td colspan="8" class="text-center text-danger">Error:
                                                        <?= mysqli_error($conn) ?>
                                                    </td>
                                                </tr>
                                            <?php elseif (mysqli_num_rows($agentsResult) > 0):
                                                while ($agent = mysqli_fetch_assoc($agentsResult)):
                                                    ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($agent['fullname']) ?></td>
                                                        <td><code><?= htmlspecialchars($agent['agent_code']) ?></code></td>
                                                        <td><?= number_format($agent['referral_count']) ?></td>
                                                        <td>
                                                            <?php if ($agent['active_count'] > 0): ?>
                                                                <span
                                                                    class="badge bg-primary"><?= number_format($agent['active_count']) ?></span>
                                                            <?php else: ?>
                                                                <span class="text-muted">0</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($agent['completed_count'] > 0): ?>
                                                                <span
                                                                    class="badge bg-success"><?= number_format($agent['completed_count']) ?></span>
                                                            <?php else: ?>
                                                                <span class="text-muted">0</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            // Use the highest value: total_earned from agents, total_paid from commissions, or case_commission_total
                                                            $earnedAmount = max($agent['total_earned'], $agent['total_paid'], $agent['case_commission_total']);
                                                            ?>
                                                            ₦<?= number_format($earnedAmount, 2) ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($agent['pending_amount'] > 0): ?>
                                                                <span
                                                                    class="text-warning fw-bold">₦<?= number_format($agent['pending_amount'], 2) ?></span>
                                                            <?php else: ?>
                                                                <span class="text-muted">₦0.00</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <a href="cases.php?agent=<?= $agent['id'] ?>"
                                                                class="btn btn-sm btn-outline-primary" title="View Cases">
                                                                <i class="ti ti-folder"></i>
                                                            </a>
                                                            <a href="commissions.php?agent_id=<?= $agent['id'] ?>"
                                                                class="btn btn-sm btn-outline-info" title="View Commissions">
                                                                <i class="ti ti-coin"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                endwhile;
                                            else:
                                                ?>
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">No verified agents found
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
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
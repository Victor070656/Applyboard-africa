<?php
include "../config/config.php";
include "../config/case_helper.php";

if (!isLoggedIn('user')) {
    echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
    exit;
}

$user = auth('user');
$userId = $user['id'];

// Create payments table if not exists
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `payments` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(11) NOT NULL,
    `case_id` INT(11) DEFAULT NULL,
    `reference` VARCHAR(100) NOT NULL UNIQUE,
    `amount` DECIMAL(12,2) NOT NULL,
    `currency` VARCHAR(10) DEFAULT 'NGN',
    `status` ENUM('pending', 'success', 'failed', 'refunded') DEFAULT 'pending',
    `payment_method` VARCHAR(50) DEFAULT 'paystack',
    `case_type` VARCHAR(50) DEFAULT NULL,
    `metadata` TEXT,
    `paid_at` DATETIME DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_case_id` (`case_id`),
    INDEX `idx_reference` (`reference`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

// Get user's payments
$payments = mysqli_query($conn, "SELECT p.*, c.case_number, c.title as case_title 
    FROM payments p 
    LEFT JOIN cases c ON p.case_id = c.id 
    WHERE p.user_id = '$userId' 
    ORDER BY p.created_at DESC");

// Calculate totals
$totalPaid = 0;
$totalPending = 0;
$successCount = 0;
$pendingCount = 0;

$paymentsResult = mysqli_query($conn, "SELECT status, SUM(amount) as total, COUNT(*) as count FROM payments WHERE user_id = '$userId' GROUP BY status");
while ($row = mysqli_fetch_assoc($paymentsResult)) {
    if ($row['status'] === 'success') {
        $totalPaid = $row['total'];
        $successCount = $row['count'];
    } elseif ($row['status'] === 'pending') {
        $totalPending = $row['total'];
        $pendingCount = $row['count'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>ApplyBoard Africa Ltd || Payment History</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="../images/favicon.png">
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

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h4 class="mb-0">Payment History</h4>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Payments</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded-circle bg-success-subtle">
                                            <iconify-icon icon="solar:check-circle-outline"
                                                class="avatar-title text-success fs-3"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted mb-1">Total Paid</p>
                                        <h4 class="mb-0 text-success">₦<?= number_format($totalPaid, 2) ?></h4>
                                        <small class="text-muted"><?= $successCount ?> successful payment(s)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded-circle bg-warning-subtle">
                                            <iconify-icon icon="solar:clock-circle-outline"
                                                class="avatar-title text-warning fs-3"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted mb-1">Pending</p>
                                        <h4 class="mb-0 text-warning">₦<?= number_format($totalPending, 2) ?></h4>
                                        <small class="text-muted"><?= $pendingCount ?> pending payment(s)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded-circle bg-primary-subtle">
                                            <iconify-icon icon="solar:card-outline"
                                                class="avatar-title text-primary fs-3"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted mb-1">Total Transactions</p>
                                        <h4 class="mb-0"><?= $successCount + $pendingCount ?></h4>
                                        <small class="text-muted">All time</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payments Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><iconify-icon icon="solar:history-outline"
                                class="me-2"></iconify-icon>Transaction History</h5>
                        <a href="new_application.php" class="btn btn-primary btn-sm">
                            <iconify-icon icon="solar:add-circle-outline"></iconify-icon> New Application
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if ($payments && mysqli_num_rows($payments) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Reference</th>
                                            <th>Service Type</th>
                                            <th>Case</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($payment = mysqli_fetch_assoc($payments)): ?>
                                            <tr>
                                                <td>
                                                    <code class="small"><?= htmlspecialchars($payment['reference']) ?></code>
                                                </td>
                                                <td><?= getCaseTypeLabel($payment['case_type']) ?></td>
                                                <td>
                                                    <?php if ($payment['case_id']): ?>
                                                        <a href="cases.php?view=<?= $payment['case_id'] ?>" class="text-primary">
                                                            <?= htmlspecialchars($payment['case_number'] ?? 'View Case') ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="fw-bold">₦<?= number_format($payment['amount'], 2) ?></td>
                                                <td>
                                                    <?php
                                                    $statusClass = [
                                                        'success' => 'bg-success',
                                                        'pending' => 'bg-warning',
                                                        'failed' => 'bg-danger',
                                                        'refunded' => 'bg-info'
                                                    ];
                                                    $class = $statusClass[$payment['status']] ?? 'bg-secondary';
                                                    ?>
                                                    <span class="badge <?= $class ?>"><?= ucfirst($payment['status']) ?></span>
                                                </td>
                                                <td>
                                                    <span title="<?= $payment['created_at'] ?>">
                                                        <?= date('M d, Y', strtotime($payment['created_at'])) ?>
                                                    </span>
                                                    <?php if ($payment['paid_at']): ?>
                                                        <br><small class="text-success">Paid:
                                                            <?= date('M d, Y H:i', strtotime($payment['paid_at'])) ?></small>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <iconify-icon icon="solar:card-outline" class="text-muted"
                                    style="font-size: 64px;"></iconify-icon>
                                <h5 class="mt-3">No Payments Yet</h5>
                                <p class="text-muted">Your payment history will appear here once you make your first
                                    payment.</p>
                                <a href="new_application.php" class="btn btn-primary">
                                    <iconify-icon icon="solar:add-circle-outline"></iconify-icon> Start New Application
                                </a>
                            </div>
                        <?php endif; ?>
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
    </div>

    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.js"></script>

</body>

</html>
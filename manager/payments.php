<?php
include "../config/config.php";
include "../config/case_helper.php";

if (!isset($_SESSION['sdtravels_manager'])) {
    echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
    exit;
}

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

// Get filter parameters
$statusFilter = $_GET['status'] ?? '';
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$where = "1=1";
if ($statusFilter) {
    $where .= " AND p.status = '" . mysqli_real_escape_string($conn, $statusFilter) . "'";
}
if ($dateFrom) {
    $where .= " AND DATE(p.created_at) >= '" . mysqli_real_escape_string($conn, $dateFrom) . "'";
}
if ($dateTo) {
    $where .= " AND DATE(p.created_at) <= '" . mysqli_real_escape_string($conn, $dateTo) . "'";
}
if ($search) {
    $search = mysqli_real_escape_string($conn, $search);
    $where .= " AND (p.reference LIKE '%$search%' OR u.fullname LIKE '%$search%' OR u.email LIKE '%$search%')";
}

// Get payments
$payments = mysqli_query($conn, "SELECT p.*, u.fullname, u.email, c.case_number, c.title as case_title 
    FROM payments p 
    LEFT JOIN users u ON p.user_id = u.id 
    LEFT JOIN cases c ON p.case_id = c.id 
    WHERE $where 
    ORDER BY p.created_at DESC");

// Calculate totals
$stats = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
    SUM(CASE WHEN status = 'success' THEN amount ELSE 0 END) as total_success,
    SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END) as total_pending,
    COUNT(CASE WHEN status = 'success' THEN 1 END) as success_count,
    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_count,
    COUNT(CASE WHEN status = 'failed' THEN 1 END) as failed_count,
    COUNT(*) as total_count
    FROM payments"));

// Today's payments
$todayStats = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
    COALESCE(SUM(CASE WHEN status = 'success' THEN amount ELSE 0 END), 0) as today_amount,
    COUNT(CASE WHEN status = 'success' THEN 1 END) as today_count
    FROM payments WHERE DATE(created_at) = CURDATE()"));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>ApplyBoard Africa Ltd || Payments</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="../images/favicon.png">
    <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />
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
                            <h4 class="mb-0">Payments</h4>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Payments</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded-circle bg-success-subtle">
                                            <iconify-icon icon="solar:wallet-money-outline"
                                                class="avatar-title text-success fs-3"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted mb-1">Total Revenue</p>
                                        <h4 class="mb-0 text-success">
                                            ₦<?= number_format($stats['total_success'] ?? 0, 2) ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded-circle bg-primary-subtle">
                                            <iconify-icon icon="solar:calendar-outline"
                                                class="avatar-title text-primary fs-3"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted mb-1">Today's Revenue</p>
                                        <h4 class="mb-0 text-primary">
                                            ₦<?= number_format($todayStats['today_amount'] ?? 0, 2) ?></h4>
                                        <small class="text-muted"><?= $todayStats['today_count'] ?? 0 ?>
                                            transaction(s)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
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
                                        <h4 class="mb-0 text-warning">
                                            ₦<?= number_format($stats['total_pending'] ?? 0, 2) ?></h4>
                                        <small class="text-muted"><?= $stats['pending_count'] ?? 0 ?> pending</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded-circle bg-info-subtle">
                                            <iconify-icon icon="solar:chart-outline"
                                                class="avatar-title text-info fs-3"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted mb-1">Total Transactions</p>
                                        <h4 class="mb-0"><?= $stats['total_count'] ?? 0 ?></h4>
                                        <small class="text-success"><?= $stats['success_count'] ?? 0 ?>
                                            successful</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-3">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Search</label>
                                <input type="text" class="form-control" name="search"
                                    placeholder="Reference, name, email..." value="<?= htmlspecialchars($search) ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="success" <?= $statusFilter === 'success' ? 'selected' : '' ?>>Success
                                    </option>
                                    <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Pending
                                    </option>
                                    <option value="failed" <?= $statusFilter === 'failed' ? 'selected' : '' ?>>Failed
                                    </option>
                                    <option value="refunded" <?= $statusFilter === 'refunded' ? 'selected' : '' ?>>Refunded
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">From Date</label>
                                <input type="date" class="form-control" name="date_from"
                                    value="<?= htmlspecialchars($dateFrom) ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">To Date</label>
                                <input type="date" class="form-control" name="date_to"
                                    value="<?= htmlspecialchars($dateTo) ?>">
                            </div>
                            <div class="col-md-3 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <iconify-icon icon="solar:magnifer-outline"></iconify-icon> Filter
                                </button>
                                <a href="payments.php" class="btn btn-outline-secondary">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Payments Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><iconify-icon icon="solar:history-outline" class="me-2"></iconify-icon>Payment
                            Transactions</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($payments && mysqli_num_rows($payments) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Reference</th>
                                            <th>Customer</th>
                                            <th>Service</th>
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
                                                <td>
                                                    <strong><?= htmlspecialchars($payment['fullname'] ?? 'N/A') ?></strong>
                                                    <br><small
                                                        class="text-muted"><?= htmlspecialchars($payment['email'] ?? '') ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary-subtle text-primary">
                                                        <?= getCaseTypeLabel($payment['case_type']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($payment['case_id']): ?>
                                                        <a href="cases.php?view=<?= $payment['case_id'] ?>" class="text-primary">
                                                            <?= htmlspecialchars($payment['case_number'] ?? '#' . $payment['case_id']) ?>
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
                                                    <?= date('M d, Y', strtotime($payment['created_at'])) ?>
                                                    <br><small
                                                        class="text-muted"><?= date('H:i', strtotime($payment['created_at'])) ?></small>
                                                    <?php if ($payment['paid_at']): ?>
                                                        <br><small class="text-success">Paid:
                                                            <?= date('M d H:i', strtotime($payment['paid_at'])) ?></small>
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
                                <h5 class="mt-3">No Payments Found</h5>
                                <p class="text-muted">No payment transactions match your filter criteria.</p>
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
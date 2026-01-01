<?php
include "../config/config.php";
include "../config/case_helper.php";

if (!isLoggedIn('agent')) {
    echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
    exit;
}

$agent = auth('agent');
$pageTitle = 'Commissions & Earnings';

// Update performance on page load
updateAgentPerformance($agent['id']);
$performance = getAgentPerformance($agent['id']);

// Calculate commission stats
$commissionStats = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT 
        COALESCE(SUM(CASE WHEN status IN ('pending', 'approved') THEN amount ELSE 0 END), 0) as pending_payout,
        COALESCE(SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END), 0) as total_received,
        COALESCE(SUM(CASE WHEN status IN ('pending', 'approved', 'paid') THEN amount ELSE 0 END), 0) as lifetime_earnings,
        COUNT(*) as total_records
    FROM `commissions` WHERE `agent_id` = '{$agent['id']}'"
));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Commissions | Agent Portal - ApplyBoard Africa</title>
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
                                <h4 class="mb-0">Commissions & Earnings</h4>
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Commissions</li>
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
                                    <div class="stat-icon warning">
                                        <iconify-icon icon="solar:clock-circle-outline"></iconify-icon>
                                    </div>
                                    <div>
                                        <div class="stat-value">
                                            ₦<?= number_format($commissionStats['pending_payout']) ?></div>
                                        <div class="stat-label">Pending Payout</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Awaiting payment</small>
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
                                        <div class="stat-value">
                                            ₦<?= number_format($commissionStats['total_received']) ?></div>
                                        <div class="stat-label">Total Received</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Already paid out</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="stat-icon primary">
                                        <iconify-icon icon="solar:chart-2-outline"></iconify-icon>
                                    </div>
                                    <div>
                                        <div class="stat-value">
                                            ₦<?= number_format($commissionStats['lifetime_earnings']) ?></div>
                                        <div class="stat-label">Lifetime Earnings</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">All time total</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="stat-icon info">
                                        <iconify-icon icon="solar:star-bold"></iconify-icon>
                                    </div>
                                    <div>
                                        <div class="stat-value">
                                            <?= $performance && $performance['rating_overall'] > 0 ? number_format($performance['rating_overall'], 1) : 'N/A' ?>
                                        </div>
                                        <div class="stat-label">Rating</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small
                                        class="text-muted"><?= ucfirst($performance ? $performance['tier'] : 'bronze') ?>
                                        Tier</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Summary -->
                <?php if ($performance): ?>
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Performance Summary</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-6 col-md-3">
                                            <div class="bg-primary-subtle rounded p-3 text-center">
                                                <h3 class="mb-1"><?= number_format($performance['total_referrals']) ?></h3>
                                                <small class="text-muted">Total Referrals</small>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="bg-warning-subtle rounded p-3 text-center">
                                                <h3 class="mb-1"><?= number_format($performance['active_cases']) ?></h3>
                                                <small class="text-muted">Active Cases</small>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="bg-success-subtle rounded p-3 text-center">
                                                <h3 class="mb-1"><?= number_format($performance['completed_cases']) ?></h3>
                                                <small class="text-muted">Completed Cases</small>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="bg-info-subtle rounded p-3 text-center">
                                                <h3 class="mb-1">₦<?= number_format($performance['total_earnings'], 0) ?>
                                                </h3>
                                                <small class="text-muted">Total Earnings</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Commission History -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Commission History</h5>
                            </div>
                            <div class="card-body p-0">
                                <?php $commissions = getAgentCommissions($agent['id']); ?>
                                <?php if (empty($commissions)): ?>
                                    <div class="text-center py-5">
                                        <div
                                            class="quick-action-icon bg-secondary bg-opacity-10 text-secondary mx-auto mb-3">
                                            <iconify-icon icon="solar:wallet-money-outline"></iconify-icon>
                                        </div>
                                        <h5>No Commissions Yet</h5>
                                        <p class="text-muted mb-0">Commission records will appear here once clients complete
                                            payments.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Case</th>
                                                    <th>Client</th>
                                                    <th>Type</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($commissions as $row):
                                                    $statusBadge = match (strtolower($row['status'] ?? 'pending')) {
                                                        'paid' => 'bg-success',
                                                        'approved' => 'bg-info',
                                                        'pending' => 'bg-warning',
                                                        'rejected' => 'bg-danger',
                                                        default => 'bg-secondary'
                                                    };
                                                    ?>
                                                    <tr>
                                                        <td><span
                                                                class="text-muted"><?= date('d M Y', strtotime($row['created_at'])) ?></span>
                                                        </td>
                                                        <td>
                                                            <?php if ($row['case_number']): ?>
                                                                <strong><?= htmlspecialchars($row['case_number']) ?></strong>
                                                                <div class="small text-muted">
                                                                    <?= htmlspecialchars(substr($row['case_title'] ?? '', 0, 25)) ?>
                                                                </div>
                                                            <?php else: ?>
                                                                <span class="text-muted">N/A</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $client = mysqli_fetch_assoc(mysqli_query($conn, "SELECT `fullname` FROM `users` WHERE `id` = '{$row['client_id']}'"));
                                                            echo htmlspecialchars($client['fullname'] ?? 'N/A');
                                                            ?>
                                                        </td>
                                                        <td><?= ucfirst(str_replace('_', ' ', $row['commission_type'])) ?></td>
                                                        <td><strong>₦<?= number_format($row['amount'], 2) ?></strong></td>
                                                        <td><span
                                                                class="badge <?= $statusBadge ?>"><?= ucfirst($row['status']) ?></span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
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
    </div>

    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>

</html>
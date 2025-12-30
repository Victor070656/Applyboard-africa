<?php
include "../config/config.php";
include "../config/case_helper.php";

if (!isLoggedIn('agent')) {
    echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
    exit;
}

$agent = auth('agent');

// Update performance on page load
updateAgentPerformance($agent['id']);
$performance = getAgentPerformance($agent['id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>ApplyBoard Africa Ltd Agent || Commissions</title>
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
                            <h4 class="mb-0">Commissions & Earnings</h4>
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
                                        <h6 class="text-muted mb-1">Wallet Balance</h6>
                                        <h4 class="mb-0">₦<?= number_format($agent['wallet_balance'], 2) ?></h4>
                                    </div>
                                    <div class="avatar-sm bg-success bg-opacity-10 rounded">
                                        <iconify-icon icon="solar:wallet-money-outline"
                                            class="fs-24 text-success"></iconify-icon>
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
                                        <h6 class="text-muted mb-1">Total Earned</h6>
                                        <h4 class="mb-0">₦<?= number_format($agent['total_earned'], 2) ?></h4>
                                    </div>
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded">
                                        <iconify-icon icon="solar:diagram-chart-outline"
                                            class="fs-24 text-primary"></iconify-icon>
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
                                        <h6 class="text-muted mb-1">Pending</h6>
                                        <h4 class="mb-0">
                                            <?php
                                            $pending = mysqli_fetch_assoc(mysqli_query(
                                                $conn,
                                                "SELECT COALESCE(SUM(amount), 0) as total FROM `commissions` WHERE `agent_id` = '{$agent['id']}' AND `status` = 'pending'"
                                            ));
                                            echo "₦" . number_format($pending['total'], 0);
                                            ?>
                                        </h4>
                                    </div>
                                    <div class="avatar-sm bg-warning bg-opacity-10 rounded">
                                        <iconify-icon icon="solar:clock-circle-outline"
                                            class="fs-24 text-warning"></iconify-icon>
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
                                        <h6 class="text-muted mb-1">Your Rating</h6>
                                        <h4 class="mb-0">
                                            <?= $performance && $performance['rating_overall'] > 0 ? number_format($performance['rating_overall'], 1) . '/5' : 'N/A' ?>
                                        </h4>
                                        <small
                                            class="text-muted"><?= ucfirst($performance ? $performance['tier'] : 'bronze') ?>
                                            Tier</small>
                                    </div>
                                    <div class="avatar-sm bg-info bg-opacity-10 rounded">
                                        <iconify-icon icon="solar:star-bold" class="fs-24 text-info"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Stats -->
                <?php if ($performance): ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Performance Summary</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <h3><?= number_format($performance['total_referrals']) ?></h3>
                                            <p class="text-muted mb-0">Total Referrals</p>
                                        </div>
                                        <div class="col-md-3">
                                            <h3><?= number_format($performance['active_cases']) ?></h3>
                                            <p class="text-muted mb-0">Active Cases</p>
                                        </div>
                                        <div class="col-md-3">
                                            <h3><?= number_format($performance['completed_cases']) ?></h3>
                                            <p class="text-muted mb-0">Completed Cases</p>
                                        </div>
                                        <div class="col-md-3">
                                            <h3>₦<?= number_format($performance['total_earnings'], 0) ?></h3>
                                            <p class="text-muted mb-0">Total Earnings</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Commission History</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-centered mb-0">
                                        <thead>
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
                                            <?php
                                            $commissions = getAgentCommissions($agent['id']);

                                            if (empty($commissions)):
                                                ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">No commission records found</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($commissions as $row):
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
                                                            <?php if ($row['case_number']): ?>
                                                                <?= htmlspecialchars($row['case_number']) ?>
                                                                <br><small
                                                                    class="text-muted"><?= htmlspecialchars(substr($row['case_title'], 0, 30)) ?></small>
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
                                                                class="badge <?= $badge ?>"><?= ucfirst($row['status']) ?></span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
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
    </div>

    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>

</html>
<?php
include "../config/config.php";
include "../config/case_helper.php";
include "../config/function.php";

if (!isLoggedIn('user')) {
    echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
    exit;
}

$user = auth('user');
$success = false;
$error = '';
$caseId = null;

// Get reference from session or URL
$reference = $_SESSION['ref'] ?? $_GET['reference'] ?? '';

if (empty($reference)) {
    $error = "No payment reference found.";
} else {
    // Verify payment with Paystack
    $paymentVerified = confirmTransaction($reference);

    if ($paymentVerified) {
        // Get payment record
        $paymentQuery = mysqli_query($conn, "SELECT * FROM payments WHERE reference = '" . mysqli_real_escape_string($conn, $reference) . "'");

        if ($paymentQuery && mysqli_num_rows($paymentQuery) > 0) {
            $payment = mysqli_fetch_assoc($paymentQuery);

            // Check if already processed
            if ($payment['status'] === 'success' && $payment['case_id']) {
                $success = true;
                $caseId = $payment['case_id'];
            } else if ($payment['status'] === 'pending') {
                // Process payment and create case
                $metadata = json_decode($payment['metadata'], true);

                if ($metadata) {
                    // Get agent ID from user (referral tracking)
                    // Verify the agent is still verified before assigning case
                    $agentId = 0;
                    if (isset($user['agent_id']) && $user['agent_id'] > 0) {
                        $verifyAgent = mysqli_query($conn, "SELECT `id` FROM `agents` WHERE `id` = '{$user['agent_id']}' AND `status` = 'verified'");
                        if ($verifyAgent && mysqli_num_rows($verifyAgent) > 0) {
                            $agentId = $user['agent_id'];
                        }
                        // If agent is not verified, case is created without agent assignment
                    }

                    // Prepare case data
                    $caseData = [
                        'client_id' => $user['id'],
                        'agent_id' => $agentId,
                        'case_type' => $metadata['case_type'],
                        'title' => $metadata['title'],
                        'description' => $metadata['description'] ?? '',
                        'destination_country' => $metadata['destination_country'],
                        'institution' => $metadata['institution'] ?? '',
                        'program' => $metadata['program'] ?? '',
                        'intake' => $metadata['intake'] ?? '',
                        'amount' => $metadata['amount'],
                        'commission' => $metadata['commission'] ?? 0,
                        'created_by' => $user['id'],
                        'created_by_type' => 'client'
                    ];

                    $caseId = createCase($caseData);

                    if ($caseId) {
                        // Update payment record
                        mysqli_query($conn, "UPDATE payments SET 
                            status = 'success', 
                            case_id = '$caseId', 
                            paid_at = NOW() 
                            WHERE reference = '" . mysqli_real_escape_string($conn, $reference) . "'");

                        // Create notification for user
                        createNotification(
                            $user['id'],
                            'client',
                            'Payment Successful',
                            'Your payment of ₦' . number_format($payment['amount'], 2) . ' was successful. Your application has been submitted.',
                            'success',
                            'cases.php?id=' . $caseId
                        );

                        // Create notification for agent if assigned
                        if ($agentId) {
                            createNotification(
                                $agentId,
                                'agent',
                                'New Case Assigned',
                                'A new ' . getCaseTypeLabel($metadata['case_type']) . ' case has been assigned to you.',
                                'info',
                                'cases.php?id=' . $caseId
                            );
                        }

                        // Log activity
                        logActivity($user['id'], 'client', 'payment', 'payment', $payment['id'], 'Paid ₦' . number_format($payment['amount'], 2) . ' for ' . getCaseTypeLabel($metadata['case_type']));

                        $success = true;

                        // Clear session data
                        unset($_SESSION['pending_application']);
                        unset($_SESSION['ref']);
                    } else {
                        $error = "Payment successful but failed to create application. Please contact support with reference: " . $reference;
                    }
                } else {
                    $error = "Payment verified but application data not found. Please contact support with reference: " . $reference;
                }
            } else {
                $error = "Payment already processed or failed.";
            }
        } else {
            $error = "Payment record not found. Please contact support.";
        }
    } else {
        // Payment failed
        mysqli_query($conn, "UPDATE payments SET status = 'failed' WHERE reference = '" . mysqli_real_escape_string($conn, $reference) . "'");
        $error = "Payment verification failed. If you were charged, please contact support with reference: " . $reference;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>ApplyBoard Africa Ltd || Payment Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="../images/favicon.png">
    <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/config.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
    <style>
        .status-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .success-icon {
            color: #198754;
        }

        .error-icon {
            color: #dc3545;
        }
    </style>
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
                            <h4 class="mb-0">Payment Status</h4>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="new_application.php">New Application</a></li>
                                <li class="breadcrumb-item active">Payment</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <?php if ($success): ?>
                                    <iconify-icon icon="solar:check-circle-bold"
                                        class="status-icon success-icon"></iconify-icon>
                                    <h3 class="text-success mb-3">Payment Successful!</h3>
                                    <p class="text-muted mb-4">
                                        Your payment has been confirmed and your application has been submitted
                                        successfully.
                                        Our team will review your application and get back to you soon.
                                    </p>

                                    <?php if ($reference): ?>
                                        <div class="bg-light rounded p-3 mb-4">
                                            <small class="text-muted">Payment Reference</small>
                                            <div class="fw-bold"><?= htmlspecialchars($reference) ?></div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                                        <?php if ($caseId): ?>
                                            <a href="cases.php?view=<?= $caseId ?>" class="btn btn-primary">
                                                <iconify-icon icon="solar:eye-outline"></iconify-icon> View My Application
                                            </a>
                                        <?php endif; ?>
                                        <a href="cases.php" class="btn btn-outline-primary">
                                            <iconify-icon icon="solar:folder-outline"></iconify-icon> All Cases
                                        </a>
                                        <a href="new_application.php" class="btn btn-outline-secondary">
                                            <iconify-icon icon="solar:add-circle-outline"></iconify-icon> New Application
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <iconify-icon icon="solar:close-circle-bold"
                                        class="status-icon error-icon"></iconify-icon>
                                    <h3 class="text-danger mb-3">Payment Failed</h3>
                                    <p class="text-muted mb-4">
                                        <?= htmlspecialchars($error) ?>
                                    </p>

                                    <?php if ($reference): ?>
                                        <div class="bg-light rounded p-3 mb-4">
                                            <small class="text-muted">Reference (Save this)</small>
                                            <div class="fw-bold text-danger"><?= htmlspecialchars($reference) ?></div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                                        <a href="new_application.php" class="btn btn-primary">
                                            <iconify-icon icon="solar:refresh-outline"></iconify-icon> Try Again
                                        </a>
                                        <a href="mailto:support@applyboardafrica.com?subject=Payment Issue - <?= htmlspecialchars($reference) ?>"
                                            class="btn btn-outline-danger">
                                            <iconify-icon icon="solar:letter-outline"></iconify-icon> Contact Support
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- What's Next Card -->
                        <?php if ($success): ?>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><iconify-icon icon="solar:info-circle-outline"
                                            class="me-2"></iconify-icon>What's Next?</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex mb-3">
                                        <div class="flex-shrink-0">
                                            <span class="badge bg-primary rounded-pill me-2">1</span>
                                        </div>
                                        <div>
                                            <strong>Application Review</strong>
                                            <p class="text-muted small mb-0">Our team will review your application within
                                                24-48 hours.</p>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="flex-shrink-0">
                                            <span class="badge bg-primary rounded-pill me-2">2</span>
                                        </div>
                                        <div>
                                            <strong>Document Verification</strong>
                                            <p class="text-muted small mb-0">Upload any remaining documents through your
                                                case dashboard.</p>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="flex-shrink-0">
                                            <span class="badge bg-primary rounded-pill me-2">3</span>
                                        </div>
                                        <div>
                                            <strong>Progress Updates</strong>
                                            <p class="text-muted small mb-0">Track your application progress and receive
                                                notifications.</p>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <span class="badge bg-success rounded-pill me-2">4</span>
                                        </div>
                                        <div>
                                            <strong>Completion</strong>
                                            <p class="text-muted small mb-0">Receive your final outcome and next steps.</p>
                                        </div>
                                    </div>
                                </div>
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
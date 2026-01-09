<?php
include "../config/config.php";
if (!isset($_SESSION['sdtravels_manager'])) {
    echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

$manager = auth('admin');
$loanId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch loan details
$sql = "SELECT sl.*, u.fullname as client_name, u.email as client_email, u.phone as client_phone,
               u.address as client_address, u.city as client_city, u.country as client_country,
               u.date_of_birth as client_dob, a.fullname as agent_name
        FROM student_loans sl
        LEFT JOIN users u ON sl.user_id = u.id
        LEFT JOIN agents a ON u.agent_id = a.id
        WHERE sl.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $loanId);
$stmt->execute();
$result = $stmt->get_result();
$loan = $result->fetch_assoc();
$stmt->close();

if (!$loan) {
    echo "<script>alert('Loan not found'); location.href = 'student_loans.php';</script>";
    exit();
}

// Fetch loan documents
$docSql = "SELECT * FROM loan_documents WHERE loan_id = ? ORDER BY uploaded_at DESC";
$docStmt = $conn->prepare($docSql);
$docStmt->bind_param("i", $loanId);
$docStmt->execute();
$docResult = $docStmt->get_result();
$documents = [];
while ($row = $docResult->fetch_assoc()) {
    $documents[] = $row;
}
$docStmt->close();

// Handle Status Update
if (isset($_POST['update_status'])) {
    $newStatus = mysqli_real_escape_string($conn, $_POST['new_status']);
    $approvedAmount = !empty($_POST['approved_amount']) ? floatval($_POST['approved_amount']) : null;
    $reviewNotes = isset($_POST['review_notes']) ? mysqli_real_escape_string($conn, $_POST['review_notes']) : '';
    $interestRate = !empty($_POST['interest_rate']) ? floatval($_POST['interest_rate']) : $loan['interest_rate'];
    $repaymentPeriod = !empty($_POST['repayment_period']) ? intval($_POST['repayment_period']) : $loan['repayment_period'];

    $setFields = ["`status` = '$newStatus'", "`interest_rate` = '$interestRate'", "`repayment_period` = '$repaymentPeriod'"];

    if ($newStatus == 'under_review') {
        $setFields[] = "`review_date` = NOW()";
    } elseif ($newStatus == 'approved') {
        $setFields[] = "`approval_date` = NOW()";
        $setFields[] = "`approved_by` = '{$manager['id']}'";
        $amountToApprove = $approvedAmount ?: $loan['loan_amount_requested'];
        $setFields[] = "`loan_amount_approved` = '$amountToApprove'";

        // Calculate monthly repayment
        $monthlyRate = $interestRate / 100 / 12;
        if ($monthlyRate > 0) {
            $monthlyRepayment = $amountToApprove * ($monthlyRate * pow(1 + $monthlyRate, $repaymentPeriod)) / (pow(1 + $monthlyRate, $repaymentPeriod) - 1);
        } else {
            $monthlyRepayment = $amountToApprove / $repaymentPeriod;
        }
        $setFields[] = "`monthly_repayment` = '" . round($monthlyRepayment, 2) . "'";
        $setFields[] = "`remaining_balance` = '$amountToApprove'";
    } elseif ($newStatus == 'rejected') {
        $setFields[] = "`rejection_reason` = '$reviewNotes'";
    } elseif ($newStatus == 'disbursed') {
        $setFields[] = "`disbursement_date` = NOW()";
    }

    if ($reviewNotes && $newStatus != 'rejected') {
        $setFields[] = "`review_notes` = '$reviewNotes'";
    }

    $sql = "UPDATE `student_loans` SET " . implode(', ', $setFields) . " WHERE `id` = '$loanId'";
    if (mysqli_query($conn, $sql)) {
        $msg = "Loan status updated successfully";

        // Log activity
        if (function_exists('logActivity')) {
            logActivity($manager['id'], 'admin', 'loan_status_updated', 'loan', $loanId, "Loan {$loan['loan_number']} status changed to $newStatus");
        }

        // Refresh loan data
        $loan = mysqli_fetch_assoc(mysqli_query($conn, $sql = "SELECT sl.*, u.fullname as client_name, u.email as client_email, u.phone as client_phone,
            u.address as client_address, u.city as client_city, u.country as client_country,
            u.date_of_birth as client_dob, a.fullname as agent_name
            FROM student_loans sl
            LEFT JOIN users u ON sl.user_id = u.id
            LEFT JOIN agents a ON u.agent_id = a.id
            WHERE sl.id = '$loanId'"));
    } else {
        $err = "Failed to update loan status";
    }
}

// Handle document verification
if (isset($_POST['verify_document']) && isset($_POST['document_id'])) {
    $docId = intval($_POST['document_id']);
    $docStatus = mysqli_real_escape_string($conn, $_POST['document_status']);

    mysqli_query($conn, "UPDATE loan_documents SET status = '$docStatus' WHERE id = '$docId'");
    $msg = "Document status updated";

    // Refresh documents
    $docResult = mysqli_query($conn, "SELECT * FROM loan_documents WHERE loan_id = '$loanId' ORDER BY uploaded_at DESC");
    $documents = [];
    while ($row = $docResult->fetch_assoc()) {
        $documents[] = $row;
    }
}

// Get status badge class
function getStatusBadge($status) {
    return match($status) {
        'pending' => 'bg-warning',
        'under_review' => 'bg-info',
        'approved' => 'bg-primary',
        'rejected' => 'bg-danger',
        'disbursed' => 'bg-success',
        default => 'bg-secondary'
    };
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Loan Details: <?= htmlspecialchars($loan['loan_number']) ?> | ApplyBoard Africa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="../images/favicon.png">
    <meta name="theme-color" content="#1e3a5f">

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
        <!-- Topbar -->
        <?php include "partials/header.php"; ?>

        <!-- Sidebar -->
        <?php include "partials/sidebar.php"; ?>

        <!-- Page Content -->
        <div class="page-content">
            <div class="container-fluid">

                <!-- Page Title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">Loan: <?= htmlspecialchars($loan['loan_number']) ?></h4>
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="student_loans.php">Student Loans</a></li>
                                    <li class="breadcrumb-item active">Loan Details</li>
                                </ol>
                            </div>
                            <a href="student_loans.php" class="btn btn-outline-secondary btn-sm">
                                <iconify-icon icon="solar:alt-arrow-left-outline"></iconify-icon> Back to Loans
                            </a>
                        </div>
                    </div>
                </div>

                <?php if (isset($msg)): ?>
                    <div class="alert alert-success alert-dismissible fade show"><?= htmlspecialchars($msg) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($err)): ?>
                    <div class="alert alert-danger alert-dismissible fade show"><?= htmlspecialchars($err) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Loan Status Header -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="mb-1"><?= htmlspecialchars($loan['full_name'] ?: $loan['client_name']) ?></h5>
                                <p class="text-muted mb-0">
                                    <iconify-icon icon="solar:letter-outline"></iconify-icon> <?= htmlspecialchars($loan['email'] ?: $loan['client_email']) ?>
                                    <?php if ($loan['phone'] || $loan['client_phone']): ?>
                                        | <iconify-icon icon="solar:phone-outline"></iconify-icon> <?= htmlspecialchars($loan['phone'] ?: $loan['client_phone']) ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <span class="badge <?= getStatusBadge($loan['status']) ?> fs-6"><?= ucfirst(str_replace('_', ' ', $loan['status'])) ?></span>
                                <div class="mt-1">
                                    <small class="text-muted">Applied: <?= date('M d, Y g:i A', strtotime($loan['submission_date'])) ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-lg-8">
                        <!-- Loan Details Card -->
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Loan Details</h5>
                                <span class="badge bg-light text-dark"><?= ucfirst(str_replace('_', ' ', $loan['loan_type'])) ?></span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Loan Number:</strong></p>
                                        <p class="text-primary mb-3"><?= htmlspecialchars($loan['loan_number']) ?></p>

                                        <p class="mb-1"><strong>Amount Requested:</strong></p>
                                        <p class="mb-3 fs-5"><?= number_format($loan['loan_amount_requested'], 2) ?> <?= htmlspecialchars($loan['currency']) ?></p>

                                        <?php if ($loan['loan_amount_approved']): ?>
                                            <p class="mb-1"><strong>Amount Approved:</strong></p>
                                            <p class="text-success mb-3 fs-5"><?= number_format($loan['loan_amount_approved'], 2) ?> <?= htmlspecialchars($loan['currency']) ?></p>
                                        <?php endif; ?>

                                        <p class="mb-1"><strong>Purpose:</strong></p>
                                        <p class="mb-3"><?= htmlspecialchars($loan['purpose'] ?: 'N/A') ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Interest Rate:</strong> <?= $loan['interest_rate'] ?>% per annum</p>
                                        <p class="mb-1"><strong>Repayment Period:</strong> <?= $loan['repayment_period'] ?> months</p>
                                        <?php if ($loan['monthly_repayment']): ?>
                                            <p class="mb-1"><strong>Monthly Repayment:</strong> <?= number_format($loan['monthly_repayment'], 2) ?> <?= htmlspecialchars($loan['currency']) ?></p>
                                        <?php endif; ?>

                                        <?php if ($loan['review_date']): ?>
                                            <p class="mb-1"><strong>Review Date:</strong> <?= date('M d, Y', strtotime($loan['review_date'])) ?></p>
                                        <?php endif; ?>

                                        <?php if ($loan['approval_date']): ?>
                                            <p class="mb-1"><strong>Approval Date:</strong> <?= date('M d, Y', strtotime($loan['approval_date'])) ?></p>
                                        <?php endif; ?>

                                        <?php if ($loan['disbursement_date']): ?>
                                            <p class="mb-1"><strong>Disbursement Date:</strong> <?= date('M d, Y', strtotime($loan['disbursement_date'])) ?></p>
                                        <?php endif; ?>

                                        <?php if ($loan['rejection_reason']): ?>
                                            <p class="mb-1 text-danger"><strong>Rejection Reason:</strong> <?= htmlspecialchars($loan['rejection_reason']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if ($loan['review_notes']): ?>
                                    <hr>
                                    <p class="mb-1"><strong>Review Notes:</strong></p>
                                    <p><?= nl2br(htmlspecialchars($loan['review_notes'])) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Program Information Card -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Program Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Program Name:</strong></p>
                                        <p class="mb-3"><?= htmlspecialchars($loan['program_name'] ?: 'N/A') ?></p>

                                        <p class="mb-1"><strong>Institution:</strong></p>
                                        <p class="mb-3"><?= htmlspecialchars($loan['institution_name'] ?: 'N/A') ?></p>

                                        <p class="mb-1"><strong>Course Duration:</strong></p>
                                        <p class="mb-3"><?= $loan['course_duration'] ? $loan['course_duration'] . ' months' : 'N/A' ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Program Start Date:</strong></p>
                                        <p class="mb-3"><?= $loan['program_start_date'] ? date('M d, Y', strtotime($loan['program_start_date'])) : 'N/A' ?></p>

                                        <p class="mb-1"><strong>Program End Date:</strong></p>
                                        <p class="mb-3"><?= $loan['program_end_date'] ? date('M d, Y', strtotime($loan['program_end_date'])) : 'N/A' ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Information Card -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Financial Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Employment Status:</strong></p>
                                        <p class="mb-3"><?= ucfirst(str_replace('_', ' ', $loan['employment_status'])) ?></p>

                                        <p class="mb-1"><strong>Employer:</strong></p>
                                        <p class="mb-3"><?= htmlspecialchars($loan['employer_name'] ?: 'N/A') ?></p>

                                        <p class="mb-1"><strong>Monthly Income:</strong></p>
                                        <p class="mb-3"><?= $loan['monthly_income'] ? number_format($loan['monthly_income'], 2) . ' ' . $loan['currency'] : 'N/A' ?></p>

                                        <p class="mb-1"><strong>Income Source:</strong></p>
                                        <p class="mb-3"><?= htmlspecialchars($loan['income_source'] ?: 'N/A') ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Has Collateral:</strong>
                                            <span class="badge <?= $loan['has_collateral'] ? 'bg-success' : 'bg-secondary' ?>"><?= $loan['has_collateral'] ? 'Yes' : 'No' ?></span>
                                        </p>
                                        <?php if ($loan['has_collateral']): ?>
                                            <p class="mb-1"><strong>Collateral Type:</strong> <?= htmlspecialchars($loan['collateral_type']) ?></p>
                                            <p class="mb-1"><strong>Collateral Value:</strong> <?= number_format($loan['collateral_value'], 2) ?> <?= htmlspecialchars($loan['currency']) ?></p>
                                        <?php endif; ?>

                                        <p class="mb-1 mt-3"><strong>Has Guarantor:</strong>
                                            <span class="badge <?= $loan['has_guarantor'] ? 'bg-success' : 'bg-secondary' ?>"><?= $loan['has_guarantor'] ? 'Yes' : 'No' ?></span>
                                        </p>
                                        <?php if ($loan['has_guarantor']): ?>
                                            <p class="mb-1"><strong>Guarantor Name:</strong> <?= htmlspecialchars($loan['guarantor_name']) ?></p>
                                            <p class="mb-1"><strong>Guarantor Email:</strong> <?= htmlspecialchars($loan['guarantor_email']) ?></p>
                                            <p class="mb-1"><strong>Guarantor Phone:</strong> <?= htmlspecialchars($loan['guarantor_phone']) ?></p>
                                            <p class="mb-1"><strong>Relationship:</strong> <?= htmlspecialchars($loan['guarantor_relationship']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Disbursement Bank Account Card -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Disbursement Bank Account</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($loan['bank_name']): ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Bank Name:</strong></p>
                                            <p class="mb-3"><?= htmlspecialchars($loan['bank_name']) ?></p>

                                            <p class="mb-1"><strong>Account Number:</strong></p>
                                            <p class="mb-3"><?= htmlspecialchars($loan['account_number']) ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Account Name:</strong></p>
                                            <p class="mb-3"><?= htmlspecialchars($loan['account_name']) ?></p>

                                            <p class="mb-1"><strong>Account Type:</strong></p>
                                            <p class="mb-3"><?= ucfirst($loan['account_type']) ?></p>
                                        </div>
                                    </div>
                                    <div class="alert alert-info mb-0">
                                        <iconify-icon icon="solar:info-circle-outline"></iconify-icon>
                                        Loan funds will be disbursed to this account upon approval.
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-warning mb-0">
                                        <iconify-icon icon="solar:danger-triangle-outline"></iconify-icon>
                                        <strong>Warning:</strong> No bank account details provided. The applicant needs to add their disbursement bank account details.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Documents Card -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Uploaded Documents</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($documents)): ?>
                                    <p class="text-muted">No documents uploaded yet.</p>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Document Type</th>
                                                    <th>File Name</th>
                                                    <th>Uploaded</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($documents as $doc):
                                                    $docStatusBadge = match($doc['status']) {
                                                        'verified' => 'bg-success',
                                                        'rejected' => 'bg-danger',
                                                        default => 'bg-warning'
                                                    };
                                                ?>
                                                    <tr>
                                                        <td><?= ucfirst(str_replace('_', ' ', $doc['document_type'])) ?></td>
                                                        <td><a href="../<?= htmlspecialchars($doc['file_path']) ?>" target="_blank"><?= htmlspecialchars($doc['file_name']) ?></a></td>
                                                        <td><?= date('M d, Y', strtotime($doc['uploaded_at'])) ?></td>
                                                        <td><span class="badge <?= $docStatusBadge ?>"><?= ucfirst($doc['status']) ?></span></td>
                                                        <td>
                                                            <?php if ($doc['status'] == 'pending'): ?>
                                                                <form method="POST" class="d-inline">
                                                                    <input type="hidden" name="document_id" value="<?= $doc['id'] ?>">
                                                                    <input type="hidden" name="document_status" value="verified">
                                                                    <button type="submit" name="verify_document" class="btn btn-sm btn-success" onclick="return confirm('Verify this document?')">
                                                                        <iconify-icon icon="solar:check-circle-outline"></iconify-icon>
                                                                    </button>
                                                                </form>
                                                                <form method="POST" class="d-inline">
                                                                    <input type="hidden" name="document_id" value="<?= $doc['id'] ?>">
                                                                    <input type="hidden" name="document_status" value="rejected">
                                                                    <button type="submit" name="verify_document" class="btn btn-sm btn-danger" onclick="return confirm('Reject this document?')">
                                                                        <iconify-icon icon="solar:close-circle-outline"></iconify-icon>
                                                                    </button>
                                                                </form>
                                                            <?php else: ?>
                                                                <span class="text-muted"><?= ucfirst($doc['status']) ?></span>
                                                            <?php endif; ?>
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

                    <!-- Right Column -->
                    <div class="col-lg-4">
                        <!-- Status Update Card -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Update Status</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">New Status</label>
                                        <select name="new_status" class="form-select" id="new_status" required>
                                            <option value="pending" <?= $loan['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="under_review" <?= $loan['status'] == 'under_review' ? 'selected' : '' ?>>Under Review</option>
                                            <option value="approved" <?= $loan['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                                            <option value="rejected" <?= $loan['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                            <option value="disbursed" <?= $loan['status'] == 'disbursed' ? 'selected' : '' ?>>Disbursed</option>
                                        </select>
                                    </div>

                                    <div class="mb-3" id="approved_amount_group" style="display: <?= $loan['status'] == 'approved' || $loan['loan_amount_approved'] ? 'block' : 'none' ?>;">
                                        <label class="form-label">Approved Amount (<?= $loan['currency'] ?>)</label>
                                        <input type="number" step="0.01" name="approved_amount" class="form-control"
                                               value="<?= $loan['loan_amount_approved'] ?: $loan['loan_amount_requested'] ?>"
                                               placeholder="Enter amount to approve">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Interest Rate (%)</label>
                                        <input type="number" step="0.1" name="interest_rate" class="form-control"
                                               value="<?= $loan['interest_rate'] ?: 15 ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Repayment Period (months)</label>
                                        <input type="number" name="repayment_period" class="form-control"
                                               value="<?= $loan['repayment_period'] ?: 36 ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Review Notes</label>
                                        <textarea name="review_notes" class="form-control" rows="3"><?= htmlspecialchars($loan['review_notes'] ?: '') ?></textarea>
                                    </div>

                                    <button type="submit" name="update_status" class="btn btn-primary w-100">Update Status</button>
                                </form>
                            </div>
                        </div>

                        <!-- Applicant Info Card -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Applicant Information</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><strong>Name:</strong> <?= htmlspecialchars($loan['full_name'] ?: $loan['client_name']) ?></p>
                                <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($loan['email'] ?: $loan['client_email']) ?></p>
                                <p class="mb-1"><strong>Phone:</strong> <?= htmlspecialchars($loan['phone'] ?: $loan['client_phone'] ?: 'N/A') ?></p>
                                <p class="mb-1"><strong>Date of Birth:</strong> <?= $loan['date_of_birth'] ? date('M d, Y', strtotime($loan['date_of_birth'])) : 'N/A' ?></p>
                                <p class="mb-1"><strong>Address:</strong> <?= htmlspecialchars($loan['address'] ?: $loan['client_address'] ?: 'N/A') ?></p>
                                <p class="mb-1"><strong>City:</strong> <?= htmlspecialchars($loan['city'] ?: $loan['client_city'] ?: 'N/A') ?></p>
                                <p class="mb-1"><strong>Country:</strong> <?= htmlspecialchars($loan['country'] ?: $loan['client_country'] ?: 'N/A') ?></p>
                                <?php if ($loan['agent_name']): ?>
                                    <p class="mb-1"><strong>Assigned Agent:</strong> <?= htmlspecialchars($loan['agent_name']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Actions Card -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="mailto:<?= htmlspecialchars($loan['email'] ?: $loan['client_email']) ?>" class="btn btn-outline-primary">
                                        <iconify-icon icon="solar:letter-outline"></iconify-icon> Email Applicant
                                    </a>
                                    <?php if ($loan['phone'] || $loan['client_phone']): ?>
                                        <a href="tel:<?= htmlspecialchars($loan['phone'] ?: $loan['client_phone']) ?>" class="btn btn-outline-success">
                                            <iconify-icon icon="solar:phone-outline"></iconify-icon> Call Applicant
                                        </a>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                                        <iconify-icon icon="solar:printer-outline"></iconify-icon> Print Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Vendor Javascript -->
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.js"></script>

    <script>
        // Show/hide approved amount based on status
        const statusSelect = document.getElementById('new_status');
        const amountGroup = document.getElementById('approved_amount_group');

        statusSelect.addEventListener('change', function() {
            amountGroup.style.display = this.value === 'approved' ? 'block' : 'none';
        });
    </script>

</body>

</html>

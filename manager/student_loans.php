<?php
include "../config/config.php";
if (!isset($_SESSION['sdtravels_manager'])) {
    echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

$manager = auth('admin');
$view = isset($_GET['view']) ? intval($_GET['view']) : null;

// Redirect to view_loan.php if view parameter is set
if ($view) {
    header("Location: view_loan.php?id=" . $view);
    exit();
}

$statusFilter = isset($_GET['status']) ? $_GET['status'] : null;

// Handle Status Update
if (isset($_POST['update_status']) && isset($_POST['loan_id'])) {
    $loanId = intval($_POST['loan_id']);
    $newStatus = mysqli_real_escape_string($conn, $_POST['new_status']);
    $approvedAmount = !empty($_POST['approved_amount']) ? floatval($_POST['approved_amount']) : null;
    $reviewNotes = isset($_POST['review_notes']) ? mysqli_real_escape_string($conn, $_POST['review_notes']) : '';

    // Get loan details
    $loan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM student_loans WHERE id = '$loanId'"));

    if ($loan) {
        $setFields = ["`status` = '$newStatus'"];

        // Set timestamps and fields based on status
        if ($newStatus == 'under_review') {
            $setFields[] = "`review_date` = NOW()";
        } elseif ($newStatus == 'approved') {
            $setFields[] = "`approval_date` = NOW()";
            $setFields[] = "`approved_by` = '{$manager['id']}'";
            if ($approvedAmount) {
                $setFields[] = "`loan_amount_approved` = '$approvedAmount'";
            }
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
        } else {
            $err = "Failed to update loan status";
        }
    }
}

// Build query for loans list
$where = ["1=1"];
$params = [];
$paramTypes = "";

if ($statusFilter) {
    $where[] = "sl.status = ?";
    $params[] = $statusFilter;
    $paramTypes .= "s";
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
if ($search) {
    $where[] = "(sl.loan_number LIKE ? OR sl.full_name LIKE ? OR sl.program_name LIKE ? OR u.email LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $paramTypes .= "ssss";
}

$whereClause = implode(' AND ', $where);

// Get loans with pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 20;
$offset = ($page - 1) * $perPage;

// Count total
$countSql = "SELECT COUNT(*) as total FROM student_loans sl LEFT JOIN users u ON sl.user_id = u.id WHERE $whereClause";
$countStmt = $conn->prepare($countSql);
if (!empty($params)) {
    $countStmt->bind_param($paramTypes, ...$params);
}
$countStmt->execute();
$totalResult = $countStmt->get_result();
$totalLoans = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalLoans / $perPage);

// Get loans
$sql = "SELECT sl.*, u.fullname as client_name, u.email as client_email, u.phone as client_phone
        FROM student_loans sl
        LEFT JOIN users u ON sl.user_id = u.id
        WHERE $whereClause
        ORDER BY sl.created_at DESC
        LIMIT $perPage OFFSET $offset";
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($paramTypes, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$loans = [];
while ($row = $result->fetch_assoc()) {
    $loans[] = $row;
}
$stmt->close();

// Get statistics
$stats = [
    'total' => 0,
    'pending' => 0,
    'under_review' => 0,
    'approved' => 0,
    'rejected' => 0,
    'disbursed' => 0
];

$statsResult = mysqli_query($conn, "SELECT status, COUNT(*) as count FROM student_loans GROUP BY status");
while ($row = $statsResult->fetch_assoc()) {
    $stats[$row['status']] = $row['count'];
    $stats['total'] += $row['count'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Student Loans | ApplyBoard Africa</title>
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
                                <h4 class="mb-0">Student Loans Management</h4>
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">ApplyBoard Africa</a></li>
                                    <li class="breadcrumb-item active">Student Loans</li>
                                </ol>
                            </div>
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

                <!-- Stats Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-lg-2">
                        <div class="stat-card card">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div>
                                        <p class="stat-label mb-1">Total</p>
                                        <h3 class="stat-value mb-0"><?= $stats['total'] ?></h3>
                                    </div>
                                    <div class="stat-icon primary">
                                        <iconify-icon icon="solar:document-text-outline"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-2">
                        <a href="?status=pending" class="text-decoration-none">
                            <div class="stat-card card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <div>
                                            <p class="stat-label mb-1">Pending</p>
                                            <h3 class="stat-value mb-0"><?= $stats['pending'] ?></h3>
                                        </div>
                                        <div class="stat-icon warning">
                                            <iconify-icon icon="solar:clock-circle-outline"></iconify-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-lg-2">
                        <a href="?status=under_review" class="text-decoration-none">
                            <div class="stat-card card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <div>
                                            <p class="stat-label mb-1">Under Review</p>
                                            <h3 class="stat-value mb-0"><?= $stats['under_review'] ?></h3>
                                        </div>
                                        <div class="stat-icon info">
                                            <iconify-icon icon="solar:eye-outline"></iconify-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-lg-2">
                        <a href="?status=approved" class="text-decoration-none">
                            <div class="stat-card card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <div>
                                            <p class="stat-label mb-1">Approved</p>
                                            <h3 class="stat-value mb-0"><?= $stats['approved'] ?></h3>
                                        </div>
                                        <div class="stat-icon success">
                                            <iconify-icon icon="solar:check-circle-outline"></iconify-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-lg-2">
                        <a href="?status=disbursed" class="text-decoration-none">
                            <div class="stat-card card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <div>
                                            <p class="stat-label mb-1">Disbursed</p>
                                            <h3 class="stat-value mb-0"><?= $stats['disbursed'] ?></h3>
                                        </div>
                                        <div class="stat-icon purple">
                                            <iconify-icon icon="solar:wallet-money-outline"></iconify-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-lg-2">
                        <a href="?status=rejected" class="text-decoration-none">
                            <div class="stat-card card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <div>
                                            <p class="stat-label mb-1">Rejected</p>
                                            <h3 class="stat-value mb-0"><?= $stats['rejected'] ?></h3>
                                        </div>
                                        <div class="stat-icon danger">
                                            <iconify-icon icon="solar:close-circle-outline"></iconify-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div class="card mb-3">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="pending" <?= $statusFilter == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="under_review" <?= $statusFilter == 'under_review' ? 'selected' : '' ?>>Under Review</option>
                                    <option value="approved" <?= $statusFilter == 'approved' ? 'selected' : '' ?>>Approved</option>
                                    <option value="rejected" <?= $statusFilter == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                    <option value="disbursed" <?= $statusFilter == 'disbursed' ? 'selected' : '' ?>>Disbursed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Search</label>
                                <input type="text" name="search" class="form-control" placeholder="Loan #, Name, Email..." value="<?= htmlspecialchars($search) ?>">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <iconify-icon icon="solar:magnifier-outline"></iconify-icon> Filter
                                </button>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <a href="?" class="btn btn-outline-secondary w-100">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Loans Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Loan #</th>
                                        <th>Applicant</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Submitted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($loans)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <iconify-icon icon="solar:document-text-outline" style="font-size: 48px;" class="text-muted"></iconify-icon>
                                                <p class="text-muted mt-2">No loan applications found.</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($loans as $loan):
                                            $statusBadge = match($loan['status']) {
                                                'pending' => 'bg-warning',
                                                'under_review' => 'bg-info',
                                                'approved' => 'bg-primary',
                                                'rejected' => 'bg-danger',
                                                'disbursed' => 'bg-success',
                                                default => 'bg-secondary'
                                            };
                                            $statusLabel = ucfirst(str_replace('_', ' ', $loan['status']));
                                        ?>
                                            <tr>
                                                <td>
                                                    <strong><?= htmlspecialchars($loan['loan_number']) ?></strong>
                                                </td>
                                                <td>
                                                    <div><?= htmlspecialchars($loan['full_name'] ?: $loan['client_name']) ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($loan['email'] ?: $loan['client_email']) ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark"><?= ucfirst(str_replace('_', ' ', $loan['loan_type'])) ?></span>
                                                </td>
                                                <td>
                                                    <?= number_format($loan['loan_amount_requested'], 2) ?> <?= htmlspecialchars($loan['currency']) ?>
                                                    <?php if ($loan['loan_amount_approved']): ?>
                                                        <br><small class="text-success">Approved: <?= number_format($loan['loan_amount_approved'], 2) ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge <?= $statusBadge ?>"><?= $statusLabel ?></span>
                                                </td>
                                                <td>
                                                    <?= date('M d, Y', strtotime($loan['submission_date'])) ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="?view=<?= $loan['id'] ?>" class="btn btn-outline-primary">
                                                            <iconify-icon icon="solar:eye-outline"></iconify-icon>
                                                        </a>
                                                        <?php if ($loan['status'] == 'pending'): ?>
                                                            <button type="button" class="btn btn-outline-info btn-review" data-loan-id="<?= $loan['id'] ?>" data-loan-number="<?= htmlspecialchars($loan['loan_number']) ?>">
                                                                <iconify-icon icon="solar:file-text-outline"></iconify-icon>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                            <nav class="mt-3">
                                <ul class="pagination justify-content-center mb-0">
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?>&status=<?= $statusFilter ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Vendor Javascript -->
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.js"></script>

    <!-- Quick Review Modal -->
    <div class="modal fade" id="quickReviewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="quickReviewForm">
                    <input type="hidden" name="loan_id" id="modal_loan_id">
                    <div class="modal-header">
                        <h5 class="modal-title">Quick Review: <span id="modal_loan_number"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Update Status</label>
                            <select name="new_status" class="form-select" required>
                                <option value="under_review">Mark as Under Review</option>
                                <option value="approved">Approve</option>
                                <option value="rejected">Reject</option>
                            </select>
                        </div>
                        <div class="mb-3" id="approved_amount_group" style="display: none;">
                            <label class="form-label">Approved Amount</label>
                            <input type="number" step="0.01" name="approved_amount" class="form-control" placeholder="Enter amount">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Review Notes</label>
                            <textarea name="review_notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Quick review modal
        document.querySelectorAll('.btn-review').forEach(btn => {
            btn.addEventListener('click', function() {
                const loanId = this.dataset.loanId;
                const loanNumber = this.dataset.loanNumber;
                document.getElementById('modal_loan_id').value = loanId;
                document.getElementById('modal_loan_number').textContent = loanNumber;
                const modal = new bootstrap.Modal(document.getElementById('quickReviewModal'));
                modal.show();
            });
        });

        // Show/hide approved amount based on status
        document.querySelector('select[name="new_status"]').addEventListener('change', function() {
            document.getElementById('approved_amount_group').style.display = this.value === 'approved' ? 'block' : 'none';
        });
    </script>

</body>

</html>

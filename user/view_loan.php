<?php
include "../config/config.php";
if (!isLoggedIn('user')) {
    echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

$user = auth('user');
$user_id = $user['id'];
$loan_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch loan details
$sql = "SELECT * FROM student_loans WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $loan_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$loan = $result->fetch_assoc();
$stmt->close();

if (!$loan) {
    // Loan not found or doesn't belong to the user
    header("Location: student_loans.php");
    exit();
}

// Fetch loan documents
$sql = "SELECT * FROM loan_documents WHERE loan_id = ? ORDER BY uploaded_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $loan_id);
$stmt->execute();
$result = $stmt->get_result();
$documents = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Handle document upload
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['document'])) {
    $document_type = $_POST['document_type'];
    $file = $_FILES['document'];

    // Handle file upload
    $upload_dir = '../uploads/loan_documents/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $file_name = time() . '_' . basename($file['name']);
    $file_path = $upload_dir . $file_name;
    $file_size = $file['size'];

    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        // Insert into database
        $sql = "INSERT INTO loan_documents (loan_id, document_type, file_path, file_name, file_size, status) VALUES (?, ?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssi", $loan_id, $document_type, $file_path, $file_name, $file_size);
        $stmt->execute();
        $stmt->close();
        // Refresh page
        header("Location: view_loan.php?id=" . $loan_id);
        exit();
    } else {
        $error = "Failed to upload document.";
    }
}

// Handle bank details update
if (isset($_POST['update_bank_details']) && $loan['status'] == 'pending') {
    $bank_name = $_POST['bank_name'] ?? null;
    $account_number = $_POST['account_number'] ?? null;
    $account_name = $_POST['account_name'] ?? null;
    $account_type = $_POST['account_type'] ?? 'savings';

    $sql = "UPDATE student_loans SET bank_name = ?, account_number = ?, account_name = ?, account_type = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $bank_name, $account_number, $account_name, $account_type, $loan_id, $user_id);
    if ($stmt->execute()) {
        $success = "Bank details updated successfully";
        // Refresh loan data
        $loan['bank_name'] = $bank_name;
        $loan['account_number'] = $account_number;
        $loan['account_name'] = $account_name;
        $loan['account_type'] = $account_type;
    } else {
        $error = "Failed to update bank details";
    }
    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title Meta -->
    <meta charset="utf-8" />
    <title>Loan Application Details | ApplyBoard Africa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="theme-color" content="#0F4C75">

    <!-- App favicon -->
    <link rel="shortcut icon" href="../images/favicon.png">

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vendor css -->
    <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />

    <!-- Icons css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />

    <!-- Custom Dashboard CSS -->
    <link href="assets/css/dashboard.css" rel="stylesheet" type="text/css" />

    <!-- Theme Config js -->
    <script src="assets/js/config.js"></script>
    <!-- Iconify -->
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
</head>

<body>

    <!-- START Wrapper -->
    <div class="app-wrapper">

        <!-- Topbar Start -->
        <?php include "partials/header.php"; ?>
        <!-- Topbar End -->

        <!-- App Menu Start -->
        <?php include "partials/sidebar.php"; ?>
        <!-- App Menu End -->

        <!-- Start right Content here -->
        <div class="page-content">
            <!-- Start Container Fluid -->
            <div class="container-fluid">

                <!-- ========== Page Title Start ========== -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">Loan Application Details</h4>
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">ApplyBoard Africa Ltd</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="student_loans.php">Student Loans</a></li>
                                    <li class="breadcrumb-item active">Loan #<?= htmlspecialchars($loan['loan_number']) ?></li>
                                </ol>
                            </div>
                            <a href="student_loans.php" class="btn btn-outline-secondary btn-sm">
                                <iconify-icon icon="solar:alt-arrow-left-outline"></iconify-icon> Back to My Loans
                            </a>
                        </div>
                    </div>
                </div>
                <!-- ========== Page Title End ========== -->

                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($success) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['new'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Your loan application has been submitted successfully. You can upload any required documents below.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title">Loan #<?= htmlspecialchars($loan['loan_number']) ?></h5>
                                    <span class="badge bg-<?= $loan['status'] == 'approved' ? 'success' : ($loan['status'] == 'pending' ? 'warning' : 'primary') ?>"><?= ucfirst($loan['status']) ?></span>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Loan Type:</strong> <?= ucfirst(str_replace('_', ' ', htmlspecialchars($loan['loan_type']))) ?></p>
                                        <p><strong>Amount Requested:</strong> <?= number_format($loan['loan_amount_requested'], 2) ?> <?= htmlspecialchars($loan['currency']) ?></p>
                                        <p><strong>Amount Approved:</strong> <?= $loan['loan_amount_approved'] ? number_format($loan['loan_amount_approved'], 2) . ' ' . htmlspecialchars($loan['currency']) : 'N/A' ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Date Submitted:</strong> <?= date('M d, Y', strtotime($loan['submission_date'])) ?></p>
                                        <p><strong>Purpose:</strong> <?= htmlspecialchars($loan['purpose']) ?></p>
                                    </div>
                                </div>

                                <hr>

                                <h5>Program Information</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Program Name:</strong> <?= htmlspecialchars($loan['program_name']) ?></p>
                                        <p><strong>Institution:</strong> <?= htmlspecialchars($loan['institution_name']) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Course Duration:</strong> <?= htmlspecialchars($loan['course_duration']) ?> months</p>
                                        <p><strong>Program Dates:</strong> <?= date('M d, Y', strtotime($loan['program_start_date'])) ?> - <?= date('M d, Y', strtotime($loan['program_end_date'])) ?></p>
                                    </div>
                                </div>

                                <hr>

                                <h5>Financial Information</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Employment Status:</strong> <?= ucfirst(str_replace('_', ' ', htmlspecialchars($loan['employment_status']))) ?></p>
                                        <p><strong>Employer:</strong> <?= htmlspecialchars($loan['employer_name']) ?: 'N/A' ?></p>
                                        <p><strong>Monthly Income:</strong> <?= $loan['monthly_income'] ? number_format($loan['monthly_income'], 2) . ' ' . htmlspecialchars($loan['currency']) : 'N/A' ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Collateral:</strong> <?= $loan['has_collateral'] ? 'Yes' : 'No' ?></p>
                                        <?php if ($loan['has_collateral']): ?>
                                            <p><strong>Collateral Type:</strong> <?= htmlspecialchars($loan['collateral_type']) ?></p>
                                            <p><strong>Collateral Value:</strong> <?= number_format($loan['collateral_value'], 2) ?> <?= htmlspecialchars($loan['currency']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <hr>

                                <h5>Guarantor Information</h5>
                                <p><strong>Guarantor:</strong> <?= $loan['has_guarantor'] ? 'Yes' : 'No' ?></p>
                                <?php if ($loan['has_guarantor']): ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Guarantor Name:</strong> <?= htmlspecialchars($loan['guarantor_name']) ?></p>
                                            <p><strong>Guarantor Email:</strong> <?= htmlspecialchars($loan['guarantor_email']) ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Guarantor Phone:</strong> <?= htmlspecialchars($loan['guarantor_phone']) ?></p>
                                            <p><strong>Relationship:</strong> <?= htmlspecialchars($loan['guarantor_relationship']) ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <hr>

                                <h5>Disbursement Bank Account</h5>
                                <?php if ($loan['bank_name']): ?>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="mb-1"><strong>Bank:</strong> <?= htmlspecialchars($loan['bank_name']) ?></p>
                                                    <p class="mb-1"><strong>Account Number:</strong> <?= htmlspecialchars($loan['account_number']) ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-1"><strong>Account Name:</strong> <?= htmlspecialchars($loan['account_name']) ?></p>
                                                    <p class="mb-1"><strong>Account Type:</strong> <?= ucfirst($loan['account_type']) ?></p>
                                                </div>
                                            </div>
                                            <?php if ($loan['status'] == 'pending'): ?>
                                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#editBankModal">
                                                    <iconify-icon icon="solar:pen-outline"></iconify-icon> Edit Bank Details
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?php if ($loan['status'] == 'pending'): ?>
                                        <p class="text-warning">
                                            <iconify-icon icon="solar:danger-triangle-outline" class="me-1"></iconify-icon>
                                            No bank account details added. Please add your bank account for loan disbursement.
                                        </p>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editBankModal">
                                            <iconify-icon icon="solar:add-circle-outline"></iconify-icon> Add Bank Details
                                        </button>
                                    <?php else: ?>
                                        <p class="text-muted">No bank account details on file.</p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Loan Documents</h5>

                                <form method="POST" enctype="multipart/form-data" class="mb-3">
                                    <div class="mb-3">
                                        <label for="document_type" class="form-label">Document Type</label>
                                        <select class="form-select" id="document_type" name="document_type" required>
                                            <option value="identity_proof">Identity Proof</option>
                                            <option value="income_proof">Income Proof</option>
                                            <option value="admission_letter">Admission Letter</option>
                                            <option value="fee_schedule">Fee Schedule</option>
                                            <option value="bank_statement">Bank Statement</option>
                                            <option value="guarantor_form">Guarantor Form</option>
                                            <option value="collateral_document">Collateral Document</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="document" class="form-label">Upload Document</label>
                                        <input class="form-control" type="file" id="document" name="document" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Upload Document</button>
                                </form>

                                <?php if ($error): ?>
                                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                <?php endif; ?>

                                <hr>

                                <h6>Uploaded Documents</h6>
                                <?php if (empty($documents)): ?>
                                    <p class="text-muted">No documents uploaded yet.</p>
                                <?php else: ?>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($documents as $doc): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                <div>
                                                    <a href="<?= htmlspecialchars($doc['file_path']) ?>" target="_blank" class="text-decoration-none">
                                                        <?= htmlspecialchars($doc['file_name']) ?>
                                                    </a>
                                                    <br>
                                                    <small class="text-muted"><?= ucfirst(str_replace('_', ' ', htmlspecialchars($doc['document_type']))) ?> - <?= date('M d, Y', strtotime($doc['uploaded_at'])) ?></small>
                                                </div>
                                                <span class="badge bg-<?= $doc['status'] == 'verified' ? 'success' : ($doc['status'] == 'rejected' ? 'danger' : 'warning') ?>"><?= ucfirst($doc['status']) ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Contact Support Card -->
                        <div class="card mt-3">
                            <div class="card-body">
                                <h5 class="card-title">Need Help?</h5>
                                <p class="text-muted small">Have questions about your loan application? Contact our support team.</p>
                                <div class="d-grid gap-2">
                                    <a href="mailto:info@applyboardafrica.com" class="btn btn-outline-primary">
                                        <iconify-icon icon="solar:letter-outline"></iconify-icon> Email Support
                                    </a>
                                    <a href="https://wa.me/2348000000000" class="btn btn-success">
                                        <iconify-icon icon="solar:boldly-chat-outline"></iconify-icon> WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End Container Fluid -->

            <!-- Footer Start -->
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
            <!-- Footer End -->

        </div>
        <!-- End Page Content -->

    </div>
    <!-- END Wrapper -->

    <!-- Vendor Javascript -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App Javascript -->
    <script src="assets/js/app.js"></script>

    <!-- Edit Bank Details Modal -->
    <?php if ($loan['status'] == 'pending'): ?>
    <div class="modal fade" id="editBankModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <?= $loan['bank_name'] ? 'Edit' : 'Add' ?> Bank Account Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="bank_name" class="form-label">Bank Name</label>
                            <select class="form-select" id="bank_name" name="bank_name" required>
                                <option value="">Select Bank</option>
                                <option value="Access Bank" <?= $loan['bank_name'] == 'Access Bank' ? 'selected' : '' ?>>Access Bank</option>
                                <option value="Citibank" <?= $loan['bank_name'] == 'Citibank' ? 'selected' : '' ?>>Citibank</option>
                                <option value="Ecobank" <?= $loan['bank_name'] == 'Ecobank' ? 'selected' : '' ?>>Ecobank</option>
                                <option value="Fidelity Bank" <?= $loan['bank_name'] == 'Fidelity Bank' ? 'selected' : '' ?>>Fidelity Bank</option>
                                <option value="First Bank of Nigeria" <?= $loan['bank_name'] == 'First Bank of Nigeria' ? 'selected' : '' ?>>First Bank of Nigeria</option>
                                <option value="First City Monument Bank (FCMB)" <?= $loan['bank_name'] == 'First City Monument Bank (FCMB)' ? 'selected' : '' ?>>First City Monument Bank (FCMB)</option>
                                <option value="Guaranty Trust Bank (GTB)" <?= $loan['bank_name'] == 'Guaranty Trust Bank (GTB)' ? 'selected' : '' ?>>Guaranty Trust Bank (GTB)</option>
                                <option value="Heritage Bank" <?= $loan['bank_name'] == 'Heritage Bank' ? 'selected' : '' ?>>Heritage Bank</option>
                                <option value="Jaiz Bank" <?= $loan['bank_name'] == 'Jaiz Bank' ? 'selected' : '' ?>>Jaiz Bank</option>
                                <option value="Keystone Bank" <?= $loan['bank_name'] == 'Keystone Bank' ? 'selected' : '' ?>>Keystone Bank</option>
                                <option value="Polaris Bank" <?= $loan['bank_name'] == 'Polaris Bank' ? 'selected' : '' ?>>Polaris Bank</option>
                                <option value="Providus Bank" <?= $loan['bank_name'] == 'Providus Bank' ? 'selected' : '' ?>>Providus Bank</option>
                                <option value="Stanbic IBTC" <?= $loan['bank_name'] == 'Stanbic IBTC' ? 'selected' : '' ?>>Stanbic IBTC</option>
                                <option value="Standard Chartered Bank" <?= $loan['bank_name'] == 'Standard Chartered Bank' ? 'selected' : '' ?>>Standard Chartered Bank</option>
                                <option value="Sterling Bank" <?= $loan['bank_name'] == 'Sterling Bank' ? 'selected' : '' ?>>Sterling Bank</option>
                                <option value="Union Bank of Nigeria" <?= $loan['bank_name'] == 'Union Bank of Nigeria' ? 'selected' : '' ?>>Union Bank of Nigeria</option>
                                <option value="United Bank for Africa (UBA)" <?= $loan['bank_name'] == 'United Bank for Africa (UBA)' ? 'selected' : '' ?>>United Bank for Africa (UBA)</option>
                                <option value="Wema Bank" <?= $loan['bank_name'] == 'Wema Bank' ? 'selected' : '' ?>>Wema Bank</option>
                                <option value="Zenith Bank" <?= $loan['bank_name'] == 'Zenith Bank' ? 'selected' : '' ?>>Zenith Bank</option>
                                <option value="other" <?= $loan['bank_name'] == 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="account_type" class="form-label">Account Type</label>
                            <select class="form-select" id="account_type" name="account_type" required>
                                <option value="savings" <?= $loan['account_type'] == 'savings' ? 'selected' : '' ?>>Savings Account</option>
                                <option value="current" <?= $loan['account_type'] == 'current' ? 'selected' : '' ?>>Current Account</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="account_number" class="form-label">Account Number</label>
                            <input type="text" class="form-control" id="account_number" name="account_number"
                                   pattern="[0-9]{10}" maxlength="10" required placeholder="10-digit account number"
                                   value="<?= htmlspecialchars($loan['account_number'] ?? '') ?>">
                            <small class="text-muted">Enter your 10-digit account number</small>
                        </div>
                        <div class="mb-3">
                            <label for="account_name" class="form-label">Account Name</label>
                            <input type="text" class="form-control" id="account_name" name="account_name" required placeholder="Name on the account"
                                   value="<?= htmlspecialchars($loan['account_name'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_bank_details" class="btn btn-primary">Save Bank Details</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

</body>

</html>

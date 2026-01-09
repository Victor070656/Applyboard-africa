<?php
include "../config/config.php";
if (!isLoggedIn('user')) {
    echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

$user = auth('user');
$user_id = $user['id'];

// Fetch user's student loans
$sql = "SELECT * FROM student_loans WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$loans = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title Meta -->
    <meta charset="utf-8" />
    <title>Student Loans | ApplyBoard Africa</title>
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
                                <h4 class="mb-0">Student Loans</h4>
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">ApplyBoard Africa Ltd</a>
                                    </li>
                                    <li class="breadcrumb-item active">Student Loans</li>
                                </ol>
                            </div>
                            <a href="new_loan_application.php" class="btn btn-primary btn-sm">
                                <iconify-icon icon="solar:add-circle-outline"></iconify-icon> Apply for a New Loan
                            </a>
                        </div>
                    </div>
                </div>
                <!-- ========== Page Title End ========== -->

                <!-- Loans List -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-centered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Loan ID</th>
                                                <th>Amount Requested</th>
                                                <th>Amount Approved</th>
                                                <th>Status</th>
                                                <th>Date Submitted</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($loans)): ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">You have not applied for any loans yet.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($loans as $loan): ?>
                                                    <tr>
                                                        <td><strong><?= htmlspecialchars($loan['loan_number']) ?></strong></td>
                                                        <td><?= number_format($loan['loan_amount_requested'], 2) ?> <?= htmlspecialchars($loan['currency']) ?></td>
                                                        <td><?= $loan['loan_amount_approved'] ? number_format($loan['loan_amount_approved'], 2) . ' ' . htmlspecialchars($loan['currency']) : 'N/A' ?></td>
                                                        <td><span class="badge bg-<?= $loan['status'] == 'approved' ? 'success' : ($loan['status'] == 'pending' ? 'warning' : 'primary') ?>"><?= ucfirst($loan['status']) ?></span></td>
                                                        <td><?= date('M d, Y', strtotime($loan['submission_date'])) ?></td>
                                                        <td>
                                                            <a href="view_loan.php?id=<?= $loan['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
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

</body>

</html>
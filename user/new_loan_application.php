<?php
include "../config/config.php";
if (!isLoggedIn('user')) {
    echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

$user = auth('user');
$user_id = $user['id'];
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Generate a unique loan number
    $loan_number = 'ABA-L-' . time() . rand(100, 999);

    // Get form data - all optional fields set to NULL if empty
    $loan_type = $_POST['loan_type'] ?? 'tuition';
    $loan_amount_requested = floatval($_POST['loan_amount_requested'] ?? 0);
    $currency = $_POST['currency'] ?? 'NGN';
    $purpose = !empty(trim($_POST['purpose'] ?? '')) ? trim($_POST['purpose']) : null;
    $program_name = !empty(trim($_POST['program_name'] ?? '')) ? trim($_POST['program_name']) : null;
    $institution_name = !empty(trim($_POST['institution_name'] ?? '')) ? trim($_POST['institution_name']) : null;
    $course_duration = !empty($_POST['course_duration']) ? intval($_POST['course_duration']) : null;
    $program_start_date = !empty($_POST['program_start_date']) ? $_POST['program_start_date'] : null;
    $program_end_date = !empty($_POST['program_end_date']) ? $_POST['program_end_date'] : null;

    $full_name = $user['fullname'] ?? '';
    $email = $user['email'] ?? '';
    $phone = !empty($user['phone']) ? $user['phone'] : null;
    $date_of_birth = !empty($user['date_of_birth']) ? $user['date_of_birth'] : null;
    $nationality = $user['country'] ?? 'Nigeria';
    $address = !empty(trim($user['address'] ?? '')) ? trim($user['address']) : null;
    $city = !empty(trim($user['city'] ?? '')) ? trim($user['city']) : null;
    $state = !empty(trim($user['state'] ?? '')) ? trim($user['state']) : null;
    $country = $user['country'] ?? 'Nigeria';

    $employment_status = $_POST['employment_status'] ?? 'student';
    $employer_name = !empty(trim($_POST['employer_name'] ?? '')) ? trim($_POST['employer_name']) : null;
    $monthly_income = !empty($_POST['monthly_income']) ? floatval($_POST['monthly_income']) : null;
    $income_source = !empty(trim($_POST['income_source'] ?? '')) ? trim($_POST['income_source']) : null;
    $has_collateral = isset($_POST['has_collateral']) ? 1 : 0;
    $collateral_type = !empty(trim($_POST['collateral_type'] ?? '')) ? trim($_POST['collateral_type']) : null;
    $collateral_value = !empty($_POST['collateral_value']) ? floatval($_POST['collateral_value']) : null;
    $has_guarantor = isset($_POST['has_guarantor']) ? 1 : 0;
    $guarantor_name = !empty(trim($_POST['guarantor_name'] ?? '')) ? trim($_POST['guarantor_name']) : null;
    $guarantor_email = !empty(trim($_POST['guarantor_email'] ?? '')) ? trim($_POST['guarantor_email']) : null;
    $guarantor_phone = !empty(trim($_POST['guarantor_phone'] ?? '')) ? trim($_POST['guarantor_phone']) : null;
    $guarantor_relationship = !empty(trim($_POST['guarantor_relationship'] ?? '')) ? trim($_POST['guarantor_relationship']) : null;
    $guarantor_address = !empty(trim($_POST['guarantor_address'] ?? '')) ? trim($_POST['guarantor_address']) : null;

    // Bank Account Details for Disbursement
    $bank_name = !empty(trim($_POST['bank_name'] ?? '')) ? trim($_POST['bank_name']) : null;
    $account_number = !empty(trim($_POST['account_number'] ?? '')) ? trim($_POST['account_number']) : null;
    $account_name = !empty(trim($_POST['account_name'] ?? '')) ? trim($_POST['account_name']) : null;
    $account_type = !empty($_POST['account_type']) ? $_POST['account_type'] : 'savings';

    $submission_date = date('Y-m-d H:i:s');
    $status = 'pending';

    // Insert into database using mysqli_query for better debugging
    $sql = "INSERT INTO student_loans (
        loan_number, user_id, loan_type, loan_amount_requested, currency, purpose,
        program_name, institution_name, course_duration, program_start_date, program_end_date,
        full_name, email, phone, date_of_birth, nationality, address, city, state, country,
        employment_status, employer_name, monthly_income, income_source,
        has_collateral, collateral_type, collateral_value,
        has_guarantor, guarantor_name, guarantor_email, guarantor_phone, guarantor_relationship, guarantor_address,
        bank_name, account_number, account_name, account_type,
        submission_date, status
    ) VALUES (
        '$loan_number', '$user_id', '$loan_type', '$loan_amount_requested', '$currency', " . ($purpose ? "'$purpose'" : "NULL") . ",
        " . ($program_name ? "'$program_name'" : "NULL") . ", " . ($institution_name ? "'$institution_name'" : "NULL") . ", " . ($course_duration ? "'$course_duration'" : "NULL") . ", " . ($program_start_date ? "'$program_start_date'" : "NULL") . ", " . ($program_end_date ? "'$program_end_date'" : "NULL") . ",
        '$full_name', '$email', " . ($phone ? "'$phone'" : "NULL") . ", " . ($date_of_birth ? "'$date_of_birth'" : "NULL") . ", '$nationality', " . ($address ? "'$address'" : "NULL") . ", " . ($city ? "'$city'" : "NULL") . ", " . ($state ? "'$state'" : "NULL") . ", '$country',
        '$employment_status', " . ($employer_name ? "'$employer_name'" : "NULL") . ", " . ($monthly_income ? "'$monthly_income'" : "NULL") . ", " . ($income_source ? "'$income_source'" : "NULL") . ",
        '$has_collateral', " . ($collateral_type ? "'$collateral_type'" : "NULL") . ", " . ($collateral_value ? "'$collateral_value'" : "NULL") . ",
        '$has_guarantor', " . ($guarantor_name ? "'$guarantor_name'" : "NULL") . ", " . ($guarantor_email ? "'$guarantor_email'" : "NULL") . ", " . ($guarantor_phone ? "'$guarantor_phone'" : "NULL") . ", " . ($guarantor_relationship ? "'$guarantor_relationship'" : "NULL") . ", " . ($guarantor_address ? "'$guarantor_address'" : "NULL") . ",
        " . ($bank_name ? "'$bank_name'" : "NULL") . ", " . ($account_number ? "'$account_number'" : "NULL") . ", " . ($account_name ? "'$account_name'" : "NULL") . ", '$account_type',
        '$submission_date', '$status'
    )";

    if (mysqli_query($conn, $sql)) {
        $loan_id = mysqli_insert_id($conn);
        // Redirect to the loan details page
        header("Location: view_loan.php?id=" . $loan_id . "&new=true");
        exit();
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title Meta -->
    <meta charset="utf-8" />
    <title>New Loan Application | ApplyBoard Africa</title>
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
                                <h4 class="mb-0">New Loan Application</h4>
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">ApplyBoard Africa Ltd</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="student_loans.php">Student Loans</a></li>
                                    <li class="breadcrumb-item active">New Application</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ========== Page Title End ========== -->

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Loan Application Form -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="loan_type" class="form-label">Loan Type</label>
                                                <select class="form-select" id="loan_type" name="loan_type" required>
                                                    <option value="tuition">Tuition</option>
                                                    <option value="living_expenses">Living Expenses</option>
                                                    <option value="full_program">Full Program</option>
                                                    <option value="travel">Travel</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="loan_amount_requested" class="form-label">Loan Amount Requested (₦)</label>
                                                <input type="number" step="0.01" class="form-control" id="loan_amount_requested" name="loan_amount_requested" required min="100000">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="currency" class="form-label">Currency</label>
                                                <select class="form-select" id="currency" name="currency" required>
                                                    <option value="NGN">NGN</option>
                                                    <option value="USD">USD</option>
                                                    <option value="GBP">GBP</option>
                                                    <option value="CAD">CAD</option>
                                                    <option value="EUR">EUR</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="purpose" class="form-label">Purpose of Loan</label>
                                                <textarea class="form-control" id="purpose" name="purpose" rows="1" placeholder="Briefly describe the loan purpose"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h5>Program Information</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="program_name" class="form-label">Program Name</label>
                                                <input type="text" class="form-control" id="program_name" name="program_name" placeholder="e.g. BSc Computer Science">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="institution_name" class="form-label">Institution/University</label>
                                                <input type="text" class="form-control" id="institution_name" name="institution_name" placeholder="e.g. University of Lagos">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="course_duration" class="form-label">Course Duration (months)</label>
                                                <input type="number" class="form-control" id="course_duration" name="course_duration" placeholder="e.g. 48">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="program_start_date" class="form-label">Program Start Date</label>
                                                <input type="date" class="form-control" id="program_start_date" name="program_start_date">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="program_end_date" class="form-label">Program End Date</label>
                                                <input type="date" class="form-control" id="program_end_date" name="program_end_date">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h5>Employment & Financial Information</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="employment_status" class="form-label">Employment Status</label>
                                                <select class="form-select" id="employment_status" name="employment_status" required>
                                                    <option value="student">Student</option>
                                                    <option value="employed">Employed</option>
                                                    <option value="self_employed">Self-Employed</option>
                                                    <option value="unemployed">Unemployed</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="employer_name" class="form-label">Employer Name</label>
                                                <input type="text" class="form-control" id="employer_name" name="employer_name" placeholder="If employed">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="monthly_income" class="form-label">Monthly Income</label>
                                                <input type="number" step="0.01" class="form-control" id="monthly_income" name="monthly_income" placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="income_source" class="form-label">Source of Income</label>
                                                <input type="text" class="form-control" id="income_source" name="income_source" placeholder="e.g. Salary, Business, Allowance">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h5>Collateral & Guarantor</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" value="1" id="has_collateral" name="has_collateral">
                                                <label class="form-check-label" for="has_collateral">
                                                    I have collateral
                                                </label>
                                            </div>
                                            <div id="collateral_fields" style="display: none;">
                                                <div class="mb-3">
                                                    <label for="collateral_type" class="form-label">Collateral Type</label>
                                                    <input type="text" class="form-control" id="collateral_type" name="collateral_type" placeholder="e.g. Land, Vehicle, Property">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="collateral_value" class="form-label">Collateral Value (₦)</label>
                                                    <input type="number" step="0.01" class="form-control" id="collateral_value" name="collateral_value" placeholder="0.00">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" value="1" id="has_guarantor" name="has_guarantor">
                                                <label class="form-check-label" for="has_guarantor">
                                                    I have a guarantor
                                                </label>
                                            </div>
                                            <div id="guarantor_fields" style="display: none;">
                                                <div class="mb-3">
                                                    <label for="guarantor_name" class="form-label">Guarantor Name</label>
                                                    <input type="text" class="form-control" id="guarantor_name" name="guarantor_name">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="guarantor_email" class="form-label">Guarantor Email</label>
                                                    <input type="email" class="form-control" id="guarantor_email" name="guarantor_email">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="guarantor_phone" class="form-label">Guarantor Phone</label>
                                                    <input type="tel" class="form-control" id="guarantor_phone" name="guarantor_phone">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="guarantor_relationship" class="form-label">Relationship with Guarantor</label>
                                                    <input type="text" class="form-control" id="guarantor_relationship" name="guarantor_relationship" placeholder="e.g. Parent, Sibling, Spouse">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="guarantor_address" class="form-label">Guarantor Address</label>
                                                    <textarea class="form-control" id="guarantor_address" name="guarantor_address" rows="2"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h5>Disbursement Bank Account Details</h5>
                                    <p class="text-muted small mb-3">Approved loan funds will be disbursed to this bank account.</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="bank_name" class="form-label">Bank Name</label>
                                                <select class="form-select" id="bank_name" name="bank_name" required>
                                                    <option value="">Select Bank</option>
                                                    <option value="Access Bank">Access Bank</option>
                                                    <option value="Citibank">Citibank</option>
                                                    <option value="Ecobank">Ecobank</option>
                                                    <option value="Fidelity Bank">Fidelity Bank</option>
                                                    <option value="First Bank of Nigeria">First Bank of Nigeria</option>
                                                    <option value="First City Monument Bank (FCMB)">First City Monument Bank (FCMB)</option>
                                                    <option value="Guaranty Trust Bank (GTB)">Guaranty Trust Bank (GTB)</option>
                                                    <option value="Heritage Bank">Heritage Bank</option>
                                                    <option value="Jaiz Bank">Jaiz Bank</option>
                                                    <option value="Keystone Bank">Keystone Bank</option>
                                                    <option value="Polaris Bank">Polaris Bank</option>
                                                    <option value="Providus Bank">Providus Bank</option>
                                                    <option value="Stanbic IBTC">Stanbic IBTC</option>
                                                    <option value="Standard Chartered Bank">Standard Chartered Bank</option>
                                                    <option value="Sterling Bank">Sterling Bank</option>
                                                    <option value="Union Bank of Nigeria">Union Bank of Nigeria</option>
                                                    <option value="United Bank for Africa (UBA)">United Bank for Africa (UBA)</option>
                                                    <option value="Wema Bank">Wema Bank</option>
                                                    <option value="Zenith Bank">Zenith Bank</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="account_type" class="form-label">Account Type</label>
                                                <select class="form-select" id="account_type" name="account_type" required>
                                                    <option value="savings">Savings Account</option>
                                                    <option value="current">Current Account</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="account_number" class="form-label">Account Number</label>
                                                <input type="text" class="form-control" id="account_number" name="account_number" pattern="[0-9]{10}" maxlength="10" required placeholder="10-digit account number">
                                                <small class="text-muted">Enter your 10-digit account number</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="account_name" class="form-label">Account Name</label>
                                                <input type="text" class="form-control" id="account_name" name="account_name" required placeholder="Name on the account">
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Submit Application</button>
                                </form>
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

    <script>
        // Show/hide collateral and guarantor fields based on checkbox
        document.getElementById('has_collateral').addEventListener('change', function () {
            document.getElementById('collateral_fields').style.display = this.checked ? 'block' : 'none';
        });
        document.getElementById('has_guarantor').addEventListener('change', function () {
            document.getElementById('guarantor_fields').style.display = this.checked ? 'block' : 'none';
        });
    </script>

</body>

</html>

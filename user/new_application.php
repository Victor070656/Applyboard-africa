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

// Get pricing from settings
$caseTypes = ['study_abroad', 'visa_student', 'visa_tourist', 'visa_family', 'travel_booking', 'pilgrimage', 'other'];
$pricing = [];
foreach ($caseTypes as $type) {
    $pricing[$type] = getCasePricing($type);
}

// Case type labels
$caseTypeLabels = [
    'study_abroad' => ['label' => 'Study Abroad', 'icon' => 'solar:square-academic-cap-outline', 'desc' => 'Full study abroad application assistance'],
    'visa_student' => ['label' => 'Student Visa', 'icon' => 'solar:passport-outline', 'desc' => 'Student visa application processing'],
    'visa_tourist' => ['label' => 'Tourist Visa', 'icon' => 'solar:airplane-outline', 'desc' => 'Tourist/visitor visa application'],
    'visa_family' => ['label' => 'Family Visa', 'icon' => 'solar:users-group-rounded-outline', 'desc' => 'Family reunion visa processing'],
    'travel_booking' => ['label' => 'Travel Booking', 'icon' => 'solar:suitcase-outline', 'desc' => 'Flight and accommodation booking'],
    'pilgrimage' => ['label' => 'Pilgrimage', 'icon' => 'solar:moon-outline', 'desc' => 'Hajj/Umrah pilgrimage packages'],
    'other' => ['label' => 'Other Services', 'icon' => 'solar:document-text-outline', 'desc' => 'Other travel and visa services']
];

// Handle form submission - Store data in session and redirect to payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proceed_to_payment'])) {
    $requiredFields = ['case_type', 'title', 'destination_country'];

    // Validate required fields
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $error = "Please fill in all required fields.";
            break;
        }
    }

    if (empty($error)) {
        $caseType = $_POST['case_type'];
        $casePricing = getCasePricing($caseType);
        $amount = $casePricing['amount'];

        if ($amount <= 0) {
            $error = "Invalid application type or pricing not set. Please contact support.";
        } else {
            // Store application data in session for after payment
            $_SESSION['pending_application'] = [
                'case_type' => $caseType,
                'title' => $_POST['title'],
                'description' => $_POST['description'] ?? '',
                'destination_country' => $_POST['destination_country'],
                'institution' => $_POST['institution'] ?? '',
                'program' => $_POST['program'] ?? '',
                'intake' => $_POST['intake'] ?? '',
                'amount' => $amount,
                'commission' => calculateCommission($caseType, $amount)
            ];

            // Generate unique reference
            $reference = 'APP_' . time() . '_' . $user['id'] . '_' . strtoupper(substr(md5(uniqid()), 0, 6));

            // Store payment record as pending
            $userId = $user['id'];
            $metadata = json_encode($_SESSION['pending_application']);
            mysqli_query($conn, "INSERT INTO payments (user_id, reference, amount, case_type, metadata, status) 
                                VALUES ('$userId', '$reference', '$amount', '$caseType', '" . mysqli_real_escape_string($conn, $metadata) . "', 'pending')");

            // Redirect to Paystack - amount in Naira (Paystack library handles conversion)
            $callbackUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/smile-dove/user/verify_payment.php";
            makePayment($user['email'], $amount, $callbackUrl, $reference);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>ApplyBoard Africa Ltd || New Application</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="../images/favicon.png">
    <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/config.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
    <style>
        .pricing-card {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .pricing-card:hover {
            border-color: #0d6efd;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.15);
        }

        .pricing-card.selected {
            border-color: #0d6efd;
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f4f8 100%);
        }

        .pricing-card .price {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0d6efd;
        }

        .pricing-card .price small {
            font-size: 0.875rem;
            font-weight: 400;
            color: #6c757d;
        }

        .pricing-card .icon-wrap {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .pricing-summary {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 20px;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .step {
            display: flex;
            align-items: center;
            color: #6c757d;
        }

        .step.active {
            color: #0d6efd;
            font-weight: 600;
        }

        .step.completed {
            color: #198754;
        }

        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            font-weight: 600;
        }

        .step.active .step-number {
            background: #0d6efd;
            color: white;
        }

        .step.completed .step-number {
            background: #198754;
            color: white;
        }

        .step-connector {
            width: 60px;
            height: 2px;
            background: #e9ecef;
            margin: 0 15px;
        }

        .step.completed+.step-connector {
            background: #198754;
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
                            <h4 class="mb-0">New Application</h4>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">New Application</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <iconify-icon icon="solar:danger-circle-outline"></iconify-icon> <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step active" id="step1-indicator">
                        <span class="step-number">1</span>
                        <span>Select Service</span>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step" id="step2-indicator">
                        <span class="step-number">2</span>
                        <span>Application Details</span>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step" id="step3-indicator">
                        <span class="step-number">3</span>
                        <span>Payment</span>
                    </div>
                </div>

                <form method="POST" enctype="multipart/form-data" id="applicationForm">
                    <!-- Step 1: Select Service Type -->
                    <div class="form-section active" id="step1">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0"><iconify-icon icon="solar:tag-price-outline"
                                                class="me-2"></iconify-icon>Select Application Type</h5>
                                        <p class="text-muted mb-0 small">Choose the service you need. Prices are
                                            displayed in Nigerian Naira (₦)</p>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                        // Check if any pricing is configured
                                        $hasConfiguredPricing = false;
                                        foreach ($pricing as $p) {
                                            if (($p['amount'] ?? 0) > 0) {
                                                $hasConfiguredPricing = true;
                                                break;
                                            }
                                        }
                                        ?>

                                        <?php if (!$hasConfiguredPricing): ?>
                                            <div class="text-center py-5">
                                                <iconify-icon icon="solar:settings-outline" class="text-warning"
                                                    style="font-size: 64px;"></iconify-icon>
                                                <h5 class="mt-3">Pricing Not Configured</h5>
                                                <p class="text-muted">Application pricing has not been set up yet. Please
                                                    contact support or try again later.</p>
                                                <a href="index.php" class="btn btn-primary">
                                                    <iconify-icon icon="solar:arrow-left-outline"></iconify-icon> Back to
                                                    Dashboard
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <div class="row g-3">
                                                <?php foreach ($caseTypeLabels as $type => $info):
                                                    $price = $pricing[$type]['amount'] ?? 0;
                                                    if ($price <= 0)
                                                        continue; // Skip types without pricing
                                                    ?>
                                                    <div class="col-md-6 col-lg-4">
                                                        <div class="pricing-card p-3" data-type="<?= $type ?>"
                                                            data-price="<?= $price ?>">
                                                            <div class="d-flex align-items-start mb-3">
                                                                <div
                                                                    class="icon-wrap bg-primary bg-opacity-10 text-primary me-3">
                                                                    <iconify-icon icon="<?= $info['icon'] ?>"></iconify-icon>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mb-1"><?= $info['label'] ?></h6>
                                                                    <small class="text-muted"><?= $info['desc'] ?></small>
                                                                </div>
                                                            </div>
                                                            <div class="price">
                                                                ₦<?= number_format($price, 0) ?>
                                                                <small>/application</small>
                                                            </div>
                                                            <div class="mt-2">
                                                                <span class="badge bg-success-subtle text-success">
                                                                    <iconify-icon
                                                                        icon="solar:check-circle-outline"></iconify-icon>
                                                                    Available
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                            <input type="hidden" name="case_type" id="selectedCaseType" value="">

                                            <div class="text-end mt-4">
                                                <button type="button" class="btn btn-primary btn-lg" id="toStep2" disabled>
                                                    Continue <iconify-icon icon="solar:arrow-right-outline"></iconify-icon>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Application Details -->
                    <div class="form-section" id="step2">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0"><iconify-icon icon="solar:document-text-outline"
                                                class="me-2"></iconify-icon>Application Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Destination Country <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" name="destination_country" required>
                                                    <option value="">Select Country</option>
                                                    <option value="Canada">Canada</option>
                                                    <option value="United Kingdom">United Kingdom</option>
                                                    <option value="United States">United States</option>
                                                    <option value="Australia">Australia</option>
                                                    <option value="Germany">Germany</option>
                                                    <option value="France">France</option>
                                                    <option value="Ireland">Ireland</option>
                                                    <option value="New Zealand">New Zealand</option>
                                                    <option value="Dubai (UAE)">Dubai (UAE)</option>
                                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Intake/Semester</label>
                                                <select class="form-select" name="intake">
                                                    <option value="">Select Intake</option>
                                                    <option value="January 2025">January 2025</option>
                                                    <option value="May 2025">May 2025</option>
                                                    <option value="September 2025">September 2025</option>
                                                    <option value="January 2026">January 2026</option>
                                                    <option value="May 2026">May 2026</option>
                                                    <option value="September 2026">September 2026</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 col-12">
                                                <label class="form-label">Application Title <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="title" required
                                                    placeholder="e.g., Fall 2025 Study in Canada">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Institution/University</label>
                                                <input type="text" class="form-control" name="institution"
                                                    placeholder="e.g., University of Toronto">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Program/Course</label>
                                                <input type="text" class="form-control" name="program"
                                                    placeholder="e.g., BSc Computer Science">
                                            </div>
                                            <div class="mb-3 col-12">
                                                <label class="form-label">Additional Details</label>
                                                <textarea class="form-control" name="description" rows="3"
                                                    placeholder="Tell us more about your application requirements..."></textarea>
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <h6 class="mb-3"><iconify-icon icon="solar:file-outline"></iconify-icon> Upload
                                            Documents (Optional)</h6>
                                        <p class="text-muted small mb-3">You can upload documents now or later through
                                            your case dashboard. Accepted: PDF, JPG, PNG (Max 5MB each)</p>

                                        <div id="documentUploads">
                                            <div class="row g-2 mb-2 document-row">
                                                <div class="col-md-5">
                                                    <select class="form-select" name="document_types[]">
                                                        <option value="passport">Passport</option>
                                                        <option value="transcript">Transcript</option>
                                                        <option value="certificate">Certificate</option>
                                                        <option value="statement_of_purpose">Statement of Purpose
                                                        </option>
                                                        <option value="cv">CV/Resume</option>
                                                        <option value="recommendation">Recommendation Letter</option>
                                                        <option value="financial_proof">Financial Proof</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="file" class="form-control" name="documents[]"
                                                        accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                                        onclick="removeDocument(this)">
                                                        <iconify-icon
                                                            icon="solar:trash-bin-trash-outline"></iconify-icon>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-outline-primary btn-sm mt-2"
                                            onclick="addDocumentRow()">
                                            <iconify-icon icon="solar:add-circle-outline"></iconify-icon> Add More
                                            Documents
                                        </button>

                                        <div class="d-flex justify-content-between mt-4">
                                            <button type="button" class="btn btn-outline-secondary" id="backToStep1">
                                                <iconify-icon icon="solar:arrow-left-outline"></iconify-icon> Back
                                            </button>
                                            <button type="button" class="btn btn-primary" id="toStep3">
                                                Review & Pay <iconify-icon
                                                    icon="solar:arrow-right-outline"></iconify-icon>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="card sticky-top" style="top: 80px;">
                                    <div class="card-header">
                                        <h5 class="mb-0">Order Summary</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="pricing-summary">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Service Type:</span>
                                                <strong id="summaryType">-</strong>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold">Total Amount:</span>
                                                <span class="fw-bold text-primary fs-5" id="summaryPrice">₦0</span>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <iconify-icon icon="solar:shield-check-outline"
                                                    class="text-success"></iconify-icon>
                                                Secure payment via Paystack
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h6 class="mb-0">Documents Checklist</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled small mb-0">
                                            <li class="mb-2"><iconify-icon icon="solar:check-circle-outline"
                                                    class="text-success"></iconify-icon> Valid Passport</li>
                                            <li class="mb-2"><iconify-icon icon="solar:check-circle-outline"
                                                    class="text-success"></iconify-icon> Academic Transcripts</li>
                                            <li class="mb-2"><iconify-icon icon="solar:check-circle-outline"
                                                    class="text-success"></iconify-icon> Certificates/Degrees</li>
                                            <li class="mb-2"><iconify-icon icon="solar:check-circle-outline"
                                                    class="text-success"></iconify-icon> Statement of Purpose</li>
                                            <li class="mb-2"><iconify-icon icon="solar:check-circle-outline"
                                                    class="text-success"></iconify-icon> CV/Resume</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Review & Payment -->
                    <div class="form-section" id="step3">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0"><iconify-icon icon="solar:card-outline"
                                                class="me-2"></iconify-icon>Review & Payment</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-muted mb-3">Application Summary</h6>
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td class="text-muted">Service Type:</td>
                                                        <td class="fw-bold" id="reviewType">-</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Title:</td>
                                                        <td id="reviewTitle">-</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Destination:</td>
                                                        <td id="reviewDestination">-</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Intake:</td>
                                                        <td id="reviewIntake">-</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-muted mb-3">Payment Details</h6>
                                                <div class="pricing-summary">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span>Application Fee:</span>
                                                        <span id="reviewPrice">₦0</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span>Processing Fee:</span>
                                                        <span>₦0</span>
                                                    </div>
                                                    <hr>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="fw-bold">Total:</span>
                                                        <span class="fw-bold text-primary fs-4"
                                                            id="reviewTotal">₦0</span>
                                                    </div>
                                                </div>

                                                <div class="mt-3 p-3 bg-light rounded">
                                                    <div class="d-flex align-items-center">
                                                        <iconify-icon icon="solar:shield-check-outline"
                                                            class="text-success fs-4 me-2"></iconify-icon>
                                                        <div>
                                                            <strong>Secure Payment</strong>
                                                            <br><small class="text-muted">256-bit SSL encryption via
                                                                Paystack</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                            <label class="form-check-label" for="agreeTerms">
                                                I agree to the <a href="#" class="text-primary">Terms of Service</a> and
                                                <a href="#" class="text-primary">Privacy Policy</a>
                                            </label>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-secondary" id="backToStep2">
                                                <iconify-icon icon="solar:arrow-left-outline"></iconify-icon> Back
                                            </button>
                                            <button type="submit" name="proceed_to_payment"
                                                class="btn btn-success btn-lg">
                                                <iconify-icon icon="solar:card-outline"></iconify-icon> Pay Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

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

    <script>
        // Pricing data from PHP
        const caseTypeLabels = <?= json_encode(array_map(function ($info) {
            return $info['label'];
        }, $caseTypeLabels)) ?>;

        let selectedType = '';
        let selectedPrice = 0;

        // Handle pricing card selection
        document.querySelectorAll('.pricing-card').forEach(card => {
            card.addEventListener('click', function () {
                // Remove selected from all
                document.querySelectorAll('.pricing-card').forEach(c => c.classList.remove('selected'));
                // Add selected to clicked
                this.classList.add('selected');

                selectedType = this.dataset.type;
                selectedPrice = parseFloat(this.dataset.price);

                document.getElementById('selectedCaseType').value = selectedType;
                document.getElementById('toStep2').disabled = false;

                // Update summaries
                updateSummary();
            });
        });

        function updateSummary() {
            const label = caseTypeLabels[selectedType] || selectedType;
            const formattedPrice = '₦' + selectedPrice.toLocaleString();

            document.getElementById('summaryType').textContent = label;
            document.getElementById('summaryPrice').textContent = formattedPrice;
            document.getElementById('reviewType').textContent = label;
            document.getElementById('reviewPrice').textContent = formattedPrice;
            document.getElementById('reviewTotal').textContent = formattedPrice;
        }

        // Step navigation
        document.getElementById('toStep2').addEventListener('click', function () {
            if (!selectedType) {
                alert('Please select an application type');
                return;
            }
            goToStep(2);
        });

        document.getElementById('backToStep1').addEventListener('click', function () {
            goToStep(1);
        });

        document.getElementById('toStep3').addEventListener('click', function () {
            const title = document.querySelector('input[name="title"]').value;
            const destination = document.querySelector('select[name="destination_country"]').value;

            if (!title || !destination) {
                alert('Please fill in all required fields');
                return;
            }

            // Update review section
            document.getElementById('reviewTitle').textContent = title;
            document.getElementById('reviewDestination').textContent = destination;
            document.getElementById('reviewIntake').textContent = document.querySelector('select[name="intake"]').value || '-';

            goToStep(3);
        });

        document.getElementById('backToStep2').addEventListener('click', function () {
            goToStep(2);
        });

        function goToStep(step) {
            // Hide all sections
            document.querySelectorAll('.form-section').forEach(s => s.classList.remove('active'));
            // Show target section
            document.getElementById('step' + step).classList.add('active');

            // Update step indicators
            for (let i = 1; i <= 3; i++) {
                const indicator = document.getElementById('step' + i + '-indicator');
                indicator.classList.remove('active', 'completed');
                if (i < step) {
                    indicator.classList.add('completed');
                } else if (i === step) {
                    indicator.classList.add('active');
                }
            }

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Document upload functions
        function addDocumentRow() {
            const container = document.getElementById('documentUploads');
            const newRow = document.createElement('div');
            newRow.className = 'row g-2 mb-2 document-row';
            newRow.innerHTML = `
                <div class="col-md-5">
                    <select class="form-select" name="document_types[]">
                        <option value="passport">Passport</option>
                        <option value="transcript">Transcript</option>
                        <option value="certificate">Certificate</option>
                        <option value="statement_of_purpose">Statement of Purpose</option>
                        <option value="cv">CV/Resume</option>
                        <option value="recommendation">Recommendation Letter</option>
                        <option value="financial_proof">Financial Proof</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="file" class="form-control" name="documents[]" accept=".pdf,.jpg,.jpeg,.png">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeDocument(this)">
                        <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                    </button>
                </div>
            `;
            container.appendChild(newRow);
        }

        function removeDocument(button) {
            const rows = document.querySelectorAll('.document-row');
            if (rows.length > 1) {
                button.closest('.document-row').remove();
            }
        }

        // Form validation
        document.getElementById('applicationForm').addEventListener('submit', function (e) {
            if (!document.getElementById('agreeTerms').checked) {
                e.preventDefault();
                alert('Please agree to the Terms of Service and Privacy Policy');
                return false;
            }
        });
    </script>

</body>

</html>
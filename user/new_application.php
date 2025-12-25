<?php
include "../config/config.php";
include "../config/case_helper.php";
if (!isLoggedIn('user')) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

$user = auth('user');
$success = false;
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredFields = ['case_type', 'title', 'destination_country'];

    // Validate required fields
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $error = "Please fill in all required fields.";
            break;
        }
    }

    if (empty($error)) {
        // Get agent ID from user or session (referral tracking)
        $agentId = isset($user['agent_id']) ? $user['agent_id'] : 0;

        // Prepare case data
        $caseData = [
            'client_id' => $user['id'],
            'agent_id' => $agentId,
            'case_type' => $_POST['case_type'],
            'title' => $_POST['title'],
            'description' => $_POST['description'] ?? '',
            'destination_country' => $_POST['destination_country'],
            'institution' => $_POST['institution'] ?? '',
            'program' => $_POST['program'] ?? '',
            'intake' => $_POST['intake'] ?? '',
            'amount' => $_POST['estimated_budget'] ?? 0,
            'created_by' => $user['id'],
            'created_by_type' => 'client'
        ];

        $caseId = createCase($caseData);

        if ($caseId) {
            // Handle document uploads if any
            if (isset($_FILES['documents']) && !empty($_FILES['documents']['name'][0])) {
                foreach ($_FILES['documents']['name'] as $key => $name) {
                    if (!empty($_FILES['documents']['tmp_name'][$key])) {
                        $file = [
                            'name' => $_FILES['documents']['name'][$key],
                            'type' => $_FILES['documents']['type'][$key],
                            'tmp_name' => $_FILES['documents']['tmp_name'][$key],
                            'error' => $_FILES['documents']['error'][$key],
                            'size' => $_FILES['documents']['size'][$key]
                        ];

                        $docType = $_POST['document_types'][$key] ?? 'other';
                        uploadDocument($file, $user['id'], $docType, $caseId, 'client', $user['id']);
                    }
                }
            }

            $success = true;
        } else {
            $error = "Failed to create application. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="utf-8" />
     <title>ApplyBoard Africa Ltd User || New Application</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <link rel="shortcut icon" href="../images/favicon.png">
     <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />
     <script src="assets/js/config.js"></script>
     <!-- Iconify -->
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
                                   <h4 class="mb-0">New Application</h4>
                                   <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                        <li class="breadcrumb-item active">New Application</li>
                                   </ol>
                              </div>
                         </div>
                    </div>

        <?php if ($success): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <iconify-icon icon="solar:check-circle-outline" class="fs-64 text-success mb-3"></iconify-icon>
                            <h3 class="mb-3">Application Submitted Successfully!</h3>
                            <p class="text-muted mb-4">Your application has been created. You can track its progress in your cases section.</p>
                            <div>
                                <a href="cases.php" class="btn btn-primary">View My Cases</a>
                                <a href="new_application.php" class="btn btn-outline-primary">New Application</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>

            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <iconify-icon icon="solar:danger-circle-outline"></iconify-icon> <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Application Details</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Application Type <span class="text-danger">*</span></label>
                                        <select class="form-select" name="case_type" required>
                                            <option value="">Select Type</option>
                                            <option value="study_abroad">Study Abroad</option>
                                            <option value="visa_student">Student Visa</option>
                                            <option value="visa_tourist">Tourist Visa</option>
                                            <option value="travel_booking">Travel Booking</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Destination Country <span class="text-danger">*</span></label>
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
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-12">
                                        <label class="form-label">Application Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title" required placeholder="e.g., Fall 2025 Study in Canada">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Intake/Semester</label>
                                        <select class="form-select" name="intake">
                                            <option value="">Select Intake</option>
                                            <option value="January 2025">January 2025</option>
                                            <option value="May 2025">May 2025</option>
                                            <option value="September 2025">September 2025</option>
                                            <option value="January 2026">January 2026</option>
                                            <option value="September 2026">September 2026</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Estimated Budget (USD)</label>
                                        <input type="number" class="form-control" name="estimated_budget" placeholder="e.g., 15000">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Institution/University</label>
                                        <input type="text" class="form-control" name="institution" placeholder="e.g., University of Toronto">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Program/Course</label>
                                        <input type="text" class="form-control" name="program" placeholder="e.g., BSc Computer Science">
                                    </div>
                                    <div class="mb-3 col-12">
                                        <label class="form-label">Additional Details</label>
                                        <textarea class="form-control" name="description" rows="3" placeholder="Tell us more about your application requirements..."></textarea>
                                    </div>

                                    <hr class="my-4">

                                    <h6 class="mb-3">Upload Documents (Optional)</h6>
                                    <p class="text-muted small mb-3">You can upload documents now or later through your case dashboard. Accepted: PDF, JPG, PNG (Max 5MB each)</p>

                                    <div id="documentUploads">
                                        <div class="row g-2 mb-2 document-row">
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
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addDocumentRow()">
                                        <iconify-icon icon="solar:add-circle-outline"></iconify-icon> Add More Documents
                                    </button>

                                    <hr class="my-4">

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                        <label class="form-check-label" for="agreeTerms">
                                            I agree to the <a href="#" class="text-primary">Terms of Service</a> and <a href="#" class="text-primary">Privacy Policy</a>
                                        </label>
                                    </div>

                                    <div class="text-end">
                                        <a href="cases.php" class="btn btn-outline-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Submit Application</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Need Help?</h5>
                        </div>
                        <div class="card-body">
                            <p class="small text-muted">Our team is here to assist you with your application.</p>
                            <div class="d-grid gap-2">
                                <a href="mailto:info@applyboardafrica.com" class="btn btn-outline-primary btn-sm">
                                    <iconify-icon icon="solar:letter-outline"></iconify-icon> Email Support
                                </a>
                                <a href="https://wa.me/2348000000000" class="btn btn-success btn-sm">
                                    <iconify-icon icon="solar:boldly-chat-outline"></iconify-icon> WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Documents Checklist</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2"><iconify-icon icon="solar:check-circle-outline" class="text-success"></iconify-icon> Valid Passport</li>
                                <li class="mb-2"><iconify-icon icon="solar:check-circle-outline" class="text-success"></iconify-icon> Academic Transcripts</li>
                                <li class="mb-2"><iconify-icon icon="solar:check-circle-outline" class="text-success"></iconify-icon> Certificates/Degrees</li>
                                <li class="mb-2"><iconify-icon icon="solar:check-circle-outline" class="text-success"></iconify-icon> Statement of Purpose</li>
                                <li class="mb-2"><iconify-icon icon="solar:check-circle-outline" class="text-success"></iconify-icon> CV/Resume</li>
                                <li class="mb-2"><iconify-icon icon="solar:check-circle-outline" class="text-success"></iconify-icon> Reference Letters</li>
                                <li class="mb-2"><iconify-icon icon="solar:check-circle-outline" class="text-success"></iconify-icon> Financial Proof</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

               </div>

               <footer class="footer card mb-0 rounded-0 justify-content-center align-items-center">
                    <div class="container-fluid">
                         <div class="row">
                              <div class="col-12 text-center">
                                   <p class="mb-0"><script>document.write(new Date().getFullYear())</script> &copy; ApplyBoard Africa Ltd.</p>
                              </div>
                         </div>
                    </div>
               </footer>

          </div>
     </div>

     <script src="assets/js/vendor.min.js"></script>
     <script src="assets/js/app.js"></script>

     <script>
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
     </script>

</body>
</html>

<?php
include "../config/config.php";
include "../config/case_helper.php";
if (!isLoggedIn('user')) {
    echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}
$user = auth('user');
$view = isset($_GET['view']) ? intval($_GET['view']) : null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title Meta -->
    <meta charset="utf-8" />
    <title>ApplyBoard Africa Ltd User || My Cases</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="robots" content="index, follow" />
    <meta name="theme-color" content="#ffffff">

    <!-- App favicon -->
    <link rel="shortcut icon" href="../images/favicon.png">

    <!-- Google Font Family link -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2c4ad.css?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
        rel="stylesheet">

    <!-- Vendor css -->
    <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />

    <!-- Icons css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />
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
                <?php if (!$view): ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">My Cases</h4>
                                <a href="new_application.php" class="btn btn-primary btn-sm">New Application</a>
                            </div>
                        </div>
                    </div>
                    <!-- ========== Page Title End ========== -->
                <?php endif; ?>

                <?php if ($view): ?>
                    <!-- Single Case View -->
                    <?php
                    $case = getCase($view);

                    // Verify this case belongs to this user
                    if ($case['client_id'] != $user['id']) {
                        echo "<script>alert('Access denied'); location.href = 'cases.php';</script>";
                        exit;
                    }

                    $stageHistory = mysqli_query($conn, "SELECT h.*, a.fullname, m.email as admin_email
                FROM `case_stages_history` h
                LEFT JOIN `agents` a ON h.changed_by = a.id AND h.changed_by_type = 'agent'
                LEFT JOIN `admin` m ON h.changed_by = m.id AND h.changed_by_type = 'admin'
                WHERE h.case_id = '$view'
                ORDER BY h.created_at ASC");
                    $documents = getCaseDocuments($view);
                    ?>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><?= htmlspecialchars($case['case_number']) ?>:
                                        <?= htmlspecialchars($case['title']) ?></h5>
                                    <a href="cases.php" class="btn btn-outline-secondary btn-sm">Back to List</a>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="fw-bold">Case Type:</label>
                                            <p><span
                                                    class="badge bg-info"><?= getCaseTypeLabel($case['case_type']) ?></span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Current Stage:</label>
                                            <p><span
                                                    class="badge <?= getStageBadge($case['stage']) ?>"><?= getStageLabel($case['case_type'], $case['stage']) ?></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="fw-bold">Status:</label>
                                            <p><span
                                                    class="badge <?= $case['status'] == 'active' ? 'bg-success' : ($case['status'] == 'completed' ? 'bg-primary' : 'bg-warning') ?>"><?= ucfirst($case['status']) ?></span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Assigned Agent:</label>
                                            <p><?= htmlspecialchars($case['agent_name']) ?></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="fw-bold">Destination:</label>
                                            <p><?= htmlspecialchars($case['destination_country'] ?: 'N/A') ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Institution:</label>
                                            <p><?= htmlspecialchars($case['institution'] ?: 'N/A') ?></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="fw-bold">Program:</label>
                                            <p><?= htmlspecialchars($case['program'] ?: 'N/A') ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Intake:</label>
                                            <p><?= htmlspecialchars($case['intake'] ?: 'N/A') ?></p>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="fw-bold">Description:</label>
                                        <p><?= nl2br(htmlspecialchars($case['description'] ?: 'No description')) ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Stage History -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="mb-0">Case History</h5>
                                </div>
                                <div class="card-body">
                                    <div class="timeline border-start border-primary ps-3 ms-2">
                                        <?php while ($history = mysqli_fetch_assoc($stageHistory)):
                                            $changerName = $history['admin_email'] ?: $history['fullname'] ?: 'System';
                                            ?>
                                            <div class="timeline-item mb-3">
                                                <small
                                                    class="text-muted"><?= date('d M Y H:i', strtotime($history['created_at'])) ?></small>
                                                <p class="mb-0">
                                                    <strong><?= $changerName ?></strong> updated the case to
                                                    <span
                                                        class="badge bg-primary"><?= getStageLabelFromStage($history['to_stage']) ?></span>
                                                </p>
                                                <?php if ($history['notes']): ?>
                                                    <small class="text-muted"><?= htmlspecialchars($history['notes']) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Documents -->
                            <div class="card mt-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">My Documents</h5>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#uploadDocModal">Upload Document</button>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($documents)): ?>
                                        <p class="text-muted">No documents uploaded yet.</p>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Type</th>
                                                        <th>File</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($documents as $doc): ?>
                                                        <tr>
                                                            <td><?= ucfirst(str_replace('_', ' ', $doc['document_type'])) ?></td>
                                                            <td><a href="../<?= $doc['file_path'] ?>"
                                                                    target="_blank"><?= htmlspecialchars($doc['file_name']) ?></a>
                                                            </td>
                                                            <td><span
                                                                    class="badge bg-<?= $doc['status'] == 'verified' ? 'success' : ($doc['status'] == 'rejected' ? 'danger' : 'warning') ?>"><?= ucfirst($doc['status']) ?></span>
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

                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Case Progress</h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $availableStages = getCaseStages($case['case_type']);
                                    $stageKeys = array_keys($availableStages);
                                    $currentIndex = array_search($case['stage'], $stageKeys);
                                    ?>
                                    <div class="progress mb-3" style="height: 30px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: <?= (($currentIndex + 1) / count($stageKeys)) * 100 ?>%">
                                            <?= round((($currentIndex + 1) / count($stageKeys)) * 100) ?>%
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($availableStages as $key => $label): ?>
                                            <?php
                                            $itemIndex = array_search($key, $stageKeys);
                                            $isComplete = $itemIndex < $currentIndex;
                                            $isCurrent = $key == $case['stage'];
                                            ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><?= $label ?></span>
                                                <?php if ($isComplete): ?>
                                                    <span class="badge bg-success">✓</span>
                                                <?php elseif ($isCurrent): ?>
                                                    <span class="badge bg-primary">Current</span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="mb-0">Contact Support</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small">Need help with your application? Contact our support team.
                                    </p>
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

                <?php else: ?>
                    <!-- Cases List -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-centered mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Case #</th>
                                                    <th>Title</th>
                                                    <th>Type</th>
                                                    <th>Stage</th>
                                                    <th>Status</th>
                                                    <th>Created</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $cases = getCases(['client_id' => $user['id']]);

                                                if (empty($cases)):
                                                    ?>
                                                    <tr>
                                                        <td colspan="7" class="text-center">No cases found. <a
                                                                href="index.php">Start an application</a></td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($cases as $row): ?>
                                                        <tr>
                                                            <td><strong><?= htmlspecialchars($row['case_number']) ?></strong></td>
                                                            <td><?= htmlspecialchars($row['title']) ?></td>
                                                            <td><span
                                                                    class="badge bg-secondary"><?= getCaseTypeLabel($row['case_type']) ?></span>
                                                            </td>
                                                            <td><span
                                                                    class="badge <?= getStageBadge($row['stage']) ?>"><?= getStageLabelFromStage($row['stage']) ?></span>
                                                            </td>
                                                            <td><span
                                                                    class="badge <?= $row['status'] == 'active' ? 'bg-success' : ($row['status'] == 'completed' ? 'bg-primary' : 'bg-warning') ?>"><?= ucfirst($row['status']) ?></span>
                                                            </td>
                                                            <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                                                            <td>
                                                                <a href="?view=<?= $row['id'] ?>"
                                                                    class="btn btn-sm btn-outline-primary">View</a>
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
                <?php endif; ?>

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

    <!-- Upload Document Modal -->
    <div class="modal fade" id="uploadDocModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data" action="upload_document.php">
                    <input type="hidden" name="case_id" value="<?= $view ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Document</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Document Type</label>
                            <select name="document_type" class="form-select" required>
                                <option value="passport">Passport</option>
                                <option value="transcript">Transcript</option>
                                <option value="certificate">Certificate</option>
                                <option value="statement_of_purpose">Statement of Purpose</option>
                                <option value="cv">CV/Resume</option>
                                <option value="recommendation">Recommendation Letter</option>
                                <option value="financial_proof">Financial Proof</option>
                                <option value="visa">Visa</option>
                                <option value="offer_letter">Offer Letter</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">File</label>
                            <input type="file" name="document" class="form-control" required>
                            <small class="text-muted">PDF, JPG, PNG. Max 5MB.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Vendor Javascript -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App Javascript -->
    <script src="assets/js/app.js"></script>

</body>

</html>
<?php
include "../config/config.php";
if (!isset($_SESSION['sdtravels_manager'])) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}
include "../config/case_helper.php";

$manager = auth('admin');
$view = isset($_GET['view']) ? intval($_GET['view']) : null;
$stageFilter = isset($_GET['stage']) ? $_GET['stage'] : null;
$statusFilter = isset($_GET['status']) ? $_GET['status'] : null;

// Handle Stage Update
if (isset($_POST['update_stage']) && isset($_POST['case_id']) && isset($_POST['new_stage'])) {
    $caseId = intval($_POST['case_id']);
    $newStage = mysqli_real_escape_string($conn, $_POST['new_stage']);
    $notes = isset($_POST['notes']) ? mysqli_real_escape_string($conn, $_POST['notes']) : '';

    if (updateCaseStage($caseId, $newStage, $manager['id'], 'admin', $notes)) {
        $msg = "Case stage updated successfully";
    } else {
        $err = "Failed to update case stage";
    }
}

// Handle Status Update
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    $status = 'active';

    if ($action == 'on_hold') $status = 'on_hold';
    if ($action == 'cancelled') $status = 'cancelled';
    if ($action == 'completed') $status = 'completed';

    $sql = "UPDATE `cases` SET `status` = '$status' WHERE `id` = $id";
    if (mysqli_query($conn, $sql)) {
        logActivity($manager['id'], 'admin', 'case_status_updated', 'case', $id, "Case status changed to $status");
        echo "<script>alert('Status updated to $status'); location.href = 'cases.php';</script>";
    }
}

// Convert inquiry to case
if (isset($_GET['convert']) && isset($_GET['inquiry_id'])) {
    $inquiryId = intval($_GET['inquiry_id']);

    // Get inquiry details
    $inquiry = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `inquiries` WHERE `id` = '$inquiryId'"));

    if ($inquiry) {
        // Check if user exists or create one
        $userCheck = mysqli_fetch_assoc(mysqli_query($conn, "SELECT `id` FROM `users` WHERE `email` = '{$inquiry['email']}'"));

        if (!$userCheck) {
            // Create user account
            $password = password_hash('Password123', PASSWORD_DEFAULT);
            mysqli_query($conn, "INSERT INTO `users` (`userid`, `fullname`, `email`, `password`, `agent_id`)
                    VALUES ('" . uniqid() . "', '{$inquiry['name']}', '{$inquiry['email']}', '$password', '{$inquiry['agent_id']}')");
            $clientId = mysqli_insert_id($conn);
        } else {
            $clientId = $userCheck['id'];
        }

        // Create case
        $caseData = [
            'client_id' => $clientId,
            'agent_id' => $inquiry['agent_id'] ?: 1, // Default to admin if no agent
            'case_type' => $inquiry['service_type'] ?: 'other',
            'title' => 'Case from Inquiry - ' . $inquiry['name'],
            'description' => $inquiry['message'],
            'created_by' => $manager['id'],
            'created_by_type' => 'admin'
        ];

        $caseId = createCase($caseData);

        if ($caseId) {
            // Update inquiry
            mysqli_query($conn, "UPDATE `inquiries` SET `converted_to_case` = 1, `case_id` = '$caseId' WHERE `id` = '$inquiryId'");
            echo "<script>alert('Case created successfully'); location.href = 'cases.php?view=$caseId';</script>";
        } else {
            echo "<script>alert('Failed to create case');</script>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="utf-8" />
     <title>ApplyBoard Africa Ltd || Cases</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <link rel="shortcut icon" href="../images/favicon.png">
     <link href="https://fonts.googleapis.com/css2c4ad.css?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
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
                <div class="page-title-box d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Case Management</h4>
                    <div>
                        <a href="cases.php?convert=new" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#convertModal">
                            <i class="fas fa-plus"></i> New Case
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($msg)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $msg ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (isset($err)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $err ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if ($view): ?>
            <!-- Single Case View -->
            <?php
            $case = getCase($view);
            $stageHistory = mysqli_query($conn, "SELECT h.*, a.fullname, u.fullname as admin_name
                FROM `case_stages_history` h
                LEFT JOIN `agents` a ON h.changed_by = a.id AND h.changed_by_type = 'agent'
                LEFT JOIN `admin` u ON h.changed_by = u.id AND h.changed_by_type = 'admin'
                WHERE h.case_id = '$view'
                ORDER BY h.created_at ASC");
            $documents = getCaseDocuments($view);
            $availableStages = isset($case['case_type']) ? getCaseStages($case['case_type']) : [];
            ?>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><?= htmlspecialchars($case['case_number']) ?>: <?= htmlspecialchars($case['title']) ?></h5>
                            <a href="cases.php" class="btn btn-outline-secondary btn-sm">Back to List</a>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="fw-bold">Client:</label>
                                    <p><?= htmlspecialchars($case['client_name']) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold">Agent:</label>
                                    <p><?= htmlspecialchars($case['agent_name']) ?> (<?= htmlspecialchars($case['agent_code']) ?>)</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="fw-bold">Case Type:</label>
                                    <p><span class="badge bg-info"><?= getCaseTypeLabel($case['case_type']) ?></span></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="fw-bold">Current Stage:</label>
                                    <p><span class="badge <?= getStageBadge($case['stage']) ?>"><?= getStageLabel($case['case_type'], $case['stage']) ?></span></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="fw-bold">Status:</label>
                                    <p><span class="badge <?= $case['status'] == 'active' ? 'bg-success' : ($case['status'] == 'completed' ? 'bg-primary' : 'bg-warning') ?>"><?= ucfirst($case['status']) ?></span></p>
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
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="fw-bold">Amount:</label>
                                    <p>₦<?= number_format($case['amount'], 2) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold">Commission:</label>
                                    <p>₦<?= number_format($case['commission_amount'], 2) ?> <span class="badge bg-<?= $case['commission_paid'] == 'paid' ? 'success' : 'warning' ?>"><?= ucfirst($case['commission_paid']) ?></span></p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Notes:</label>
                                <p><?= nl2br(htmlspecialchars($case['notes'] ?: 'No notes')) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Stage History -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">Stage History</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline border-start border-primary ps-3 ms-2">
                                <?php while ($history = mysqli_fetch_assoc($stageHistory)):
                                    $changerName = $history['admin_name'] ?: $history['fullname'] ?: 'System';
                                ?>
                                <div class="timeline-item mb-3">
                                    <small class="text-muted"><?= date('d M Y H:i', strtotime($history['created_at'])) ?></small>
                                    <p class="mb-0">
                                        <strong><?= $changerName ?></strong> changed stage
                                        <?php if ($history['from_stage']): ?>
                                            from <span class="badge bg-secondary"><?= getStageLabelFromStage($history['from_stage']) ?></span>
                                        <?php endif; ?>
                                        to <span class="badge bg-primary"><?= getStageLabelFromStage($history['to_stage']) ?></span>
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
                            <h5 class="mb-0">Documents</h5>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocModal">Upload Document</button>
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
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($documents as $doc): ?>
                                            <tr>
                                                <td><?= ucfirst(str_replace('_', ' ', $doc['document_type'])) ?></td>
                                                <td><a href="../<?= $doc['file_path'] ?>" target="_blank"><?= htmlspecialchars($doc['file_name']) ?></a></td>
                                                <td><span class="badge bg-<?= $doc['status'] == 'verified' ? 'success' : ($doc['status'] == 'rejected' ? 'danger' : 'warning') ?>"><?= ucfirst($doc['status']) ?></span></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-success" onclick="verifyDoc(<?= $doc['id'] ?>)">Verify</button>
                                                        <button class="btn btn-outline-danger" onclick="rejectDoc(<?= $doc['id'] ?>)">Reject</button>
                                                    </div>
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
                    <!-- Update Stage -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Update Stage</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="case_id" value="<?= $case['id'] ?>">
                                <input type="hidden" name="update_stage" value="1">
                                <div class="mb-3">
                                    <label class="form-label">New Stage</label>
                                    <select name="new_stage" class="form-select" required>
                                        <?php foreach ($availableStages as $key => $label): ?>
                                            <option value="<?= $key ?>" <?= $key == $case['stage'] ? 'selected' : '' ?>>
                                                <?= $label ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control" rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Update Stage</button>
                            </form>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="?action=on_hold&id=<?= $case['id'] ?>" class="btn btn-warning">Put On Hold</a>
                                <a href="?action=completed&id=<?= $case['id'] ?>" class="btn btn-success">Mark Completed</a>
                                <a href="?action=cancelled&id=<?= $case['id'] ?>" class="btn btn-danger">Cancel Case</a>
                                <a href="commissions.php?case_id=<?= $case['id'] ?>" class="btn btn-info">View Commissions</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- Cases List -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <select name="stage" class="form-select">
                                        <option value="">All Stages</option>
                                        <option value="assessment" <?= $stageFilter == 'assessment' ? 'selected' : '' ?>>Assessment</option>
                                        <option value="application" <?= $stageFilter == 'application' ? 'selected' : '' ?>>Application</option>
                                        <option value="submission" <?= $stageFilter == 'submission' ? 'selected' : '' ?>>Submission</option>
                                        <option value="offer" <?= $stageFilter == 'offer' ? 'selected' : '' ?>>Offer</option>
                                        <option value="visa" <?= $stageFilter == 'visa' ? 'selected' : '' ?>>Visa</option>
                                        <option value="completed" <?= $stageFilter == 'completed' ? 'selected' : '' ?>>Completed</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="active" <?= $statusFilter == 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="on_hold" <?= $statusFilter == 'on_hold' ? 'selected' : '' ?>>On Hold</option>
                                        <option value="completed" <?= $statusFilter == 'completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="cancelled" <?= $statusFilter == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control" placeholder="Search case..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

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
                                            <th>Client</th>
                                            <th>Agent</th>
                                            <th>Type</th>
                                            <th>Stage</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $filters = [];
                                        if ($stageFilter) $filters['stage'] = $stageFilter;
                                        if ($statusFilter) $filters['status'] = $statusFilter;
                                        if (isset($_GET['search'])) $filters['search'] = $_GET['search'];

                                        $cases = getCases($filters);

                                        if (empty($cases)):
                                        ?>
                                            <tr>
                                                <td colspan="9" class="text-center">No cases found</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($cases as $row): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($row['case_number']) ?></strong></td>
                                                <td><?= htmlspecialchars($row['title']) ?></td>
                                                <td><?= htmlspecialchars($row['client_name']) ?></td>
                                                <td><?= htmlspecialchars($row['agent_name']) ?></td>
                                                <td><span class="badge bg-secondary"><?= getCaseTypeLabel($row['case_type']) ?></span></td>
                                                <td><span class="badge <?= getStageBadge($row['stage']) ?>"><?= getStageLabelFromStage($row['stage']) ?></span></td>
                                                <td><span class="badge <?= $row['status'] == 'active' ? 'bg-success' : ($row['status'] == 'completed' ? 'bg-primary' : 'bg-warning') ?>"><?= ucfirst($row['status']) ?></span></td>
                                                <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                                                <td>
                                                    <a href="?view=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
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

<!-- Convert Inquiry Modal -->
<div class="modal fade" id="convertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Convert Inquiry to Case</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="GET">
                    <input type="hidden" name="convert" value="1">
                    <div class="mb-3">
                        <label class="form-label">Select Inquiry</label>
                        <select name="inquiry_id" class="form-select" required>
                            <option value="">Select an inquiry...</option>
                            <?php
                            $inquiries = mysqli_query($conn, "SELECT i.*, a.fullname as agent_name FROM `inquiries` i LEFT JOIN `agents` a ON i.agent_id = a.id WHERE i.converted_to_case = 0 ORDER BY i.created_at DESC");
                            while ($inq = mysqli_fetch_assoc($inquiries)):
                            ?>
                                <option value="<?= $inq['id'] ?>">
                                    <?= htmlspecialchars($inq['name']) ?> - <?= htmlspecialchars($inq['email']) ?> (<?= date('d M Y', strtotime($inq['created_at'])) ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Convert to Case</button>
                </form>
            </div>
        </div>
    </div>
</div>

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

<script>
function verifyDoc(id) {
    if (confirm('Verify this document?')) {
        fetch('update_document.php?id=' + id + '&status=verified').then(() => location.reload());
    }
}
function rejectDoc(id) {
    if (confirm('Reject this document?')) {
        fetch('update_document.php?id=' + id + '&status=rejected').then(() => location.reload());
    }
}
</script>

<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>

<?php
include "../config/config.php";
include "../config/case_helper.php";

if (!isLoggedIn('agent')) {
    echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
    exit;
}

$agent = auth('agent');
$view = isset($_GET['view']) ? intval($_GET['view']) : null;
$pageTitle = $view ? 'Case Details' : 'Case Files';

// Get case stats
$totalCases = countCases(['agent_id' => $agent['id']]);
$activeCases = countCases(['agent_id' => $agent['id'], 'status' => 'active']);
$completedCases = countCases(['agent_id' => $agent['id'], 'status' => 'completed']);
$pendingCases = countCases(['agent_id' => $agent['id'], 'stage' => 'assessment']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?= $pageTitle ?> | Agent Portal - ApplyBoard Africa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="theme-color" content="#0F4C75">

    <link rel="shortcut icon" href="../images/favicon.png">
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
        <?php include "partials/header.php"; ?>
        <?php include "partials/sidebar.php"; ?>

        <div class="page-content">
            <div class="container-fluid">

                <!-- Page Title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?= $view ? 'Case Details' : 'Case Files' ?></h4>
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                    <?php if ($view): ?>
                                        <li class="breadcrumb-item"><a href="cases.php">Cases</a></li>
                                        <li class="breadcrumb-item active">Details</li>
                                    <?php else: ?>
                                        <li class="breadcrumb-item active">Cases</li>
                                    <?php endif; ?>
                                </ol>
                            </div>
                            <?php if (!$view): ?>
                                <span class="badge bg-primary"><?= $totalCases ?> Total</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if ($view): ?>
                    <!-- Single Case View -->
                    <?php
                    $case = getCase($view);

                    // Verify this case belongs to this agent
                    if ($case['agent_id'] != $agent['id']) {
                        echo "<script>alert('Access denied'); location.href = 'cases.php';</script>";
                        exit;
                    }

                    $stageHistory = mysqli_query($conn, "SELECT h.*, a.fullname
                FROM `case_stages_history` h
                LEFT JOIN `agents` a ON h.changed_by = a.id AND h.changed_by_type = 'agent'
                WHERE h.case_id = '$view'
                ORDER BY h.created_at ASC");
                    $documents = getCaseDocuments($view);
                    ?>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><?= htmlspecialchars($case['case_number']) ?>:
                                        <?= htmlspecialchars($case['title']) ?>
                                    </h5>
                                    <a href="cases.php" class="btn btn-outline-secondary btn-sm">Back to List</a>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="fw-bold">Client:</label>
                                            <p><?= htmlspecialchars($case['client_name']) ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Case Type:</label>
                                            <p><span
                                                    class="badge bg-info"><?= getCaseTypeLabel($case['case_type']) ?></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="fw-bold">Current Stage:</label>
                                            <p><span
                                                    class="badge <?= getStageBadge($case['stage']) ?>"><?= getStageLabel($case['case_type'], $case['stage']) ?></span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Status:</label>
                                            <p><span
                                                    class="badge <?= $case['status'] == 'active' ? 'bg-success' : ($case['status'] == 'completed' ? 'bg-primary' : 'bg-warning') ?>"><?= ucfirst($case['status']) ?></span>
                                            </p>
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
                                            <label class="fw-bold">Your Commission:</label>
                                            <p>₦<?= number_format($case['commission_amount'], 2) ?> <span
                                                    class="badge bg-<?= $case['commission_paid'] == 'paid' ? 'success' : 'warning' ?>"><?= ucfirst($case['commission_paid']) ?></span>
                                            </p>
                                        </div>
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
                                        <?php while ($history = mysqli_fetch_assoc($stageHistory)): ?>
                                            <div class="timeline-item mb-3">
                                                <small
                                                    class="text-muted"><?= date('d M Y H:i', strtotime($history['created_at'])) ?></small>
                                                <p class="mb-0">
                                                    Stage changed
                                                    <?php if ($history['from_stage']): ?>
                                                        from <span
                                                            class="badge bg-secondary"><?= getStageLabelFromStage($history['from_stage']) ?></span>
                                                    <?php endif; ?>
                                                    to <span
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
                                <div class="card-header">
                                    <h5 class="mb-0">Documents</h5>
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
                                    <h5 class="mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addNoteModal">Add Note</button>
                                        <a href="../contact.php" class="btn btn-outline-secondary">Contact Support</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- Cases List -->

                    <!-- Stats Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-lg-3">
                            <div class="card stat-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="stat-icon primary">
                                            <iconify-icon icon="solar:folder-with-files-outline"></iconify-icon>
                                        </div>
                                        <div>
                                            <div class="stat-value"><?= number_format($totalCases) ?></div>
                                            <div class="stat-label">Total Cases</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="card stat-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="stat-icon warning">
                                            <iconify-icon icon="solar:clock-circle-outline"></iconify-icon>
                                        </div>
                                        <div>
                                            <div class="stat-value"><?= number_format($activeCases) ?></div>
                                            <div class="stat-label">Active</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="card stat-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="stat-icon success">
                                            <iconify-icon icon="solar:check-circle-outline"></iconify-icon>
                                        </div>
                                        <div>
                                            <div class="stat-value"><?= number_format($completedCases) ?></div>
                                            <div class="stat-label">Completed</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="card stat-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="stat-icon info">
                                            <iconify-icon icon="solar:hourglass-outline"></iconify-icon>
                                        </div>
                                        <div>
                                            <div class="stat-value"><?= number_format($pendingCases) ?></div>
                                            <div class="stat-label">Pending</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">All Cases</h5>
                                </div>
                                <div class="card-body p-0">
                                    <?php $cases = getCases(['agent_id' => $agent['id']]); ?>
                                    <?php if (empty($cases)): ?>
                                        <div class="text-center py-5">
                                            <div
                                                class="quick-action-icon bg-secondary bg-opacity-10 text-secondary mx-auto mb-3">
                                                <iconify-icon icon="solar:folder-open-outline"></iconify-icon>
                                            </div>
                                            <h5>No Cases Yet</h5>
                                            <p class="text-muted mb-0">Once clients start their applications, cases will appear
                                                here.</p>
                                        </div>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Case</th>
                                                        <th>Client</th>
                                                        <th>Type</th>
                                                        <th>Stage</th>
                                                        <th>Status</th>
                                                        <th>Created</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($cases as $row): ?>
                                                        <tr>
                                                            <td>
                                                                <strong><?= htmlspecialchars($row['case_number']) ?></strong>
                                                                <div class="small text-muted">
                                                                    <?= htmlspecialchars(substr($row['title'], 0, 30)) ?></div>
                                                            </td>
                                                            <td><?= htmlspecialchars($row['client_name']) ?></td>
                                                            <td><span
                                                                    class="badge bg-secondary"><?= getCaseTypeLabel($row['case_type']) ?></span>
                                                            </td>
                                                            <td><span
                                                                    class="badge <?= getStageBadge($row['stage']) ?>"><?= getStageLabelFromStage($row['stage']) ?></span>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge <?= $row['status'] == 'active' ? 'bg-success' : ($row['status'] == 'completed' ? 'bg-primary' : 'bg-warning') ?>">
                                                                    <?= ucfirst($row['status']) ?>
                                                                </span>
                                                            </td>
                                                            <td><span
                                                                    class="text-muted"><?= date('d M Y', strtotime($row['created_at'])) ?></span>
                                                            </td>
                                                            <td>
                                                                <a href="?view=<?= $row['id'] ?>"
                                                                    class="btn btn-sm btn-outline-primary">
                                                                    <iconify-icon icon="solar:eye-outline"></iconify-icon> View
                                                                </a>
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
    </div>

    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>

</html>
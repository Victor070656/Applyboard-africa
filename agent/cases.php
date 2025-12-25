<?php
include "../config/config.php";
include "../config/case_helper.php";
include "partials/header.php";
include "partials/sidebar.php";

$agent = auth('agent');
$view = isset($_GET['view']) ? intval($_GET['view']) : null;

function getStageBadge($stage) {
    $badges = [
        'assessment' => 'bg-primary',
        'options' => 'bg-info',
        'application' => 'bg-secondary',
        'submission' => 'bg-warning',
        'offer' => 'bg-success',
        'visa' => 'bg-danger',
        'travel' => 'bg-purple',
        'booking' => 'bg-orange',
        'completed' => 'bg-green',
        'closed' => 'bg-dark',
        'requirements' => 'bg-primary',
        'processing' => 'bg-info',
        'decision' => 'bg-warning'
    ];
    return isset($badges[$stage]) ? $badges[$stage] : 'bg-secondary';
}

function getCaseTypeLabel($type) {
    $labels = [
        'study_abroad' => 'Study Abroad',
        'visa_student' => 'Student Visa',
        'visa_tourist' => 'Tourist Visa',
        'visa_family' => 'Family Visa',
        'travel_booking' => 'Travel Booking',
        'pilgrimage' => 'Pilgrimage',
        'other' => 'Other'
    ];
    return isset($labels[$type]) ? $labels[$type] : ucfirst(str_replace('_', ' ', $type));
}
?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">My Cases</h4>
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
                                    <label class="fw-bold">Case Type:</label>
                                    <p><span class="badge bg-info"><?= getCaseTypeLabel($case['case_type']) ?></span></p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="fw-bold">Current Stage:</label>
                                    <p><span class="badge <?= getStageBadge($case['stage']) ?>"><?= getStageLabel($case['case_type'], $case['stage']) ?></span></p>
                                </div>
                                <div class="col-md-6">
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
                                    <p>₦<?= number_format($case['commission_amount'], 2) ?> <span class="badge bg-<?= $case['commission_paid'] == 'paid' ? 'success' : 'warning' ?>"><?= ucfirst($case['commission_paid']) ?></span></p>
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
                                    <small class="text-muted"><?= date('d M Y H:i', strtotime($history['created_at'])) ?></small>
                                    <p class="mb-0">
                                        Stage changed
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
                                                <td><a href="../<?= $doc['file_path'] ?>" target="_blank"><?= htmlspecialchars($doc['file_name']) ?></a></td>
                                                <td><span class="badge bg-<?= $doc['status'] == 'verified' ? 'success' : ($doc['status'] == 'rejected' ? 'danger' : 'warning') ?>"><?= ucfirst($doc['status']) ?></span></td>
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
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?= (($currentIndex + 1) / count($stageKeys)) * 100 ?>%">
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
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNoteModal">Add Note</button>
                                <a href="../contact.php" class="btn btn-outline-secondary">Contact Support</a>
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
                                            <th>Client</th>
                                            <th>Type</th>
                                            <th>Stage</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cases = getCases(['agent_id' => $agent['id']]);

                                        if (empty($cases)):
                                        ?>
                                            <tr>
                                                <td colspan="8" class="text-center">No cases found</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($cases as $row): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($row['case_number']) ?></strong></td>
                                                <td><?= htmlspecialchars($row['title']) ?></td>
                                                <td><?= htmlspecialchars($row['client_name']) ?></td>
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

<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>

<?php
include "../config/config.php";
include "../config/case_helper.php";
include "partials/header.php";
include "partials/sidebar.php";

$user = auth('user');

// Handle document upload
if (isset($_FILES['document']) && isset($_POST['case_id']) && isset($_POST['document_type'])) {
    $caseId = intval($_POST['case_id']);
    $case = getCase($caseId);

    if (!$case || $case['client_id'] != $user['id']) {
        die("Access denied");
    }

    $result = uploadDocument(
        $_FILES['document'],
        $user['id'],
        $_POST['document_type'],
        $caseId,
        'client',
        $user['id']
    );

    if ($result['success']) {
        echo "<script>alert('Document uploaded successfully'); location.href = 'documents.php';</script>";
    } else {
        echo "<script>alert('{$result['message']}'); location.href = 'documents.php';</script>";
    }
}

// Get all user's documents
$documents = [];
$cases = getCases(['client_id' => $user['id']]);
foreach ($cases as $case) {
    $caseDocs = getCaseDocuments($case['id']);
    foreach ($caseDocs as $doc) {
        $doc['case_number'] = $case['case_number'];
        $doc['case_title'] = $case['title'];
        $documents[] = $doc;
    }
}
?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">My Documents</h4>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDocModal">Upload Document</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <?php if (empty($documents)): ?>
                            <div class="text-center py-5">
                                <iconify-icon icon="solar:document-text-outline" class="fs-48 text-muted"></iconify-icon>
                                <p class="text-muted mt-3">No documents uploaded yet.</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocModal">Upload Your First Document</button>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-centered mb-0">
                                    <thead>
                                        <tr>
                                            <th>Document Type</th>
                                            <th>File Name</th>
                                            <th>Case</th>
                                            <th>Status</th>
                                            <th>Uploaded</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($documents as $doc): ?>
                                        <tr>
                                            <td><?= ucfirst(str_replace('_', ' ', $doc['document_type'])) ?></td>
                                            <td><?= htmlspecialchars($doc['file_name']) ?></td>
                                            <td>
                                                <?= htmlspecialchars($doc['case_number']) ?>
                                                <br><small class="text-muted"><?= htmlspecialchars(substr($doc['case_title'], 0, 30)) ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $doc['status'] == 'verified' ? 'success' : ($doc['status'] == 'rejected' ? 'danger' : 'warning') ?>">
                                                    <?= ucfirst($doc['status']) ?>
                                                </span>
                                            </td>
                                            <td><?= date('d M Y', strtotime($doc['created_at'])) ?></td>
                                            <td>
                                                <a href="../<?= $doc['file_path'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
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

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Case</label>
                        <select name="case_id" class="form-select" required>
                            <?php foreach ($cases as $case): ?>
                                <option value="<?= $case['id'] ?>"><?= htmlspecialchars($case['case_number']) ?> - <?= htmlspecialchars(substr($case['title'], 0, 30)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
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

<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>

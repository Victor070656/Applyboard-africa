<?php
include "../config/config.php";
include "partials/header.php";
include "partials/sidebar.php";

// Handle Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    $status = 'new';
    if ($action == 'contacted') $status = 'contacted';
    if ($action == 'resolved') $status = 'resolved';
    
    $sql = "UPDATE `inquiries` SET `status` = '$status' WHERE `id` = $id";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Status updated to $status'); location.href = 'inquiries.php';</script>";
    } else {
        echo "<script>alert('Error updating status');</script>";
    }
}
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="mb-0">Manage Inquiries</h4>
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
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Message</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Join with agents table to show agent name
                                    $sql = "SELECT i.*, a.fullname as agent_name FROM `inquiries` i LEFT JOIN `agents` a ON i.agent_id = a.id ORDER BY i.created_at DESC";
                                    $result = mysqli_query($conn, $sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $statusBadge = $row['status'] == 'resolved' ? 'bg-success' : ($row['status'] == 'new' ? 'bg-danger' : 'bg-warning');
                                        ?>
                                        <tr>
                                            <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                                            <td><?= htmlspecialchars($row['name']) ?></td>
                                            <td><?= htmlspecialchars($row['email']) ?></td>
                                            <td><?= htmlspecialchars($row['phone']) ?></td>
                                            <td><?= $row['agent_name'] ? htmlspecialchars($row['agent_name']) : '<span class="text-muted">Direct</span>' ?></td>
                                            <td><span class="badge <?= $statusBadge ?>"><?= ucfirst($row['status']) ?></span></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#msgModal<?= $row['id'] ?>">View</button>
                                                
                                                <!-- Modal -->
                                                <div class="modal fade" id="msgModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Message from <?= htmlspecialchars($row['name']) ?></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">Action</button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="?action=contacted&id=<?= $row['id'] ?>">Mark as Contacted</a></li>
                                                        <li><a class="dropdown-item" href="?action=resolved&id=<?= $row['id'] ?>">Mark as Resolved</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
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

<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>

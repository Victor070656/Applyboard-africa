<?php
include "../config/config.php";
include "partials/header.php";
include "partials/sidebar.php";

// Handle Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    $status = ($action == 'approve') ? 'verified' : 'rejected';
    
    $sql = "UPDATE `agents` SET `status` = '$status' WHERE `id` = $id";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Agent status updated to $status'); location.href = 'agents.php';</script>";
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
                    <h4 class="mb-0">Manage Agents</h4>
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
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Document</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM `agents` ORDER BY `created_at` DESC";
                                    $result = mysqli_query($conn, $sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $statusBadge = $row['status'] == 'verified' ? 'bg-success' : ($row['status'] == 'rejected' ? 'bg-danger' : 'bg-warning');
                                        ?>
                                        <tr>
                                            <td><?= $row['agent_code'] ?></td>
                                            <td><?= $row['fullname'] ?></td>
                                            <td><?= $row['email'] ?></td>
                                            <td><?= $row['phone'] ?></td>
                                            <td><span class="badge <?= $statusBadge ?>"><?= strtoupper($row['status']) ?></span></td>
                                            <td>
                                                <?php if($row['documents']): ?>
                                                    <a href="../uploads/<?= $row['documents'] ?>" target="_blank" class="btn btn-sm btn-info">View Doc</a>
                                                <?php else: ?>
                                                    <span class="text-muted">No Doc</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($row['status'] == 'pending'): ?>
                                                    <a href="?action=approve&id=<?= $row['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Approve this agent?')">Approve</a>
                                                    <a href="?action=reject&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Reject this agent?')">Reject</a>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
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

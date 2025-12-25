<?php
include "../config/config.php";
// include "../config/auth_helper.php";

if (!isLoggedIn('agent')) {
    header("Location: login.php");
    exit;
}

$agent_id = auth('agent')['id'];

// Handle Actions (Agents can mark as contacted)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    $status = 'new';
    if ($action == 'contacted') $status = 'contacted';
    
    // Ensure inquiry belongs to agent
    $sql = "UPDATE `inquiries` SET `status` = '$status' WHERE `id` = $id AND `agent_id` = '$agent_id'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Status updated to $status'); location.href = 'inquiries.php';</script>";
    } else {
        echo "<script>alert('Error updating status');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>ApplyBoard Africa Ltd Agent || Inquiries</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/favicon.png">
    <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/config.js"></script>
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
                                   <h4 class="mb-0">My Inquiries</h4>
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
                                                            <th>Status</th>
                                                            <th>Message</th>
                                                            <th>Action</th>
                                                       </tr>
                                                  </thead>
                                                  <tbody>
                                                       <?php
                                                       $sql = "SELECT * FROM `inquiries` WHERE `agent_id` = '$agent_id' ORDER BY `created_at` DESC";
                                                       $result = mysqli_query($conn, $sql);
                                                       if (mysqli_num_rows($result) > 0) {
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                 $statusBadge = $row['status'] == 'resolved' ? 'bg-success' : ($row['status'] == 'new' ? 'bg-danger' : 'bg-warning');
                                                       ?>
                                                                 <tr>
                                                                      <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                                                                      <td><?= htmlspecialchars($row['name']) ?></td>
                                                                      <td><?= htmlspecialchars($row['email']) ?></td>
                                                                      <td><?= htmlspecialchars($row['phone']) ?></td>
                                                                      <td><span class="badge <?= $statusBadge ?>"><?= ucfirst($row['status']) ?></span></td>
                                                                      <td>
                                                                           <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#msgModal<?= $row['id'] ?>">View</button>
                                                                           
                                                                           <!-- Modal -->
                                                                           <div class="modal fade" id="msgModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                                                                                <div class="modal-dialog">
                                                                                     <div class="modal-content">
                                                                                          <div class="modal-header">
                                                                                               <h5 class="modal-title">Message details</h5>
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
                                                                           <?php if($row['status'] == 'new'): ?>
                                                                               <a href="?action=contacted&id=<?= $row['id'] ?>" class="btn btn-sm btn-info">Mark Contacted</a>
                                                                           <?php else: ?>
                                                                               <span class="text-muted">-</span>
                                                                           <?php endif; ?>
                                                                      </td>
                                                                 </tr>
                                                       <?php
                                                            }
                                                       } else {
                                                            echo "<tr><td colspan='7' class='text-center'>No inquiries found.</td></tr>";
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
     </div>

     <script src="assets/js/vendor.min.js"></script>
     <script src="assets/js/app.js"></script>
</body>
</html>

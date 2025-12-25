<?php
include "../config/config.php";
if (!isset($_SESSION['sdtravels_manager'])) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

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
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="utf-8" />
     <title>ApplyBoard Africa Ltd || Inquiries</title>
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
                              <div class="page-title-box">
                                   <h4 class="mb-0">Manage Inquiries</h4>
                                   <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">ApplyBoard Africa Ltd</a></li>
                                        <li class="breadcrumb-item active">Inquiries</li>
                                   </ol>
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
     </div>

     <script src="assets/js/vendor.min.js"></script>
     <script src="assets/js/app.js"></script>
</body>
</html>

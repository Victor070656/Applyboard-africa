<?php
include "../config/config.php";
if (!isset($_SESSION['sdtravels_manager'])) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

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
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="utf-8" />
     <title>ApplyBoard Africa Ltd || Agents</title>
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
                                   <h4 class="mb-0">Manage Agents</h4>
                                   <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">ApplyBoard Africa Ltd</a></li>
                                        <li class="breadcrumb-item active">Agents</li>
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
     </div>

     <script src="assets/js/vendor.min.js"></script>
     <script src="assets/js/app.js"></script>
</body>
</html>

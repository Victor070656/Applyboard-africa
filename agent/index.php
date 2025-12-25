<?php
include "../config/config.php";
// include "../config/auth_helper.php";

if (!isLoggedIn('agent')) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
     exit;
}

$agent_id = auth('agent')['id'];
$agent_code = auth('agent')['agent_code'];

// Queries filtered by Agent (if applicable, or just show totals if schema doesn't link yet)
// We added agent_id to users.
$getUsers = mysqli_query($conn, "SELECT * FROM `users` WHERE `agent_id` = '$agent_id'");
// Inquiries (we added inquiries table)
$getInquiries = mysqli_query($conn, "SELECT * FROM `inquiries` WHERE `agent_id` = '$agent_id'");

// For now, Hotels/Visa/Bookings might be global or not linked. 
// Just showing Inquiries and Clients is most important for Sprint 1.
$usersCount = mysqli_num_rows($getUsers);
$inquiriesCount = mysqli_num_rows($getInquiries);

?>
<!DOCTYPE html>
<html lang="en">
<head>
     <!-- Title Meta -->
     <meta charset="utf-8" />
     <title>ApplyBoard Africa Ltd Agent || Dashboard</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     
     <!-- App favicon -->
     <link rel="shortcut icon" href="../images/favicon.png">

     <!-- Vendor css -->
     <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
     <!-- Icons css -->
     <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
     <!-- App css -->
     <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />
     <!-- Theme Config js -->
     <script src="assets/js/config.js"></script>
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

          <div class="page-content">
               <div class="container-fluid">

                    <!-- ========== Page Title Start ========== -->
                    <div class="row">
                         <div class="col-12">
                              <div class="page-title-box">
                                   <div class="d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0">Agent Dashboard</h4>
                                        <div>
                                             <span class="badge bg-primary fs-14">Code: <?= $agent_code ?></span>
                                             <span class="badge bg-success fs-14">Status: <?= auth('agent')['status'] ?></span>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
                    <!-- ========== Page Title End ========== -->

                    <div class="row">
                         <!-- Card 1 -->
                         <div class="col-md-6 col-xl-3">
                              <div class="card rounded-4">
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-6">
                                                  <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                                                       <iconify-icon icon="solar:users-group-two-rounded-outline" class="fs-32 text-primary avatar-title"></iconify-icon>
                                                  </div>
                                             </div>
                                             <div class="col-6 text-end">
                                                  <p class="text-muted mb-0 text-truncate">My Clients</p>
                                                  <h3 class="text-dark mt-2 mb-0"><?= $usersCount; ?></h3>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- Card 2 -->
                         <div class="col-md-6 col-xl-3">
                              <div class="card rounded-4">
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-6">
                                                  <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                                                       <iconify-icon icon="solar:question-circle-outline" class="fs-32 text-primary avatar-title"></iconify-icon>
                                                  </div>
                                             </div>
                                             <div class="col-6 text-end">
                                                  <p class="text-muted mb-0 text-truncate">Inquiries</p>
                                                  <h3 class="text-dark mt-2 mb-0"><?= $inquiriesCount; ?></h3>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         
                         <!-- Card 3 -->
                         <div class="col-md-6 col-xl-6">
                              <div class="card rounded-4">
                                   <div class="card-body">
                                        <h5 class="card-title">My Referral Link</h5>
                                        <div class="input-group">
                                             <input type="text" class="form-control" value="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/smile-dove/register.php?ref=' . $agent_code ?>" readonly id="refLink">
                                             <button class="btn btn-outline-primary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('refLink').value); alert('Copied!');">Copy</button>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="row">
                         <div class="col-12">
                              <div class="card rounded-4">
                                   <div class="card-body">
                                        <h4 class="card-title">Recent Inquiries</h4>
                                   </div> 
                                   <div class="table-responsive">
                                        <table class="table table-striped table-centered w-100">
                                             <thead>
                                                  <tr>
                                                       <th>Name</th>
                                                       <th>Email</th>
                                                       <th>Date</th>
                                                       <th>Status</th>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <?php
                                                  if ($inquiriesCount > 0) {
                                                       while ($row = mysqli_fetch_assoc($getInquiries)) {
                                                            ?>
                                                            <tr>
                                                                 <td><?= $row['name'] ?></td>
                                                                 <td><?= $row['email'] ?></td>
                                                                 <td><?= date("d M Y", strtotime($row['created_at'])) ?></td>
                                                                 <td><span class="badge bg-secondary"><?= $row['status'] ?></span></td>
                                                            </tr>
                                                            <?php
                                                       }
                                                  } else {
                                                       echo "<tr><td colspan='4' class='text-center'>No inquiries yet.</td></tr>";
                                                  }
                                                  ?>
                                             </tbody>
                                        </table>
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
     <script src="assets/js/pages/dashboard.js"></script>
</body>
</html>
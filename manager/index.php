<?php
include "../config/config.php";
// session_start();
if (!isset($_SESSION['sdtravels_manager'])) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

// New Stats for Plan Alignment
$getAgents = mysqli_query($conn, "SELECT * FROM `agents`");
$getPendingAgents = mysqli_query($conn, "SELECT * FROM `agents` WHERE `status` = 'pending'");
$getInquiries = mysqli_query($conn, "SELECT * FROM `inquiries`");
$getNewInquiries = mysqli_query($conn, "SELECT * FROM `inquiries` WHERE `status` = 'new'");

?>
<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from stackbros.in/ApplyBoard Africa Ltd/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 19 Mar 2025 10:04:58 GMT -->

<head>
     <!-- Title Meta -->
     <meta charset="utf-8" />
     <title>ApplyBoard Africa Ltd || Dashboard</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <meta name="robots" content="index, follow" />
     <meta name="theme-color" content="#ffffff">

     <!-- App favicon -->
     <link rel="shortcut icon" href="../images/favicon.png">

     <!-- Google Font Family link -->
     <link rel="preconnect" href="https://fonts.googleapis.com/index.html">
     <link rel="preconnect" href="https://fonts.gstatic.com/index.html" crossorigin>
     <link href="https://fonts.googleapis.com/css2c4ad.css?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">

     <!-- Vendor css -->
     <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />

     <!-- Icons css -->
     <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

     <!-- App css -->
     <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />

     <!-- Theme Config js -->
     <script src="assets/js/config.js"></script>
     <!-- Iconify -->
     <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
     <link rel="stylesheet" href="assets/vendor/jsvectormap/css/jsvectormap.min.css">
     <link rel="stylesheet" href="assets/css/datatables.min.css">
</head>

<body>

     <!-- START Wrapper -->
     <div class="app-wrapper">

          <!-- Topbar Start -->
          <?php
          include "partials/header.php";
          ?>
          <!-- Topbar End -->

          <!-- App Menu Start -->
          <?php
          include "partials/sidebar.php";
          ?>
          <!-- App Menu End -->

          <!-- ==================================================== -->
          <!-- Start right Content here -->
          <!-- ==================================================== -->
          <div class="page-content">

               <!-- Start Container Fluid -->
               <div class="container-fluid">

                    <!-- ========== Page Title Start ========== -->
                    <div class="row">
                         <div class="col-12">
                              <div class="page-title-box">
                                   <h4 class="mb-0">Dashboard</h4>
                                   <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">ApplyBoard Africa Ltd</a></li>
                                        <li class="breadcrumb-item active">Dashboard</li>
                                   </ol>
                              </div>
                         </div>
                    </div>
                    <!-- ========== Page Title End ========== -->

                    <div class="row">

                         <!-- Card Agents -->
                         <div class="col-md-6 col-xl-3">
                              <div class="card rounded-4">
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-6">
                                                  <div class="avatar-md bg-warning bg-opacity-10 rounded-circle">
                                                       <iconify-icon icon="solar:users-group-two-rounded-outline"
                                                            class="fs-32 text-warning avatar-title"></iconify-icon>
                                                  </div>
                                             </div>
                                             <div class="col-6 text-end">
                                                  <p class="text-muted mb-0 text-truncate">Agents</p>
                                                  <h3 class="text-dark mt-2 mb-0"><?= $getAgents->num_rows; ?></h3>
                                                  <small class="text-danger"><?= $getPendingAgents->num_rows ?> Pending</small>
                                             </div>
                                        </div>
                                   </div>

                              </div>
                         </div>

                         <!-- Card Inquiries -->
                         <div class="col-md-6 col-xl-3">
                              <div class="card rounded-4">
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-6">
                                                  <div class="avatar-md bg-info bg-opacity-10 rounded-circle">
                                                       <iconify-icon icon="solar:chat-round-line-broken"
                                                            class="fs-32 text-info avatar-title"></iconify-icon>
                                                  </div>
                                             </div>
                                             <div class="col-6 text-end">
                                                  <p class="text-muted mb-0 text-truncate">Inquiries</p>
                                                  <h3 class="text-dark mt-2 mb-0"><?= $getInquiries->num_rows; ?></h3>
                                                  <small class="text-danger"><?= $getNewInquiries->num_rows ?> New</small>
                                             </div>
                                        </div>
                                   </div>

                              </div>
                         </div>
                    </div>
               </div>
               <!-- End Container Fluid -->

               <!-- Footer Start -->
               <footer class="footer card mb-0 rounded-0 justify-content-center align-items-center">
                    <div class="container-fluid">
                         <div class="row">
                              <div class="col-12 text-center">
                                   <p class="mb-0">
                                        <script>
                                             document.write(new Date().getFullYear())
                                        </script> &copy; ApplyBoard Africa Ltd.</a>
                                   </p>
                              </div>
                         </div>
                    </div>
               </footer>
               <!-- Footer End -->

          </div>
          <!-- ==================================================== -->
          <!-- End Page Content -->
          <!-- ==================================================== -->

     </div>
     <!-- END Wrapper -->

     <!-- Vendor Javascript -->
     <script src="assets/js/vendor.min.js"></script>

     <!-- App Javascript -->
     <script src="assets/js/app.js"></script>

     <!-- Vector Map Js -->
     <script src="assets/vendor/jsvectormap/js/jsvectormap.min.js"></script>
     <script src="assets/vendor/jsvectormap/maps/world-merc.js"></script>
     <script src="assets/vendor/jsvectormap/maps/world.js"></script>

</body>


<!-- Mirrored from stackbros.in/ApplyBoard Africa Ltd/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 19 Mar 2025 10:04:58 GMT -->

</html>
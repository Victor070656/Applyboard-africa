<?php
include "../config/config.php";
// include "../config/auth_helper.php";

if (!isLoggedIn('agent')) {
    header("Location: login.php");
    exit;
}

$agent_id = auth('agent')['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>ApplyBoard Africa Ltd Agent || My Clients</title>
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
                                   <h4 class="mb-0">My Clients</h4>
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
                                                            <th>Date Joined</th>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <!-- <th>Phone</th> -->
                                                       </tr>
                                                  </thead>
                                                  <tbody>
                                                       <?php
                                                       $sql = "SELECT * FROM `users` WHERE `agent_id` = '$agent_id' ORDER BY `created_at` DESC";
                                                       $result = mysqli_query($conn, $sql);
                                                       if (mysqli_num_rows($result) > 0) {
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                       ?>
                                                                 <tr>
                                                                      <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                                                                      <td><?= htmlspecialchars($row['fullname']) ?></td>
                                                                      <td><?= htmlspecialchars($row['email']) ?></td>
                                                                      <!-- <td><?= htmlspecialchars($row['phone'] ?? '-') ?></td> -->
                                                                 </tr>
                                                       <?php
                                                            }
                                                       } else {
                                                            echo "<tr><td colspan='3' class='text-center'>No clients found. Share your referral link!</td></tr>";
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

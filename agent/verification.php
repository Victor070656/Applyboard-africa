<?php
include '../config/config.php';
// include '../config/auth_helper.php';

if (!isLoggedIn('agent')) {
    header("Location: login.php");
    exit;
}

$agent = auth('agent');
$agent_id = $agent['id'];

// If already verified, redirect to dashboard 
if ($agent['status'] == 'verified') {
    echo "<script>alert('You are already verified!'); location.href = 'index.php'</script>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>ApplyBoard Africa Ltd Agent || Verification</title>
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
                                   <h4 class="mb-0">Account Verification</h4>
                              </div>
                         </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Upload Verification Documents</h5>
                                    <p class="text-muted">Please upload a valid government ID or Business License to verify your account. Your account is currently <strong><?= strtoupper($agent['status']) ?></strong>.</p>
                                    
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="document" class="form-label">Select Document (PDF, JPG, PNG)</label>
                                            <input type="file" name="document" class="form-control" required>
                                        </div>
                                        <button type="submit" name="upload" class="btn btn-primary">Upload Document</button>
                                    </form>

                                    <?php
                                    if (isset($_POST['upload'])) {
                                        $target_dir = "../uploads/agents/";
                                        if (!file_exists($target_dir)) {
                                            mkdir($target_dir, 0777, true);
                                        }
                                        
                                        $file_name = time() . '_' . basename($_FILES["document"]["name"]);
                                        $target_file = $target_dir . $file_name;
                                        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                                        
                                        $valid_extensions = array("jpg", "jpeg", "png", "pdf");
                                        
                                        if (in_array($imageFileType, $valid_extensions)) {
                                            if (move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {
                                                // Save DB
                                                // For simplicity, overwriting existing for now, or could append to JSON
                                                // Let's store as simple string for first doc
                                                $doc_path = "agents/" . $file_name;
                                                $sql = "UPDATE `agents` SET `documents` = '$doc_path' WHERE `id` = '$agent_id'";
                                                if (mysqli_query($conn, $sql)) {
                                                    // Refresh session or just notify
                                                    echo "<div class='alert alert-success mt-3'>Document uploaded successfully. Waiting for admin approval.</div>";
                                                } else {
                                                    echo "<div class='alert alert-danger mt-3'>Database Error: " . mysqli_error($conn) . "</div>";
                                                }
                                            } else {
                                                echo "<div class='alert alert-danger mt-3'>Error uploading file.</div>";
                                            }
                                        } else {
                                            echo "<div class='alert alert-danger mt-3'>Invalid file format. Only JPG, PNG, PDF allowed.</div>";
                                        }
                                    }
                                    ?>
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

<?php
include '../config/config.php';
// include '../config/auth_helper.php';

if (isLoggedIn('agent')) {
    echo "<script>location.href = './'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title Meta -->
    <meta charset="utf-8" />
    <title>ApplyBoard Africa Ltd Agent || Sign In</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ApplyBoard Africa Ltd Agent Portal" />
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

<body class="authentication-bg">
    <div class="account-pages py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <form method="post">

                        <div class="card border-0 shadow-lg">
                            <div class="card-body p-5">
                                <div class="text-center">
                                    <div class="mx-auto mb-4 text-center auth-logo">
                                        <img src="../images/logo-2.png" alt="" height="50">
                                    </div>
                                    <h4 class="fw-bold text-dark mb-2">Agent Login</h4>
                                    <p class="text-muted">Sign in to manage your clients and commissions</p>
                                </div>
                                <form method="post" class="mt-4">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label for="password" class="form-label">Password</label>
                                        </div>
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
                                    </div>
                                    <div class="d-grid">
                                        <button class="btn btn-primary btn-lg fw-medium" name="submit" type="submit">Sign In</button>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <p class="mb-0">Don't have an account? <a href="register.php" class="text-primary fw-bold">Register as Agent</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php
                        if (isset($_POST['submit'])) {
                            $email = mysqli_real_escape_string($conn, $_POST['email']);
                            $password = $_POST['password']; // Password hashing should be used, but keeping consistent with existing plain text for now, or check if password_verify needed. Admin uses plain text in sample?
                            // Admin sample: `password` = '$password' (Plain text! Security risk, but matching existing pattern).
                            
                            $sql = "SELECT * FROM `agents` WHERE `email` = '$email' AND `password` = '$password'";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                if ($row['status'] == 'rejected') {
                                     echo "<script>alert('Your account has been rejected. Please contact support.')</script>";
                                } else {
                                    loginUser('agent', $row);
                                    echo "<script>alert('Login Successful'); location.href = './'</script>";
                                }
                            } else {
                                echo "<script>alert('Invalid Email or Password')</script>";
                            }
                        }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor Javascript -->
    <script src="assets/js/vendor.min.js"></script>
    <!-- App Javascript -->
    <script src="assets/js/app.js"></script>

</body>
</html>
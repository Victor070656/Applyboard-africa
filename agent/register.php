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
    <meta charset="utf-8" />
    <title>ApplyBoard Africa Ltd Agent || Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="../images/favicon.png">
    <!-- Vendor css -->
    <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/config.js"></script>
</head>

<body class="authentication-bg">
    <div class="account-pages py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <form method="post" enctype="multipart/form-data">
                        <div class="card border-0 shadow-lg">
                            <div class="card-body p-5">
                                <div class="text-center">
                                    <div class="mx-auto mb-4 text-center auth-logo">
                                        <img src="../images/logo-2.png" alt="" height="50">
                                    </div>
                                    <h4 class="fw-bold text-dark mb-2">Agent Registration</h4>
                                    <p class="text-muted">Join our partner network</p>
                                </div>
                                <div class="mt-4">
                                    <div class="mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" name="fullname" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" name="phone" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password" required>
                                    </div>
                                    <div class="d-grid">
                                        <button class="btn btn-primary btn-lg fw-medium" name="register"
                                            type="submit">Register</button>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <p class="mb-0">Already have an account? <a href="login.php"
                                                class="text-primary fw-bold">Login</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        if (isset($_POST['register'])) {
                            $fullname = mysqli_real_escape_string($conn, trim($_POST['fullname']));
                            $email = mysqli_real_escape_string($conn, trim($_POST['email']));
                            $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
                            $password = $_POST['password'];

                            // Validation
                            if (empty($fullname) || empty($email) || empty($password)) {
                                echo "<script>alert('Please fill in all required fields')</script>";
                            } elseif (strlen($password) < 6) {
                                echo "<script>alert('Password must be at least 6 characters')</script>";
                            } else {
                                // Generate Agent Code (e.g., AGT-RANDOM)
                                $agent_code = 'AGT-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));

                                // Check email
                                $check = mysqli_query($conn, "SELECT * FROM `agents` WHERE `email` = '$email'");
                                if (mysqli_num_rows($check) > 0) {
                                    echo "<script>alert('Email already registered')</script>";
                                } else {
                                    // Hash the password
                                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                                    $sql = "INSERT INTO `agents` (`agent_code`, `fullname`, `email`, `password`, `phone`, `status`) VALUES ('$agent_code', '$fullname', '$email', '$hashed_password', '$phone', 'pending')";
                                    if (mysqli_query($conn, $sql)) {
                                        echo "<script>alert('Registration Successful! Your agent code is: $agent_code. Please login.'); location.href = 'login.php'</script>";
                                    } else {
                                        echo "<script>alert('Error: " . mysqli_error($conn) . "')</script>";
                                    }
                                }
                            }
                        }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>

</html>
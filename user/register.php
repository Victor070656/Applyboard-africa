<?php
include '../config/config.php';
// session_start();

?>
<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from stackbros.in/ApplyBoard Africa Ltd/auth-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 19 Mar 2025 10:05:01 GMT -->

<head>
    <!-- Title Meta -->
    <meta charset="utf-8" />
    <title>ApplyBoard Africa Ltd User || Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ApplyBoard Africa Ltd: An advanced, fully responsive admin dashboard template packed with features to streamline your analytics and management needs." />
    <meta name="author" content="StackBros" />
    <meta name="keywords" content="ApplyBoard Africa Ltd, admin dashboard, responsive template, analytics, modern UI, management tools" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="robots" content="index, follow" />
    <meta name="theme-color" content="#ffffff">

    <!-- App favicon -->
    <link rel="shortcut icon" href="../images/favicon.png">

    <!-- Google Font Family link -->
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com/index.html">
    <link rel="preconnect" href="https://fonts.gstatic.com/index.html" crossorigin>
    <link href="https://fonts.googleapis.com/css2c4ad.css?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet"> -->

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

                                    </div>
                                    <h4 class="fw-bold text-dark mb-2">Create Account!</h3>
                                        <p class="text-muted">Fill in your details to continue</p>
                                </div>
                                <form method="post" action="?ref=<?= isset($_GET['ref']) ? htmlspecialchars($_GET['ref']) : '' ?>" class="mt-4">
                                    <?php if(isset($referral_agent_id) && $referral_agent_id != 'NULL'): ?>
                                        <input type="hidden" name="agent_id" value="<?= $referral_agent_id ?>">
                                        <div class="alert alert-success fs-14">Referred by Agent Code: <?= htmlspecialchars($_GET['ref']) ?></div>
                                    <?php endif; ?>
                                    <div class="mb-3">
                                        <label for="fullname" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Enter your name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email">
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label for="password" class="form-label">Password</label>
                                        </div>
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password">
                                    </div>
                                    <div class="d-grid">
                                        <button class="btn btn-dark btn-lg fw-medium" name="submit" type="submit">Sign Up</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <p class="text-center mt-4 text-white text-opacity-50">I already have an account
                            <a href="login.php" class="text-decoration-none text-white fw-bold">Sign In</a>
                        </p>
                        <?php
                        // Referral Code Logic
                        $referral_agent_id = 'NULL';
                        $ref_code = '';
                        
                        if (isset($_GET['ref'])) {
                             $ref_code = mysqli_real_escape_string($conn, $_GET['ref']);
                        } elseif (isset($_COOKIE['sdtravels_ref'])) {
                             $ref_code = mysqli_real_escape_string($conn, $_COOKIE['sdtravels_ref']);
                        }

                        if ($ref_code) {
                             $agent_check = mysqli_query($conn, "SELECT `id` FROM `agents` WHERE `agent_code` = '$ref_code'");
                             if (mysqli_num_rows($agent_check) > 0) {
                                  $agent_row = mysqli_fetch_assoc($agent_check);
                                  $referral_agent_id = $agent_row['id'];
                             }
                        }

                        if (isset($_POST['submit'])) {
                            $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
                            $email = mysqli_real_escape_string($conn, $_POST['email']);
                            $password = $_POST['password']; // Ideally hash this
                            $userid = uniqid();
                            
                            // Use hidden input if passed or session, but simple approach: use the one from URL if present during submit? 
                            // Better: keep it in action URL or hidden field.
                            // Let's use hidden field.
                            $post_agent_id = isset($_POST['agent_id']) && $_POST['agent_id'] != '' ? $_POST['agent_id'] : 'NULL';
                            
                            $sql = "INSERT INTO `users` (`userid`, `fullname`, `email`, `password`, `agent_id`) VALUES ('$userid','$fullname','$email','$password', $post_agent_id)";
                            $result = mysqli_query($conn, $sql);
                            if ($result) {
                                echo "<script>alert('Registration Successful'); location.href = 'login.php'</script>";
                            } else {
                                echo "<script>alert('Something went wrong: " . mysqli_error($conn) . "')</script>";
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


<!-- Mirrored from stackbros.in/ApplyBoard Africa Ltd/auth-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 19 Mar 2025 10:05:01 GMT -->

</html>
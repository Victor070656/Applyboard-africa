<?php
include '../config/config.php';

if (isLoggedIn('agent')) {
    header("Location: ./");
    exit;
}

$error = '';

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    // Get agent by email
    $sql = "SELECT * FROM `agents` WHERE `email` = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Check password
        if ($password === $row['password']) {
            if ($row['status'] == 'rejected') {
                $error = "Your account has been rejected. Please contact support.";
            } else {
                loginUser('agent', $row);
                echo "<script>location.href = './'</script>";
                exit;
            }
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>ApplyBoard Africa Ltd Agent || Sign In</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="../images/favicon.png">
    <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/config.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
</head>

<body class="authentication-bg">
    <div class="account-pages py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            <div class="text-center">
                                <div class="mx-auto mb-4 text-center auth-logo">
                                    <img src="../images/logo-2.png" alt="Logo" height="50">
                                </div>
                                <h4 class="fw-bold text-dark mb-2">Agent Login</h4>
                                <p class="text-muted">Sign in to manage your clients and commissions</p>
                            </div>

                            <?php if ($error): ?>
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <iconify-icon icon="solar:danger-circle-outline"></iconify-icon>
                                    <?= htmlspecialchars($error) ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <form method="post" class="mt-4">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="Enter your email" required
                                        value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Enter your password" required>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-primary btn-lg fw-medium" name="submit" type="submit">
                                        <iconify-icon icon="solar:login-outline" class="me-1"></iconify-icon> Sign In
                                    </button>
                                </div>
                            </form>

                            <div class="text-center mt-4">
                                <p class="text-muted mb-0">Don't have an account?
                                    <a href="register.php" class="text-primary fw-semibold">Register as Agent</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <p class="text-white-50 mb-0">
                            <a href="../index.php" class="text-white">
                                <iconify-icon icon="solar:arrow-left-outline"></iconify-icon> Back to Home
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>

</html>
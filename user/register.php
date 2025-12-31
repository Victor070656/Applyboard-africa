<?php
include '../config/config.php';

// Redirect if already logged in
if (isLoggedIn('user')) {
    header("Location: ./");
    exit;
}

// Referral Code Logic - MUST be before the form
$referral_agent_id = null;
$ref_code = '';
$agent_name = '';

// Check URL parameter first, then cookie
if (isset($_GET['ref']) && !empty($_GET['ref'])) {
    $ref_code = mysqli_real_escape_string($conn, $_GET['ref']);
    // Store in cookie for persistence
    setcookie("sdtravels_ref", $ref_code, time() + (86400 * 30), "/");
} elseif (isset($_COOKIE['sdtravels_ref']) && !empty($_COOKIE['sdtravels_ref'])) {
    $ref_code = mysqli_real_escape_string($conn, $_COOKIE['sdtravels_ref']);
}

// Validate agent code and get agent info
if ($ref_code) {
    $agent_check = mysqli_query($conn, "SELECT `id`, `fullname`, `agent_code` FROM `agents` WHERE `agent_code` = '$ref_code' AND `status` = 'verified'");
    if ($agent_check && mysqli_num_rows($agent_check) > 0) {
        $agent_row = mysqli_fetch_assoc($agent_check);
        $referral_agent_id = $agent_row['id'];
        $agent_name = $agent_row['fullname'];
    }
}

// Handle form submission
$error = '';
$success = '';

if (isset($_POST['submit'])) {
    $fullname = mysqli_real_escape_string($conn, trim($_POST['fullname']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Get agent_id from hidden field (which was set from $referral_agent_id)
    $post_agent_id = isset($_POST['agent_id']) && $_POST['agent_id'] != '' ? intval($_POST['agent_id']) : null;

    // Validation
    if (empty($fullname) || empty($email) || empty($password)) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $check = mysqli_query($conn, "SELECT * FROM `users` WHERE `email` = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Email already registered. Please login or use a different email.";
        } else {
            $userid = uniqid('USR');

            // Build the insert query
            if ($post_agent_id) {
                $sql = "INSERT INTO `users` (`userid`, `fullname`, `email`, `password`, `agent_id`) 
                        VALUES ('$userid', '$fullname', '$email', '$password', $post_agent_id)";
            } else {
                $sql = "INSERT INTO `users` (`userid`, `fullname`, `email`, `password`) 
                        VALUES ('$userid', '$fullname', '$email', '$password')";
            }

            $result = mysqli_query($conn, $sql);
            if ($result) {
                // Update agent's referral count if there was a referral
                if ($post_agent_id) {
                    mysqli_query($conn, "UPDATE `agents` SET `referral_count` = `referral_count` + 1 WHERE `id` = $post_agent_id");

                    // Log the referral activity
                    $new_user_id = mysqli_insert_id($conn);
                    mysqli_query($conn, "INSERT INTO `activity_logs` (`user_id`, `user_type`, `action`, `entity_type`, `entity_id`, `description`) 
                                        VALUES ($post_agent_id, 'agent', 'client_referred', 'user', $new_user_id, 'New client registered via referral link')");

                    // Create a notification for the agent
                    mysqli_query($conn, "INSERT INTO `notifications` (`user_id`, `user_type`, `type`, `title`, `message`) 
                                        VALUES ($post_agent_id, 'agent', 'client_registered', 'New Client Registered', 'A new client ($fullname) has registered using your referral link.')");
                }

                // Clear the referral cookie after successful registration
                setcookie("sdtravels_ref", "", time() - 3600, "/");

                echo "<script>alert('Registration Successful! Please login to continue.'); location.href = 'login.php'</script>";
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>ApplyBoard Africa Ltd || Sign Up</title>
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
                                <h4 class="fw-bold text-dark mb-2">Create Account</h4>
                                <p class="text-muted">Fill in your details to get started</p>
                            </div>

                            <?php if ($referral_agent_id): ?>
                                <div class="alert alert-success d-flex align-items-center mb-3">
                                    <iconify-icon icon="solar:user-check-rounded-outline" class="fs-20 me-2"></iconify-icon>
                                    <div>
                                        <strong>Referred by:</strong> <?= htmlspecialchars($agent_name) ?>
                                        <br><small class="text-muted">Code: <?= htmlspecialchars($ref_code) ?></small>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($error): ?>
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <iconify-icon icon="solar:danger-circle-outline"></iconify-icon>
                                    <?= htmlspecialchars($error) ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <form method="post" class="mt-4">
                                <?php if ($referral_agent_id): ?>
                                    <input type="hidden" name="agent_id" value="<?= $referral_agent_id ?>">
                                <?php endif; ?>

                                <div class="mb-3">
                                    <label for="fullname" class="form-label">Full Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="fullname" id="fullname"
                                        placeholder="Enter your full name" required
                                        value="<?= isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : '' ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="Enter your email" required
                                        value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span
                                            class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Enter password (min 6 characters)" required minlength="6">
                                </div>

                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password <span
                                            class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="confirm_password"
                                        id="confirm_password" placeholder="Confirm your password" required
                                        minlength="6">
                                </div>

                                <?php if (!$referral_agent_id): ?>
                                    <div class="mb-3">
                                        <label for="agent_code" class="form-label">Agent Referral Code <small
                                                class="text-muted">(Optional)</small></label>
                                        <input type="text" class="form-control" name="manual_agent_code" id="agent_code"
                                            placeholder="Enter agent code if you have one">
                                        <small class="text-muted">Leave empty if you don't have a referral code</small>
                                    </div>
                                <?php endif; ?>

                                <div class="d-grid mt-4">
                                    <button class="btn btn-primary btn-lg fw-medium" name="submit" type="submit">
                                        <iconify-icon icon="solar:user-plus-outline" class="me-1"></iconify-icon> Create
                                        Account
                                    </button>
                                </div>
                            </form>

                            <div class="text-center mt-4">
                                <p class="text-muted mb-0">Already have an account?
                                    <a href="login.php" class="text-primary fw-semibold">Sign In</a>
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

    <script>
        // Handle manual agent code entry
        document.querySelector('form').addEventListener('submit', function (e) {
            var manualCode = document.getElementById('agent_code');
            if (manualCode && manualCode.value.trim() !== '') {
                // Redirect with the agent code to validate it server-side
                var currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('ref', manualCode.value.trim());
                window.location.href = currentUrl.toString();
                e.preventDefault();
            }
        });
    </script>
</body>

</html>
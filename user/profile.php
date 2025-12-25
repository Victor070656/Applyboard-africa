<?php
include "../config/config.php";
if (!isLoggedIn('user')) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

$user = auth('user');
$userId = $user['id'];
$success = false;
$error = '';

// Get user info
$getUser = mysqli_query($conn, "SELECT * FROM `users` WHERE `id` = '$userId'");
$info = mysqli_fetch_assoc($getUser);

// Handle profile update
if (isset($_POST["update_profile"])) {
    $fullname = mysqli_real_escape_string($conn, $_POST["fullname"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $phone = mysqli_real_escape_string($conn, $_POST["phone"] ?? '');
    $address = mysqli_real_escape_string($conn, $_POST["address"] ?? '');
    $city = mysqli_real_escape_string($conn, $_POST["city"] ?? '');
    $country = mysqli_real_escape_string($conn, $_POST["country"] ?? '');

    // Check if email already exists for another user
    $emailCheck = mysqli_query($conn, "SELECT * FROM `users` WHERE `email` = '$email' AND `id` != '$userId'");
    if (mysqli_num_rows($emailCheck) > 0) {
        $error = "Email already exists. Please use a different email.";
    } else {
        $sql = "UPDATE `users` SET `fullname` = '$fullname', `email` = '$email', `phone` = '$phone', `address` = '$address', `city` = '$city', `country` = '$country' WHERE `id` = '$userId'";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            $success = "Profile updated successfully";
            // Refresh user data
            $getUser = mysqli_query($conn, "SELECT * FROM `users` WHERE `id` = '$userId'");
            $info = mysqli_fetch_assoc($getUser);
        } else {
            $error = "Failed to update profile";
        }
    }
}

// Handle password change
if (isset($_POST["change_password"])) {
    $currentPassword = $_POST["current_password"] ?? '';
    $newPassword = $_POST["new_password"] ?? '';
    $confirmPassword = $_POST["confirm_password"] ?? '';

    // Verify current password
    if ($info['password'] !== $currentPassword) {
        $error = "Current password is incorrect";
    } elseif (strlen($newPassword) < 6) {
        $error = "New password must be at least 6 characters";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "New passwords do not match";
    } else {
        $newPasswordEsc = mysqli_real_escape_string($conn, $newPassword);
        $sql = "UPDATE `users` SET `password` = '$newPasswordEsc' WHERE `id` = '$userId'";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            $success = "Password changed successfully";
        } else {
            $error = "Failed to change password";
        }
    }
}

// Calculate profile completeness
$completedFields = 0;
$totalFields = 7;
if (!empty($info['fullname'])) $completedFields++;
if (!empty($info['email'])) $completedFields++;
if (!empty($info['phone'])) $completedFields++;
if (!empty($info['address'])) $completedFields++;
if (!empty($info['city'])) $completedFields++;
if (!empty($info['country'])) $completedFields++;
if (!empty($info['date_of_birth'])) $completedFields++;
$completeness = round(($completedFields / $totalFields) * 100);
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <!-- Title Meta -->
     <meta charset="utf-8" />
     <title>ApplyBoard Africa Ltd User || My Profile</title>
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

     <style>
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0F4C75, #3282B8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
            font-weight: bold;
            margin: 0 auto;
        }
        .completeness-meter {
            height: 8px;
            border-radius: 4px;
            background: #e9ecef;
            overflow: hidden;
        }
        .completeness-bar {
            height: 100%;
            border-radius: 4px;
            background: linear-gradient(90deg, #0F4C75, #3282B8);
            transition: width 0.5s ease;
        }
     </style>
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

          <!-- Start right Content here -->
          <div class="page-content">
               <!-- Start Container Fluid -->
               <div class="container-fluid">

                    <!-- ========== Page Title Start ========== -->
                    <div class="row">
                         <div class="col-12">
                              <div class="page-title-box">
                                   <h4 class="mb-0">My Profile</h4>
                                   <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Profile</li>
                                   </ol>
                              </div>
                         </div>
                    </div>
                    <!-- ========== Page Title End ========== -->

                    <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <iconify-icon icon="solar:check-circle-outline"></iconify-icon> <?= $success ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <iconify-icon icon="solar:danger-circle-outline"></iconify-icon> <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <div class="row">
                        <!-- Profile Overview -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="profile-avatar mb-3">
                                        <?= strtoupper(substr($info['fullname'] ?? 'U', 0, 2)) ?>
                                    </div>
                                    <h5 class="mb-1"><?= htmlspecialchars($info['fullname'] ?? 'User') ?></h5>
                                    <p class="text-muted mb-3"><?= htmlspecialchars($info['email'] ?? '') ?></p>
                                    <span class="badge bg-primary">Student</span>

                                    <hr class="my-3">

                                    <div class="text-start">
                                        <h6 class="mb-2">Profile Completeness</h6>
                                        <div class="completeness-meter mb-2">
                                            <div class="completeness-bar" style="width: <?= $completeness ?>%"></div>
                                        </div>
                                        <small class="text-muted"><?= $completeness ?>% Complete</small>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="mb-0">Account Info</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between py-2 border-bottom">
                                        <span class="text-muted">Member Since</span>
                                        <span><?= date('M Y', strtotime($info['created_at'] ?? 'now')) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between py-2 border-bottom">
                                        <span class="text-muted">User ID</span>
                                        <span>#<?= str_pad($info['id'] ?? '', 6, '0', STR_PAD_LEFT) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between py-2">
                                        <span class="text-muted">Account Status</span>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Profile Form -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <ul class="nav nav-tabs card-header-tabs" id="profileTab" role="tablist">
                                        <li class="nav-item">
                                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#personal-info" type="button">
                                                <iconify-icon icon="solar:user-outline"></iconify-icon> Personal Info
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#security" type="button">
                                                <iconify-icon icon="solar:lock-keyhole-outline"></iconify-icon> Security
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <div class="tab-content">
                                        <!-- Personal Info Tab -->
                                        <div class="tab-pane fade show active" id="personal-info">
                                            <form method="post">
                                                <div class="row">
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="fullname" required class="form-control"
                                                            value="<?= htmlspecialchars($info['fullname'] ?? '') ?>" placeholder="Enter your full name">
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                                        <input type="email" name="email" required class="form-control"
                                                            value="<?= htmlspecialchars($info['email'] ?? '') ?>" placeholder="Enter your email">
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Phone Number</label>
                                                        <input type="tel" name="phone" class="form-control"
                                                            value="<?= htmlspecialchars($info['phone'] ?? '') ?>" placeholder="+234 XXX XXX XXXX">
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Country</label>
                                                        <select name="country" class="form-select">
                                                            <option value="">Select Country</option>
                                                            <option value="Nigeria" <?= ($info['country'] ?? '') === 'Nigeria' ? 'selected' : '' ?>>Nigeria</option>
                                                            <option value="Ghana" <?= ($info['country'] ?? '') === 'Ghana' ? 'selected' : '' ?>>Ghana</option>
                                                            <option value="Kenya" <?= ($info['country'] ?? '') === 'Kenya' ? 'selected' : '' ?>>Kenya</option>
                                                            <option value="Other" <?= ($info['country'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">City</label>
                                                        <input type="text" name="city" class="form-control"
                                                            value="<?= htmlspecialchars($info['city'] ?? '') ?>" placeholder="Enter your city">
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Date of Birth</label>
                                                        <input type="date" name="date_of_birth" class="form-control"
                                                            value="<?= htmlspecialchars($info['date_of_birth'] ?? '') ?>">
                                                    </div>
                                                    <div class="mb-3 col-12">
                                                        <label class="form-label">Address</label>
                                                        <textarea name="address" class="form-control" rows="2" placeholder="Enter your full address"><?= htmlspecialchars($info['address'] ?? '') ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <button type="submit" name="update_profile" class="btn btn-primary">
                                                        <iconify-icon icon="solar:check-circle-outline"></iconify-icon> Update Profile
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Security Tab -->
                                        <div class="tab-pane fade" id="security">
                                            <h6 class="mb-3">Change Password</h6>
                                            <form method="post">
                                                <div class="row">
                                                    <div class="mb-3 col-12">
                                                        <label class="form-label">Current Password <span class="text-danger">*</span></label>
                                                        <input type="password" name="current_password" class="form-control"
                                                            placeholder="Enter your current password" required>
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">New Password <span class="text-danger">*</span></label>
                                                        <input type="password" name="new_password" class="form-control"
                                                            placeholder="Enter new password (min 6 characters)" required minlength="6">
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                                        <input type="password" name="confirm_password" class="form-control"
                                                            placeholder="Confirm new password" required minlength="6">
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <button type="submit" name="change_password" class="btn btn-warning">
                                                        <iconify-icon icon="solar:lock-keyhole-unlocked-outline"></iconify-icon> Change Password
                                                    </button>
                                                </div>
                                            </form>

                                            <hr class="my-4">

                                            <h6 class="mb-3">Login Sessions</h6>
                                            <div class="alert alert-info">
                                                <iconify-icon icon="solar:info-circle-outline"></iconify-icon>
                                                Currently logged in on this device. For security, we recommend logging out after each session.
                                            </div>
                                            <a href="logout.php" class="btn btn-outline-danger">
                                                <iconify-icon icon="solar:logout-3-outline"></iconify-icon> Sign Out
                                            </a>
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
                                        <script>document.write(new Date().getFullYear())</script> &copy; ApplyBoard Africa Ltd.
                                   </p>
                              </div>
                         </div>
                    </div>
               </footer>
               <!-- Footer End -->

          </div>
          <!-- End Page Content -->

     </div>
     <!-- END Wrapper -->

     <!-- Vendor Javascript -->
     <script src="assets/js/vendor.min.js"></script>

     <!-- App Javascript -->
     <script src="assets/js/app.js"></script>

</body>

</html>

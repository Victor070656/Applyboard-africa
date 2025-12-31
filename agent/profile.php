<?php
include "../config/config.php";
include "../config/case_helper.php";

if (!isLoggedIn('agent')) {
    header("Location: login.php");
    exit;
}

$agent = auth('agent');
$agent_id = $agent['id'];
$success = false;
$error = '';

// Ensure required columns exist in agents table
mysqli_query($conn, "ALTER TABLE `agents` ADD COLUMN IF NOT EXISTS `address` varchar(255) DEFAULT NULL");
mysqli_query($conn, "ALTER TABLE `agents` ADD COLUMN IF NOT EXISTS `city` varchar(100) DEFAULT NULL");
mysqli_query($conn, "ALTER TABLE `agents` ADD COLUMN IF NOT EXISTS `country` varchar(100) DEFAULT NULL");
mysqli_query($conn, "ALTER TABLE `agents` ADD COLUMN IF NOT EXISTS `bank_name` varchar(100) DEFAULT NULL");
mysqli_query($conn, "ALTER TABLE `agents` ADD COLUMN IF NOT EXISTS `account_number` varchar(50) DEFAULT NULL");
mysqli_query($conn, "ALTER TABLE `agents` ADD COLUMN IF NOT EXISTS `account_name` varchar(150) DEFAULT NULL");
mysqli_query($conn, "ALTER TABLE `agents` ADD COLUMN IF NOT EXISTS `wallet_balance` decimal(12,2) NOT NULL DEFAULT 0.00");
mysqli_query($conn, "ALTER TABLE `agents` ADD COLUMN IF NOT EXISTS `total_earned` decimal(12,2) NOT NULL DEFAULT 0.00");

// Get full agent info
$getAgent = mysqli_query($conn, "SELECT * FROM `agents` WHERE `id` = '$agent_id'");
$info = mysqli_fetch_assoc($getAgent);

// Get performance data
updateAgentPerformance($agent_id);
$performance = getAgentPerformance($agent_id);

// Handle profile update
if (isset($_POST["update_profile"])) {
    $fullname = mysqli_real_escape_string($conn, $_POST["fullname"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $phone = mysqli_real_escape_string($conn, $_POST["phone"] ?? '');
    $address = mysqli_real_escape_string($conn, $_POST["address"] ?? '');
    $city = mysqli_real_escape_string($conn, $_POST["city"] ?? '');
    $country = mysqli_real_escape_string($conn, $_POST["country"] ?? '');
    $bank_name = mysqli_real_escape_string($conn, $_POST["bank_name"] ?? '');
    $account_number = mysqli_real_escape_string($conn, $_POST["account_number"] ?? '');
    $account_name = mysqli_real_escape_string($conn, $_POST["account_name"] ?? '');

    // Check if email already exists for another agent
    $emailCheck = mysqli_query($conn, "SELECT * FROM `agents` WHERE `email` = '$email' AND `id` != '$agent_id'");
    if (mysqli_num_rows($emailCheck) > 0) {
        $error = "Email already exists. Please use a different email.";
    } else {
        $sql = "UPDATE `agents` SET 
                `fullname` = '$fullname', 
                `email` = '$email', 
                `phone` = '$phone', 
                `address` = '$address', 
                `city` = '$city', 
                `country` = '$country',
                `bank_name` = '$bank_name',
                `account_number` = '$account_number',
                `account_name` = '$account_name'
                WHERE `id` = '$agent_id'";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            $success = "Profile updated successfully";
            // Refresh agent data
            $getAgent = mysqli_query($conn, "SELECT * FROM `agents` WHERE `id` = '$agent_id'");
            $info = mysqli_fetch_assoc($getAgent);
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
    if ($currentPassword !== $info['password']) {
        $error = "Current password is incorrect";
    } elseif (strlen($newPassword) < 6) {
        $error = "New password must be at least 6 characters";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "New passwords do not match";
    } else {
        $sql = "UPDATE `agents` SET `password` = '$newPassword' WHERE `id` = '$agent_id'";
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
$totalFields = 8;
if (!empty($info['fullname']))
    $completedFields++;
if (!empty($info['email']))
    $completedFields++;
if (!empty($info['phone']))
    $completedFields++;
if (!empty($info['address']))
    $completedFields++;
if (!empty($info['city']))
    $completedFields++;
if (!empty($info['country']))
    $completedFields++;
if (!empty($info['bank_name']))
    $completedFields++;
if (!empty($info['account_number']))
    $completedFields++;
$completeness = round(($completedFields / $totalFields) * 100);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>ApplyBoard Africa Ltd Agent || My Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/favicon.png">
    <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/config.js"></script>
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

        .stat-card {
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }
    </style>
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
                            <h4 class="mb-0">My Profile</h4>
                        </div>
                    </div>
                </div>

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
                    <!-- Profile Card -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="profile-avatar mb-3">
                                    <?= strtoupper(substr($info['fullname'], 0, 1)) ?>
                                </div>
                                <h4 class="mb-1"><?= htmlspecialchars($info['fullname']) ?></h4>
                                <p class="text-muted mb-2"><?= htmlspecialchars($info['email']) ?></p>
                                <span
                                    class="badge bg-<?= $info['status'] == 'verified' ? 'success' : ($info['status'] == 'rejected' ? 'danger' : 'warning') ?> mb-3">
                                    <?= ucfirst($info['status']) ?> Agent
                                </span>

                                <div class="border-top pt-3 mt-3">
                                    <p class="text-muted mb-1">Agent Code</p>
                                    <h5 class="text-primary"><?= $info['agent_code'] ?></h5>
                                </div>

                                <div class="border-top pt-3 mt-3">
                                    <p class="text-muted mb-2">Profile Completeness</p>
                                    <div class="completeness-meter">
                                        <div class="completeness-bar" style="width: <?= $completeness ?>%"></div>
                                    </div>
                                    <small class="text-muted"><?= $completeness ?>% Complete</small>
                                </div>
                            </div>
                        </div>

                        <!-- Performance Card -->
                        <?php if ($performance): ?>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Performance Stats</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6 mb-3">
                                            <h4 class="mb-0"><?= number_format($performance['total_referrals']) ?></h4>
                                            <small class="text-muted">Total Referrals</small>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <h4 class="mb-0"><?= number_format($performance['completed_cases']) ?></h4>
                                            <small class="text-muted">Completed Cases</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="mb-0">
                                                <?= $performance['rating_overall'] > 0 ? number_format($performance['rating_overall'], 1) : 'N/A' ?>/5
                                            </h4>
                                            <small class="text-muted">Rating</small>
                                        </div>
                                        <div class="col-6">
                                            <h4
                                                class="mb-0 text-<?= $performance['tier'] == 'gold' ? 'warning' : ($performance['tier'] == 'silver' ? 'secondary' : 'info') ?>">
                                                <?= ucfirst($performance['tier']) ?>
                                            </h4>
                                            <small class="text-muted">Tier</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Profile Form -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Edit Profile</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Full Name</label>
                                            <input type="text" class="form-control" name="fullname"
                                                value="<?= htmlspecialchars($info['fullname']) ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email"
                                                value="<?= htmlspecialchars($info['email']) ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Phone</label>
                                            <input type="tel" class="form-control" name="phone"
                                                value="<?= htmlspecialchars($info['phone'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Country</label>
                                            <input type="text" class="form-control" name="country"
                                                value="<?= htmlspecialchars($info['country'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">City</label>
                                            <input type="text" class="form-control" name="city"
                                                value="<?= htmlspecialchars($info['city'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Address</label>
                                            <input type="text" class="form-control" name="address"
                                                value="<?= htmlspecialchars($info['address'] ?? '') ?>">
                                        </div>
                                    </div>

                                    <h6 class="mt-4 mb-3 border-top pt-3">Bank Details (For Commission Payments)</h6>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Bank Name</label>
                                            <input type="text" class="form-control" name="bank_name"
                                                value="<?= htmlspecialchars($info['bank_name'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Account Number</label>
                                            <input type="text" class="form-control" name="account_number"
                                                value="<?= htmlspecialchars($info['account_number'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Account Name</label>
                                            <input type="text" class="form-control" name="account_name"
                                                value="<?= htmlspecialchars($info['account_name'] ?? '') ?>">
                                        </div>
                                    </div>

                                    <button type="submit" name="update_profile" class="btn btn-primary">Update
                                        Profile</button>
                                </form>
                            </div>
                        </div>

                        <!-- Change Password -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Change Password</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Current Password</label>
                                            <input type="password" class="form-control" name="current_password"
                                                required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">New Password</label>
                                            <input type="password" class="form-control" name="new_password" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" name="confirm_password"
                                                required>
                                        </div>
                                    </div>
                                    <button type="submit" name="change_password" class="btn btn-warning">Change
                                        Password</button>
                                </form>
                            </div>
                        </div>

                        <!-- Referral Link Card -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Your Referral Link</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Share this link with your clients. They will be automatically
                                    linked to you when they register.</p>
                                <div class="input-group">
                                    <input type="text" class="form-control"
                                        value="<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/smile-dove/user/register.php?ref=' . $info['agent_code'] ?>"
                                        readonly id="refLink">
                                    <button class="btn btn-primary" type="button"
                                        onclick="navigator.clipboard.writeText(document.getElementById('refLink').value); alert('Copied!');">
                                        <iconify-icon icon="solar:copy-outline"></iconify-icon> Copy
                                    </button>
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
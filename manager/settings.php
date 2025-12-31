<?php
include "../config/config.php";
include "../config/case_helper.php";

if (!isset($_SESSION['sdtravels_manager'])) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
     exit;
}

$success = '';
$error = '';

// Create settings table if not exists
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT,
    `setting_group` VARCHAR(50) DEFAULT 'general',
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated_by` INT(11) DEFAULT NULL,
    INDEX `idx_setting_group` (`setting_group`),
    INDEX `idx_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

// Create services table if not exists
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `services` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT,
    `base_price` DECIMAL(12,2) DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

// Note: Case type pricing must be configured by admin in Settings > Case Type Pricing
// No default values are seeded - admin must set all pricing

/**
 * Get a setting value from database
 */
function getSetting($key, $default = null)
{
     global $conn;
     $key = mysqli_real_escape_string($conn, $key);
     $result = mysqli_query($conn, "SELECT setting_value FROM settings WHERE setting_key = '$key' LIMIT 1");
     if ($result && mysqli_num_rows($result) > 0) {
          $row = mysqli_fetch_assoc($result);
          return $row['setting_value'];
     }
     return $default;
}

/**
 * Set a setting value in database
 */
function setSetting($key, $value, $group = 'general', $userId = null)
{
     global $conn;
     $key = mysqli_real_escape_string($conn, $key);
     $value = mysqli_real_escape_string($conn, $value);
     $group = mysqli_real_escape_string($conn, $group);
     $userId = $userId ? intval($userId) : 'NULL';

     $sql = "INSERT INTO settings (setting_key, setting_value, setting_group, updated_by) 
            VALUES ('$key', '$value', '$group', $userId)
            ON DUPLICATE KEY UPDATE 
            setting_value = '$value', 
            setting_group = '$group',
            updated_by = $userId,
            updated_at = NOW()";

     return mysqli_query($conn, $sql);
}

/**
 * Get all settings by group
 */
function getSettingsByGroup($group)
{
     global $conn;
     $group = mysqli_real_escape_string($conn, $group);
     $result = mysqli_query($conn, "SELECT * FROM settings WHERE setting_group = '$group'");
     $settings = [];
     if ($result) {
          while ($row = mysqli_fetch_assoc($result)) {
               $settings[$row['setting_key']] = $row['setting_value'];
          }
     }
     return $settings;
}

// Get admin user ID
$adminId = $_SESSION['sdtravels_manager']['id'] ?? 0;

// Handle settings updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

     // Update Commission Settings
     if (isset($_POST['update_commission'])) {
          $referralRate = floatval($_POST['referral_rate']);
          $caseCompletionRate = floatval($_POST['case_completion_rate']);
          $serviceRate = floatval($_POST['service_rate']);

          $saved = true;
          $saved = $saved && setSetting('commission_referral_rate', $referralRate, 'commission', $adminId);
          $saved = $saved && setSetting('commission_case_completion_rate', $caseCompletionRate, 'commission', $adminId);
          $saved = $saved && setSetting('commission_service_rate', $serviceRate, 'commission', $adminId);

          if ($saved) {
               logActivity($adminId, 'admin', 'update', 'settings', null, 'Updated commission settings');
               $success = 'Commission rates updated successfully!';
          } else {
               $error = 'Failed to save commission settings: ' . mysqli_error($conn);
          }
     }

     // Update System Settings
     if (isset($_POST['update_system'])) {
          $siteName = trim($_POST['site_name']);
          $siteEmail = trim($_POST['site_email']);
          $sitePhone = trim($_POST['site_phone']);
          $maintenanceMode = isset($_POST['maintenance_mode']) ? '1' : '0';

          $saved = true;
          $saved = $saved && setSetting('site_name', $siteName, 'system', $adminId);
          $saved = $saved && setSetting('site_email', $siteEmail, 'system', $adminId);
          $saved = $saved && setSetting('site_phone', $sitePhone, 'system', $adminId);
          $saved = $saved && setSetting('maintenance_mode', $maintenanceMode, 'system', $adminId);

          if ($saved) {
               logActivity($adminId, 'admin', 'update', 'settings', null, 'Updated system settings');
               $success = 'System settings updated successfully!';
          } else {
               $error = 'Failed to save system settings: ' . mysqli_error($conn);
          }
     }

     // Add Service
     if (isset($_POST['add_service'])) {
          $serviceName = mysqli_real_escape_string($conn, trim($_POST['service_name']));
          $serviceDesc = mysqli_real_escape_string($conn, trim($_POST['service_description']));
          $basePrice = floatval($_POST['base_price']);

          $check = mysqli_query($conn, "SELECT id FROM services WHERE name = '$serviceName'");
          if ($check && mysqli_num_rows($check) > 0) {
               $error = 'Service already exists!';
          } else {
               if (mysqli_query($conn, "INSERT INTO services (name, description, base_price) VALUES ('$serviceName', '$serviceDesc', '$basePrice')")) {
                    $newId = mysqli_insert_id($conn);
                    logActivity($adminId, 'admin', 'create', 'service', $newId, "Added service: $serviceName");
                    $success = 'Service added successfully!';
               } else {
                    $error = 'Failed to add service: ' . mysqli_error($conn);
               }
          }
     }

     // Update Service
     if (isset($_POST['update_service']) && isset($_POST['service_id'])) {
          $serviceId = intval($_POST['service_id']);
          $serviceName = mysqli_real_escape_string($conn, trim($_POST['service_name']));
          $serviceDesc = mysqli_real_escape_string($conn, trim($_POST['service_description']));
          $basePrice = floatval($_POST['base_price']);
          $isActive = isset($_POST['is_active']) ? '1' : '0';

          if (mysqli_query($conn, "UPDATE services SET name = '$serviceName', description = '$serviceDesc', base_price = '$basePrice', is_active = '$isActive' WHERE id = $serviceId")) {
               logActivity($adminId, 'admin', 'update', 'service', $serviceId, "Updated service: $serviceName");
               $success = 'Service updated successfully!';
          } else {
               $error = 'Failed to update service: ' . mysqli_error($conn);
          }
     }

     // Delete Service
     if (isset($_POST['delete_service']) && isset($_POST['service_id'])) {
          $serviceId = intval($_POST['service_id']);
          if (mysqli_query($conn, "DELETE FROM services WHERE id = $serviceId")) {
               logActivity($adminId, 'admin', 'delete', 'service', $serviceId, "Deleted service ID: $serviceId");
               $success = 'Service deleted successfully!';
          } else {
               $error = 'Failed to delete service: ' . mysqli_error($conn);
          }
     }

     // Update Case Type Pricing
     if (isset($_POST['update_case_pricing'])) {
          $caseTypes = ['study_abroad', 'visa_student', 'visa_tourist', 'visa_family', 'travel_booking', 'pilgrimage', 'other'];
          $saved = true;

          foreach ($caseTypes as $type) {
               $amount = floatval($_POST['case_amount_' . $type] ?? 0);
               $commission = floatval($_POST['case_commission_' . $type] ?? 0);
               $commissionPercent = floatval($_POST['case_commission_percent_' . $type] ?? 0);

               $saved = $saved && setSetting('case_amount_' . $type, $amount, 'case_pricing', $adminId);
               $saved = $saved && setSetting('case_commission_' . $type, $commission, 'case_pricing', $adminId);
               $saved = $saved && setSetting('case_commission_percent_' . $type, $commissionPercent, 'case_pricing', $adminId);
          }

          if ($saved) {
               logActivity($adminId, 'admin', 'update', 'settings', null, 'Updated case type pricing');
               $success = 'Case type pricing updated successfully!';
          } else {
               $error = 'Failed to save case pricing settings: ' . mysqli_error($conn);
          }
     }
}

// Load current settings from database
$commissionSettings = [
     'referral_rate' => getSetting('commission_referral_rate', 5.0),
     'case_completion_rate' => getSetting('commission_case_completion_rate', 10.0),
     'service_rate' => getSetting('commission_service_rate', 3.0)
];

$systemSettings = [
     'site_name' => getSetting('site_name', 'ApplyBoard Africa Ltd'),
     'site_email' => getSetting('site_email', 'info@applyboardafrica.com'),
     'site_phone' => getSetting('site_phone', '+250 788 000 000'),
     'maintenance_mode' => getSetting('maintenance_mode', '0')
];

// Load case pricing from database
$caseTypes = ['study_abroad', 'visa_student', 'visa_tourist', 'visa_family', 'travel_booking', 'pilgrimage', 'other'];
$casePricing = [];
foreach ($caseTypes as $type) {
     $casePricing[$type] = [
          'amount' => floatval(getSetting('case_amount_' . $type, 0)),
          'commission' => floatval(getSetting('case_commission_' . $type, 0)),
          'commission_percent' => floatval(getSetting('case_commission_percent_' . $type, 10))
     ];
}

// Get last update info
$lastUpdate = mysqli_query($conn, "SELECT updated_at, updated_by FROM settings ORDER BY updated_at DESC LIMIT 1");
$lastUpdateInfo = ['updated_at' => 'Never', 'updated_by' => 'N/A'];
if ($lastUpdate && mysqli_num_rows($lastUpdate) > 0) {
     $row = mysqli_fetch_assoc($lastUpdate);
     $lastUpdateInfo['updated_at'] = $row['updated_at'];
     if ($row['updated_by']) {
          $adminQuery = mysqli_query($conn, "SELECT email FROM admin WHERE id = " . intval($row['updated_by']));
          if ($adminQuery && mysqli_num_rows($adminQuery) > 0) {
               $lastUpdateInfo['updated_by'] = mysqli_fetch_assoc($adminQuery)['email'];
          }
     }
}

// Get services
$services = mysqli_query($conn, "SELECT * FROM services ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="utf-8" />
     <title>ApplyBoard Africa Ltd || Settings</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <link rel="shortcut icon" href="../images/favicon.png">
     <link href="https://fonts.googleapis.com/css2c4ad.css?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
          rel="stylesheet">
     <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />
     <script src="assets/js/config.js"></script>
     <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
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
                                   <h4 class="mb-0">System Settings</h4>
                                   <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">ApplyBoard Africa
                                                  Ltd</a></li>
                                        <li class="breadcrumb-item active">Settings</li>
                                   </ol>
                              </div>
                         </div>
                    </div>

                    <?php if ($success): ?>
                         <div class="alert alert-success alert-dismissible fade show" role="alert">
                              <iconify-icon icon="solar:check-circle-outline" class="me-1"></iconify-icon>
                              <?= htmlspecialchars($success) ?>
                              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                         </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                         <div class="alert alert-danger alert-dismissible fade show" role="alert">
                              <iconify-icon icon="solar:danger-triangle-outline" class="me-1"></iconify-icon>
                              <?= htmlspecialchars($error) ?>
                              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                         </div>
                    <?php endif; ?>

                    <!-- Commission Settings -->
                    <div class="card mb-3">
                         <div class="card-header">
                              <h5 class="mb-0"><iconify-icon icon="solar:wallet-money-outline"
                                        class="me-1"></iconify-icon> Commission Rates</h5>
                         </div>
                         <div class="card-body">
                              <form method="POST" class="row">
                                   <div class="col-md-4 mb-3">
                                        <label class="form-label">Referral Commission Rate (%)</label>
                                        <input type="number" step="0.01" min="0" max="100" name="referral_rate"
                                             class="form-control"
                                             value="<?= htmlspecialchars($commissionSettings['referral_rate']) ?>">
                                        <small class="text-muted">Commission paid for successful referrals</small>
                                   </div>
                                   <div class="col-md-4 mb-3">
                                        <label class="form-label">Case Completion Rate (%)</label>
                                        <input type="number" step="0.01" min="0" max="100" name="case_completion_rate"
                                             class="form-control"
                                             value="<?= htmlspecialchars($commissionSettings['case_completion_rate']) ?>">
                                        <small class="text-muted">Commission on completed cases</small>
                                   </div>
                                   <div class="col-md-4 mb-3">
                                        <label class="form-label">Service Fee Rate (%)</label>
                                        <input type="number" step="0.01" min="0" max="100" name="service_rate"
                                             class="form-control"
                                             value="<?= htmlspecialchars($commissionSettings['service_rate']) ?>">
                                        <small class="text-muted">Commission on service fees</small>
                                   </div>
                                   <div class="col-12">
                                        <button type="submit" name="update_commission" class="btn btn-primary">
                                             <iconify-icon icon="solar:disk-outline"></iconify-icon> Save Commission
                                             Rates
                                        </button>
                                   </div>
                              </form>
                         </div>
                    </div>

                    <!-- Case Type Pricing -->
                    <div class="card mb-3">
                         <div class="card-header">
                              <h5 class="mb-0"><iconify-icon icon="solar:tag-price-outline" class="me-1"></iconify-icon>
                                   Case Type Pricing</h5>
                              <small class="text-muted">Set default amounts and commission for each case type. These
                                   values will be auto-filled when cases are created.</small>
                         </div>
                         <div class="card-body">
                              <form method="POST">
                                   <div class="table-responsive">
                                        <table class="table table-bordered mb-0">
                                             <thead class="table-light">
                                                  <tr>
                                                       <th>Case Type</th>
                                                       <th>Default Amount (₦)</th>
                                                       <th>Fixed Commission (₦)</th>
                                                       <th>Commission %</th>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <?php
                                                  $caseTypeLabels = [
                                                       'study_abroad' => 'Study Abroad',
                                                       'visa_student' => 'Student Visa',
                                                       'visa_tourist' => 'Tourist Visa',
                                                       'visa_family' => 'Family Visa',
                                                       'travel_booking' => 'Travel Booking',
                                                       'pilgrimage' => 'Pilgrimage',
                                                       'other' => 'Other'
                                                  ];
                                                  foreach ($caseTypeLabels as $type => $label):
                                                       $pricing = $casePricing[$type] ?? ['amount' => 0, 'commission' => 0, 'commission_percent' => 10];
                                                       ?>
                                                       <tr>
                                                            <td>
                                                                 <strong><?= htmlspecialchars($label) ?></strong>
                                                                 <br><small
                                                                      class="text-muted"><?= htmlspecialchars($type) ?></small>
                                                            </td>
                                                            <td>
                                                                 <input type="number" step="0.01" min="0"
                                                                      name="case_amount_<?= $type ?>"
                                                                      class="form-control form-control-sm"
                                                                      value="<?= $pricing['amount'] ?>" placeholder="0.00">
                                                            </td>
                                                            <td>
                                                                 <input type="number" step="0.01" min="0"
                                                                      name="case_commission_<?= $type ?>"
                                                                      class="form-control form-control-sm"
                                                                      value="<?= $pricing['commission'] ?>"
                                                                      placeholder="0.00">
                                                                 <small class="text-muted">Fixed amount</small>
                                                            </td>
                                                            <td>
                                                                 <div class="input-group input-group-sm">
                                                                      <input type="number" step="0.01" min="0" max="100"
                                                                           name="case_commission_percent_<?= $type ?>"
                                                                           class="form-control"
                                                                           value="<?= $pricing['commission_percent'] ?>">
                                                                      <span class="input-group-text">%</span>
                                                                 </div>
                                                                 <small class="text-muted">Of amount</small>
                                                            </td>
                                                       </tr>
                                                  <?php endforeach; ?>
                                             </tbody>
                                        </table>
                                   </div>
                                   <div class="mt-3">
                                        <button type="submit" name="update_case_pricing" class="btn btn-primary">
                                             <iconify-icon icon="solar:disk-outline"></iconify-icon> Save Case Pricing
                                        </button>
                                        <small class="text-muted ms-2">Commission can be set as fixed amount OR
                                             percentage of case amount (percentage used if fixed is 0)</small>
                                   </div>
                              </form>
                         </div>
                    </div>

                    <!-- System Settings -->
                    <div class="card mb-3">
                         <div class="card-header">
                              <h5 class="mb-0"><iconify-icon icon="solar:settings-outline" class="me-1"></iconify-icon>
                                   General Settings</h5>
                         </div>
                         <div class="card-body">
                              <form method="POST">
                                   <div class="row">
                                        <div class="col-md-6 mb-3">
                                             <label class="form-label">Site Name</label>
                                             <input type="text" name="site_name" class="form-control"
                                                  value="<?= htmlspecialchars($systemSettings['site_name']) ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                             <label class="form-label">Site Email</label>
                                             <input type="email" name="site_email" class="form-control"
                                                  value="<?= htmlspecialchars($systemSettings['site_email']) ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                             <label class="form-label">Site Phone</label>
                                             <input type="text" name="site_phone" class="form-control"
                                                  value="<?= htmlspecialchars($systemSettings['site_phone']) ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                             <label class="form-label">Maintenance Mode</label>
                                             <div class="form-check form-switch mt-2">
                                                  <input class="form-check-input" type="checkbox"
                                                       name="maintenance_mode" value="1"
                                                       <?= $systemSettings['maintenance_mode'] === '1' ? 'checked' : '' ?>>
                                                  <label class="form-check-label">Enable maintenance mode (disables user
                                                       access)</label>
                                             </div>
                                        </div>
                                        <div class="col-12">
                                             <button type="submit" name="update_system" class="btn btn-primary">
                                                  <iconify-icon icon="solar:disk-outline"></iconify-icon> Save System
                                                  Settings
                                             </button>
                                        </div>
                                   </div>
                              </form>
                         </div>
                    </div>

                    <!-- Services Management -->
                    <div class="card mb-3">
                         <div class="card-header d-flex justify-content-between align-items-center">
                              <h5 class="mb-0"><iconify-icon icon="solar:box-outline" class="me-1"></iconify-icon>
                                   Service Catalog</h5>
                              <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                   data-bs-target="#addServiceModal">
                                   <iconify-icon icon="solar:add-circle-outline"></iconify-icon> Add Service
                              </button>
                         </div>
                         <div class="card-body">
                              <?php if ($services && mysqli_num_rows($services) > 0): ?>
                                   <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                             <thead>
                                                  <tr>
                                                       <th>Service Name</th>
                                                       <th>Description</th>
                                                       <th>Base Price</th>
                                                       <th>Status</th>
                                                       <th>Actions</th>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <?php while ($service = mysqli_fetch_assoc($services)): ?>
                                                       <tr>
                                                            <td><?= htmlspecialchars($service['name']) ?></td>
                                                            <td><?= htmlspecialchars($service['description'] ?: '-') ?></td>
                                                            <td>₦<?= number_format($service['base_price'], 2) ?></td>
                                                            <td>
                                                                 <span
                                                                      class="badge <?= $service['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                                                                      <?= $service['is_active'] ? 'Active' : 'Inactive' ?>
                                                                 </span>
                                                            </td>
                                                            <td>
                                                                 <button class="btn btn-sm btn-outline-primary"
                                                                      data-bs-toggle="modal"
                                                                      data-bs-target="#editServiceModal<?= $service['id'] ?>">
                                                                      <iconify-icon icon="solar:pen-outline"></iconify-icon>
                                                                 </button>
                                                                 <form method="POST" class="d-inline"
                                                                      onsubmit="return confirm('Delete this service?');">
                                                                      <input type="hidden" name="service_id"
                                                                           value="<?= $service['id'] ?>">
                                                                      <button type="submit" name="delete_service"
                                                                           class="btn btn-sm btn-outline-danger">
                                                                           <iconify-icon
                                                                                icon="solar:trash-bin-trash-outline"></iconify-icon>
                                                                      </button>
                                                                 </form>
                                                            </td>
                                                       </tr>

                                                       <!-- Edit Modal -->
                                                       <div class="modal fade" id="editServiceModal<?= $service['id'] ?>"
                                                            tabindex="-1">
                                                            <div class="modal-dialog">
                                                                 <div class="modal-content">
                                                                      <div class="modal-header">
                                                                           <h5 class="modal-title">Edit Service</h5>
                                                                           <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"></button>
                                                                      </div>
                                                                      <form method="POST">
                                                                           <div class="modal-body">
                                                                                <input type="hidden" name="service_id"
                                                                                     value="<?= $service['id'] ?>">
                                                                                <div class="mb-3">
                                                                                     <label class="form-label">Service
                                                                                          Name</label>
                                                                                     <input type="text" name="service_name"
                                                                                          class="form-control"
                                                                                          value="<?= htmlspecialchars($service['name']) ?>"
                                                                                          required>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                     <label class="form-label">Description</label>
                                                                                     <textarea name="service_description"
                                                                                          class="form-control"
                                                                                          rows="3"><?= htmlspecialchars($service['description']) ?></textarea>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                     <label class="form-label">Base Price
                                                                                          (₦)</label>
                                                                                     <input type="number" step="0.01" min="0"
                                                                                          name="base_price" class="form-control"
                                                                                          value="<?= $service['base_price'] ?>"
                                                                                          required>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                     <div class="form-check">
                                                                                          <input class="form-check-input"
                                                                                               type="checkbox" name="is_active"
                                                                                               value="1" <?= $service['is_active'] ? 'checked' : '' ?>>
                                                                                          <label
                                                                                               class="form-check-label">Active</label>
                                                                                     </div>
                                                                                </div>
                                                                           </div>
                                                                           <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary"
                                                                                     data-bs-dismiss="modal">Cancel</button>
                                                                                <button type="submit" name="update_service"
                                                                                     class="btn btn-primary">Update
                                                                                     Service</button>
                                                                           </div>
                                                                      </form>
                                                                 </div>
                                                            </div>
                                                       </div>
                                                  <?php endwhile; ?>
                                             </tbody>
                                        </table>
                                   </div>
                              <?php else: ?>
                                   <div class="text-center py-4">
                                        <iconify-icon icon="solar:document-text-outline" class="fs-48 text-muted mb-2"
                                             style="font-size: 48px;"></iconify-icon>
                                        <p class="text-muted mb-0">No services found. Add your first service to get started.
                                        </p>
                                   </div>
                              <?php endif; ?>
                         </div>
                    </div>

                    <!-- System Information -->
                    <div class="card mb-3">
                         <div class="card-header">
                              <h5 class="mb-0"><iconify-icon icon="solar:info-circle-outline"
                                        class="me-1"></iconify-icon> System Information</h5>
                         </div>
                         <div class="card-body">
                              <div class="row">
                                   <div class="col-md-3 mb-3">
                                        <label class="form-label text-muted">PHP Version</label>
                                        <div class="fw-bold"><?= PHP_VERSION ?></div>
                                   </div>
                                   <div class="col-md-3 mb-3">
                                        <label class="form-label text-muted">MySQL Version</label>
                                        <div class="fw-bold"><?= mysqli_get_server_info($conn) ?></div>
                                   </div>
                                   <div class="col-md-3 mb-3">
                                        <label class="form-label text-muted">Server Time</label>
                                        <div class="fw-bold"><?= date('Y-m-d H:i:s') ?></div>
                                   </div>
                                   <div class="col-md-3 mb-3">
                                        <label class="form-label text-muted">Storage</label>
                                        <div class="fw-bold"><span class="badge bg-success">Database</span></div>
                                   </div>
                                   <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Last Settings Update</label>
                                        <div class="fw-bold"><?= htmlspecialchars($lastUpdateInfo['updated_at']) ?>
                                        </div>
                                   </div>
                                   <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Updated By</label>
                                        <div class="fw-bold"><?= htmlspecialchars($lastUpdateInfo['updated_by']) ?>
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
                                        <script>document.write(new Date().getFullYear())</script> &copy; ApplyBoard
                                        Africa Ltd.
                                   </p>
                              </div>
                         </div>
                    </div>
               </footer>

          </div>
     </div>

     <!-- Add Service Modal -->
     <div class="modal fade" id="addServiceModal" tabindex="-1">
          <div class="modal-dialog">
               <div class="modal-content">
                    <div class="modal-header">
                         <h5 class="modal-title">Add New Service</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST">
                         <div class="modal-body">
                              <div class="mb-3">
                                   <label class="form-label">Service Name</label>
                                   <input type="text" name="service_name" class="form-control" required>
                              </div>
                              <div class="mb-3">
                                   <label class="form-label">Description</label>
                                   <textarea name="service_description" class="form-control" rows="3"></textarea>
                              </div>
                              <div class="mb-3">
                                   <label class="form-label">Base Price (₦)</label>
                                   <input type="number" step="0.01" min="0" name="base_price" class="form-control"
                                        required>
                              </div>
                         </div>
                         <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                              <button type="submit" name="add_service" class="btn btn-primary">Add Service</button>
                         </div>
                    </form>
               </div>
          </div>
     </div>

     <script src="assets/js/vendor.min.js"></script>
     <script src="assets/js/app.js"></script>

</body>

</html>
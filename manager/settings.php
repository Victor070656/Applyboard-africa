<?php
include "../config/config.php";
if (!isset($_SESSION['sdtravels_manager'])) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

$success = '';
$error = '';

// Handle settings updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_commission'])) {
        // Update commission rates
        $referralRate = floatval($_POST['referral_rate']);
        $caseCompletionRate = floatval($_POST['case_completion_rate']);
        $serviceRate = floatval($_POST['service_rate']);

        // Create or update settings in database (using a settings table or creating one)
        // For now, store in a file-based approach
        $settingsFile = '../config/settings.json';
        $settings = [];
        if (file_exists($settingsFile)) {
            $settings = json_decode(file_get_contents($settingsFile), true) ?: [];
        }

        $settings['commission'] = [
            'referral_rate' => $referralRate,
            'case_completion_rate' => $caseCompletionRate,
            'service_rate' => $serviceRate,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $_SESSION['sdtravels_manager']
        ];

        if (file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT))) {
            logActivity('manager', $_SESSION['sdtravels_manager'], 'update', 'Updated commission settings');
            $success = 'Commission rates updated successfully!';
        } else {
            $error = 'Failed to save settings.';
        }
    }

    if (isset($_POST['update_system'])) {
        $siteName = mysqli_real_escape_string($conn, $_POST['site_name']);
        $siteEmail = mysqli_real_escape_string($conn, $_POST['site_email']);
        $sitePhone = mysqli_real_escape_string($conn, $_POST['site_phone']);
        $maintenanceMode = isset($_POST['maintenance_mode']) ? '1' : '0';

        $settingsFile = '../config/settings.json';
        $settings = [];
        if (file_exists($settingsFile)) {
            $settings = json_decode(file_get_contents($settingsFile), true) ?: [];
        }

        $settings['system'] = [
            'site_name' => $siteName,
            'site_email' => $siteEmail,
            'site_phone' => $sitePhone,
            'maintenance_mode' => $maintenanceMode,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $_SESSION['sdtravels_manager']
        ];

        if (file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT))) {
            logActivity('manager', $_SESSION['sdtravels_manager'], 'update', 'Updated system settings');
            $success = 'System settings updated successfully!';
        } else {
            $error = 'Failed to save settings.';
        }
    }

    if (isset($_POST['add_service'])) {
        $serviceName = mysqli_real_escape_string($conn, $_POST['service_name']);
        $serviceDesc = mysqli_real_escape_string($conn, $_POST['service_description']);
        $basePrice = floatval($_POST['base_price']);

        $check = mysqli_query($conn, "SELECT id FROM services WHERE name = '$serviceName'");
        if (mysqli_num_rows($check) > 0) {
            $error = 'Service already exists!';
        } else {
            // Create services table if not exists
            mysqli_query($conn, "CREATE TABLE IF NOT EXISTS services (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL UNIQUE,
                description TEXT,
                base_price DECIMAL(12,2) DEFAULT 0,
                is_active TINYINT(1) DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");

            if (mysqli_query($conn, "INSERT INTO services (name, description, base_price) VALUES ('$serviceName', '$serviceDesc', '$basePrice')")) {
                logActivity('manager', $_SESSION['sdtravels_manager'], 'create', "Added service: $serviceName");
                $success = 'Service added successfully!';
            } else {
                $error = 'Failed to add service.';
            }
        }
    }

    if (isset($_POST['update_service']) && isset($_POST['service_id'])) {
        $serviceId = intval($_POST['service_id']);
        $serviceName = mysqli_real_escape_string($conn, $_POST['service_name']);
        $serviceDesc = mysqli_real_escape_string($conn, $_POST['service_description']);
        $basePrice = floatval($_POST['base_price']);
        $isActive = isset($_POST['is_active']) ? '1' : '0';

        if (mysqli_query($conn, "UPDATE services SET name = '$serviceName', description = '$serviceDesc', base_price = '$basePrice', is_active = '$isActive' WHERE id = $serviceId")) {
            logActivity('manager', $_SESSION['sdtravels_manager'], 'update', "Updated service: $serviceName");
            $success = 'Service updated successfully!';
        } else {
            $error = 'Failed to update service.';
        }
    }

    if (isset($_POST['delete_service']) && isset($_POST['service_id'])) {
        $serviceId = intval($_POST['service_id']);
        if (mysqli_query($conn, "DELETE FROM services WHERE id = $serviceId")) {
            logActivity('manager', $_SESSION['sdtravels_manager'], 'delete', "Deleted service ID: $serviceId");
            $success = 'Service deleted successfully!';
        } else {
            $error = 'Failed to delete service.';
        }
    }
}

// Load current settings
$settingsFile = '../config/settings.json';
$settings = [];
if (file_exists($settingsFile)) {
    $settings = json_decode(file_get_contents($settingsFile), true) ?: [];
}

$commissionSettings = $settings['commission'] ?? [
    'referral_rate' => 5.0,
    'case_completion_rate' => 10.0,
    'service_rate' => 3.0
];

$systemSettings = $settings['system'] ?? [
    'site_name' => 'ApplyBoard Africa Ltd',
    'site_email' => 'info@applyboardafrica.com',
    'site_phone' => '+250 788 000 000',
    'maintenance_mode' => '0'
];

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
     <link href="https://fonts.googleapis.com/css2c4ad.css?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
     <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />
     <script src="assets/js/config.js"></script>
     <!-- Iconify -->
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">ApplyBoard Africa Ltd</a></li>
                                        <li class="breadcrumb-item active">Settings</li>
                                   </ol>
                              </div>
                         </div>
                    </div>

                    <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                         <?= $success ?>
                         <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                         <?= $error ?>
                         <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <!-- Commission Settings -->
                    <div class="card mb-3">
                         <div class="card-header">
                              <h5 class="mb-0">Commission Rates</h5>
                         </div>
                         <div class="card-body">
                              <form method="POST" class="row">
                                   <div class="col-md-4 mb-3">
                                        <label class="form-label">Referral Commission Rate (%)</label>
                                        <input type="number" step="0.01" min="0" max="100" name="referral_rate"
                                               class="form-control" value="<?= $commissionSettings['referral_rate'] ?? 5.0 ?>">
                                        <small class="text-muted">Commission paid for successful referrals</small>
                                   </div>
                                   <div class="col-md-4 mb-3">
                                        <label class="form-label">Case Completion Rate (%)</label>
                                        <input type="number" step="0.01" min="0" max="100" name="case_completion_rate"
                                               class="form-control" value="<?= $commissionSettings['case_completion_rate'] ?? 10.0 ?>">
                                        <small class="text-muted">Commission on completed cases</small>
                                   </div>
                                   <div class="col-md-4 mb-3">
                                        <label class="form-label">Service Fee Rate (%)</label>
                                        <input type="number" step="0.01" min="0" max="100" name="service_rate"
                                               class="form-control" value="<?= $commissionSettings['service_rate'] ?? 3.0 ?>">
                                        <small class="text-muted">Commission on service fees</small>
                                   </div>
                                   <div class="col-12">
                                        <button type="submit" name="update_commission" class="btn btn-primary">
                                             <iconify-icon icon="solar:disk-outline"></iconify-icon> Save Commission Rates
                                        </button>
                                   </div>
                              </form>
                         </div>
                    </div>

                    <!-- System Settings -->
                    <div class="card mb-3">
                         <div class="card-header">
                              <h5 class="mb-0">General Settings</h5>
                         </div>
                         <div class="card-body">
                              <form method="POST">
                                   <div class="row">
                                        <div class="col-md-6 mb-3">
                                             <label class="form-label">Site Name</label>
                                             <input type="text" name="site_name" class="form-control"
                                                    value="<?= htmlspecialchars($systemSettings['site_name'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                             <label class="form-label">Site Email</label>
                                             <input type="email" name="site_email" class="form-control"
                                                    value="<?= htmlspecialchars($systemSettings['site_email'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                             <label class="form-label">Site Phone</label>
                                             <input type="text" name="site_phone" class="form-control"
                                                    value="<?= htmlspecialchars($systemSettings['site_phone'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                             <label class="form-label">Maintenance Mode</label>
                                             <div class="form-check form-switch mt-2">
                                                  <input class="form-check-input" type="checkbox" name="maintenance_mode"
                                                         value="1" <?= ($systemSettings['maintenance_mode'] ?? '0') === '1' ? 'checked' : '' ?>>
                                                  <label class="form-check-label">Enable maintenance mode (disables user access)</label>
                                             </div>
                                        </div>
                                        <div class="col-12">
                                             <button type="submit" name="update_system" class="btn btn-primary">
                                                  <iconify-icon icon="solar:disk-outline"></iconify-icon> Save System Settings
                                             </button>
                                        </div>
                                   </div>
                              </form>
                         </div>
                    </div>

                    <!-- Services Management -->
                    <div class="card mb-3">
                         <div class="card-header d-flex justify-content-between align-items-center">
                              <h5 class="mb-0">Service Catalog</h5>
                              <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                                   <iconify-icon icon="solar:add-circle-outline"></iconify-icon> Add Service
                              </button>
                         </div>
                         <div class="card-body">
                              <?php if (mysqli_num_rows($services) > 0): ?>
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
                                                  <td>$<?= number_format($service['base_price'], 2) ?></td>
                                                  <td>
                                                       <span class="badge <?= $service['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                                                            <?= $service['is_active'] ? 'Active' : 'Inactive' ?>
                                                       </span>
                                                  </td>
                                                  <td>
                                                       <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                               data-bs-target="#editServiceModal<?= $service['id'] ?>">
                                                            <iconify-icon icon="solar:pen-outline"></iconify-icon>
                                                       </button>
                                                       <form method="POST" class="d-inline" onsubmit="return confirm('Delete this service?');">
                                                            <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
                                                            <button type="submit" name="delete_service" class="btn btn-sm btn-outline-danger">
                                                                 <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                                                            </button>
                                                       </form>
                                                  </td>
                                             </tr>

                                             <!-- Edit Modal -->
                                             <div class="modal fade" id="editServiceModal<?= $service['id'] ?>" tabindex="-1">
                                                  <div class="modal-dialog">
                                                       <div class="modal-content">
                                                            <div class="modal-header">
                                                                 <h5 class="modal-title">Edit Service</h5>
                                                                 <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form method="POST">
                                                                 <div class="modal-body">
                                                                      <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
                                                                      <div class="mb-3">
                                                                           <label class="form-label">Service Name</label>
                                                                           <input type="text" name="service_name" class="form-control"
                                                                                  value="<?= htmlspecialchars($service['name']) ?>" required>
                                                                      </div>
                                                                      <div class="mb-3">
                                                                           <label class="form-label">Description</label>
                                                                           <textarea name="service_description" class="form-control" rows="3"><?= htmlspecialchars($service['description']) ?></textarea>
                                                                      </div>
                                                                      <div class="mb-3">
                                                                           <label class="form-label">Base Price</label>
                                                                           <input type="number" step="0.01" min="0" name="base_price"
                                                                                  class="form-control" value="<?= $service['base_price'] ?>" required>
                                                                      </div>
                                                                      <div class="mb-3">
                                                                           <div class="form-check">
                                                                                <input class="form-check-input" type="checkbox" name="is_active"
                                                                                       value="1" <?= $service['is_active'] ? 'checked' : '' ?>>
                                                                                <label class="form-check-label">Active</label>
                                                                           </div>
                                                                      </div>
                                                                 </div>
                                                                 <div class="modal-footer">
                                                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                      <button type="submit" name="update_service" class="btn btn-primary">Update Service</button>
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
                                   <iconify-icon icon="solar:document-text-outline" class="fs-48 text-muted mb-2"></iconify-icon>
                                   <p class="text-muted mb-0">No services found. Add your first service to get started.</p>
                              </div>
                              <?php endif; ?>
                         </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="card mb-3">
                         <div class="card-header">
                              <h5 class="mb-0">System Information</h5>
                         </div>
                         <div class="card-body">
                              <div class="row">
                                   <div class="col-md-4 mb-3">
                                        <label class="form-label text-muted">PHP Version</label>
                                        <div class="fw-bold"><?= PHP_VERSION ?></div>
                                   </div>
                                   <div class="col-md-4 mb-3">
                                        <label class="form-label text-muted">MySQL Version</label>
                                        <div class="fw-bold"><?= mysqli_get_server_info($conn) ?></div>
                                   </div>
                                   <div class="col-md-4 mb-3">
                                        <label class="form-label text-muted">Server Time</label>
                                        <div class="fw-bold"><?= date('Y-m-d H:i:s') ?></div>
                                   </div>
                                   <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Last Settings Update</label>
                                        <div class="fw-bold"><?= $commissionSettings['updated_at'] ?? 'Never' ?></div>
                                   </div>
                                   <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Updated By</label>
                                        <div class="fw-bold"><?= $commissionSettings['updated_by'] ?? 'N/A' ?></div>
                                   </div>
                              </div>
                         </div>
                    </div>

               </div>

               <footer class="footer card mb-0 rounded-0 justify-content-center align-items-center">
                    <div class="container-fluid">
                         <div class="row">
                              <div class="col-12 text-center">
                                   <p class="mb-0"><script>document.write(new Date().getFullYear())</script> &copy; ApplyBoard Africa Ltd.</p>
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
                                   <label class="form-label">Base Price ($)</label>
                                   <input type="number" step="0.01" min="0" name="base_price" class="form-control" required>
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

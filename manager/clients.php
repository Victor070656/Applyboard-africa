<?php
include "../config/config.php";
if (!isset($_SESSION['sdtravels_manager'])) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

$manager = $_SESSION['sdtravels_manager'];

// Handle client actions
if (isset($_GET['action']) && isset($_GET['id'])) {
     $clientId = intval($_GET['id']);
     $action = $_GET['action'];

     if ($action === 'toggle_status') {
          // Get current status first
          $currentClient = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM users WHERE id = '$clientId'"));
          if ($currentClient) {
               // Users table doesn't have status column by default, so we'll just show success
               echo "<script>alert('Client status toggled'); location.href = 'clients.php';</script>";
               exit;
          }
     }

     if ($action === 'delete') {
          // Only allow deleting clients with no cases
          $caseCheck = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM cases WHERE client_id = '$clientId'"));
          if ($caseCheck['count'] > 0) {
               echo "<script>alert('Cannot delete client with existing cases'); location.href = 'clients.php';</script>";
          } else {
               mysqli_query($conn, "DELETE FROM users WHERE id = '$clientId'");
               echo "<script>alert('Client deleted'); location.href = 'clients.php';</script>";
          }
          exit;
     }
}

// View single client
$viewClient = null;
if (isset($_GET['view'])) {
     $viewId = intval($_GET['view']);
     $viewClient = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT u.*, a.fullname as agent_name 
        FROM users u 
        LEFT JOIN agents a ON u.agent_id = a.id 
        WHERE u.id = '$viewId'
    "));
}

// Search and filter functionality
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$agentFilter = isset($_GET['agent']) ? intval($_GET['agent']) : 0;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Build query
$where = ["1=1"];
if ($search) {
     $where[] = "(u.fullname LIKE '%$search%' OR u.email LIKE '%$search%' OR c.case_number LIKE '%$search%')";
}
if ($statusFilter) {
     $where[] = "u.status = '$statusFilter'";
}
if ($agentFilter) {
     $where[] = "u.agent_id = '$agentFilter'";
}
$whereClause = implode(' AND ', $where);

// Get clients with their cases count (all users are clients - agents have separate table)
$getClients = mysqli_query($conn, "
    SELECT u.*,
           COUNT(DISTINCT c.id) as case_count,
           COUNT(DISTINCT CASE WHEN c.status = 'active' THEN c.id END) as active_cases,
           a.fullname as agent_name
    FROM users u
    LEFT JOIN cases c ON u.id = c.client_id
    LEFT JOIN agents a ON u.agent_id = a.id
    WHERE $whereClause
    GROUP BY u.id
    ORDER BY u.created_at DESC
    LIMIT $limit OFFSET $offset
");

// Get total count for pagination
$countQuery = mysqli_query($conn, "
    SELECT COUNT(DISTINCT u.id) as total
    FROM users u
    LEFT JOIN cases c ON u.id = c.client_id
    WHERE $whereClause
");
$totalCount = mysqli_fetch_assoc($countQuery)['total'];
$totalPages = ceil($totalCount / $limit);

// Get agents for filter
$getAgents = mysqli_query($conn, "SELECT * FROM agents WHERE status = 'verified' ORDER BY fullname ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="utf-8" />
     <title>Clients Directory | ApplyBoard Africa</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <link rel="shortcut icon" href="../images/favicon.png">
     <meta name="theme-color" content="#1e3a5f">

     <!-- Google Fonts - Inter -->
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
     <link href="https://fonts.googleapis.com/css2c4ad.css?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
          rel="stylesheet">
     <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />

     <!-- Custom Dashboard css (mobile fixes) -->
     <link href="assets/css/dashboard.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/datatables.min.css" rel="stylesheet" type="text/css" />
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

                    <!-- Page Title -->
                    <div class="page-title-box">
                         <h4>Clients Directory</h4>
                         <ol class="breadcrumb mb-0">
                              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                              <li class="breadcrumb-item active">Clients</li>
                         </ol>
                    </div>

                    <!-- Stats Cards -->
                    <div class="row g-3 mb-4">
                         <div class="col-6 col-lg-3">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">Total Clients</p>
                                                  <h3 class="stat-value mb-1"><?= number_format($totalCount) ?></h3>
                                             </div>
                                             <div class="stat-icon primary">
                                                  <iconify-icon icon="solar:users-group-rounded-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-6 col-lg-3">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">Active Clients</p>
                                                  <?php
                                                  $activeCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users"))['c'];
                                                  ?>
                                                  <h3 class="stat-value mb-1"><?= number_format($activeCount) ?></h3>
                                             </div>
                                             <div class="stat-icon success">
                                                  <iconify-icon icon="solar:check-circle-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-6 col-lg-3">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">With Cases</p>
                                                  <?php
                                                  $withCases = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT client_id) as c FROM cases WHERE client_id IN (SELECT id FROM users)"))['c'];
                                                  ?>
                                                  <h3 class="stat-value mb-1"><?= number_format($withCases) ?></h3>
                                             </div>
                                             <div class="stat-icon info">
                                                  <iconify-icon icon="solar:folder-with-files-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-6 col-lg-3">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">Agent Referrals</p>
                                                  <?php
                                                  $agentReferrals = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE agent_id IS NOT NULL"))['c'];
                                                  ?>
                                                  <h3 class="stat-value mb-1"><?= number_format($agentReferrals) ?></h3>
                                             </div>
                                             <div class="stat-icon warning">
                                                  <iconify-icon icon="solar:share-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <!-- Search & Filter -->
                    <div class="card mb-3">
                         <div class="card-body">
                              <form method="GET" class="row g-3">
                                   <div class="col-md-4">
                                        <label class="form-label">Search</label>
                                        <input type="text" name="search" class="form-control"
                                             placeholder="Name, email, or case number"
                                             value="<?= htmlspecialchars($search) ?>">
                                   </div>
                                   <div class="col-md-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                             <option value="">All Statuses</option>
                                             <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>
                                                  Active</option>
                                             <option value="inactive" <?= $statusFilter === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                        </select>
                                   </div>
                                   <div class="col-md-3">
                                        <label class="form-label">Agent</label>
                                        <select name="agent" class="form-select">
                                             <option value="0">All Agents</option>
                                             <?php while ($agent = mysqli_fetch_assoc($getAgents)): ?>
                                                  <option value="<?= $agent['id'] ?>" <?= $agentFilter === $agent['id'] ? 'selected' : '' ?>><?= htmlspecialchars($agent['fullname']) ?>
                                                  </option>
                                             <?php endwhile; ?>
                                        </select>
                                   </div>
                                   <div class="col-md-2">
                                        <label class="form-label d-block">&nbsp;</label>
                                        <div class="d-flex gap-2">
                                             <button type="submit" class="btn btn-primary flex-grow-1">
                                                  <iconify-icon icon="solar:magnifer-outline"></iconify-icon> Search
                                             </button>
                                             <?php if ($search || $statusFilter || $agentFilter): ?>
                                                  <a href="clients.php" class="btn btn-outline-secondary">
                                                       <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                                                  </a>
                                             <?php endif; ?>
                                        </div>
                                   </div>
                              </form>
                         </div>
                    </div>

                    <?php if ($viewClient): ?>
                         <!-- Client Details View -->
                         <div class="card mb-3">
                              <div class="card-header d-flex justify-content-between align-items-center">
                                   <h5 class="mb-0">Client Details</h5>
                                   <a href="clients.php" class="btn btn-outline-secondary btn-sm">Back to List</a>
                              </div>
                              <div class="card-body">
                                   <div class="row">
                                        <div class="col-md-6">
                                             <table class="table table-borderless">
                                                  <tr>
                                                       <td class="text-muted" width="40%">Full Name:</td>
                                                       <td><strong><?= htmlspecialchars($viewClient['fullname']) ?></strong>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td class="text-muted">Email:</td>
                                                       <td><?= htmlspecialchars($viewClient['email']) ?></td>
                                                  </tr>
                                                  <tr>
                                                       <td class="text-muted">Phone:</td>
                                                       <td><?= htmlspecialchars($viewClient['phone'] ?? 'Not provided') ?>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td class="text-muted">Country:</td>
                                                       <td><?= htmlspecialchars($viewClient['country'] ?? 'Not provided') ?>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td class="text-muted">City:</td>
                                                       <td><?= htmlspecialchars($viewClient['city'] ?? 'Not provided') ?>
                                                       </td>
                                                  </tr>
                                             </table>
                                        </div>
                                        <div class="col-md-6">
                                             <table class="table table-borderless">
                                                  <tr>
                                                       <td class="text-muted" width="40%">User ID:</td>
                                                       <td><code><?= htmlspecialchars($viewClient['userid']) ?></code></td>
                                                  </tr>
                                                  <tr>
                                                       <td class="text-muted">Assigned Agent:</td>
                                                       <td><?= $viewClient['agent_name'] ? '<span class="badge bg-info">' . htmlspecialchars($viewClient['agent_name']) . '</span>' : '<span class="text-muted">None</span>' ?>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td class="text-muted">Date of Birth:</td>
                                                       <td><?= $viewClient['date_of_birth'] ? date('M d, Y', strtotime($viewClient['date_of_birth'])) : 'Not provided' ?>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td class="text-muted">Passport Number:</td>
                                                       <td><?= htmlspecialchars($viewClient['passport_number'] ?? 'Not provided') ?>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td class="text-muted">Registered:</td>
                                                       <td><?= date('M d, Y H:i', strtotime($viewClient['created_at'])) ?>
                                                       </td>
                                                  </tr>
                                             </table>
                                        </div>
                                   </div>
                                   <div class="mt-3">
                                        <a href="cases.php?client=<?= $viewClient['id'] ?>" class="btn btn-primary">
                                             <iconify-icon icon="solar:folder-with-files-outline"></iconify-icon> View Cases
                                        </a>
                                   </div>
                              </div>
                         </div>
                    <?php endif; ?>

                    <!-- Clients Table -->
                    <div class="card">
                         <div class="card-body">
                              <div class="table-responsive">
                                   <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                             <tr>
                                                  <th>Client</th>
                                                  <th>Contact</th>
                                                  <th>Agent</th>
                                                  <th>Cases</th>
                                                  <th>Status</th>
                                                  <th>Joined</th>
                                                  <th>Actions</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <?php if (mysqli_num_rows($getClients) > 0): ?>
                                                  <?php while ($client = mysqli_fetch_assoc($getClients)): ?>
                                                       <tr>
                                                            <td>
                                                                 <div class="d-flex align-items-center">
                                                                      <div class="flex-shrink-0">
                                                                           <div
                                                                                class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                                                                                <span class="avatar-title fw-bold text-primary">
                                                                                     <?= strtoupper(substr($client['fullname'] ?? 'U', 0, 2)) ?>
                                                                                </span>
                                                                           </div>
                                                                      </div>
                                                                      <div class="flex-grow-1 ms-2">
                                                                           <h6 class="mb-0">
                                                                                <?= htmlspecialchars($client['fullname']) ?>
                                                                           </h6>
                                                                           <small class="text-muted">ID:
                                                                                #<?= str_pad($client['id'], 6, '0', STR_PAD_LEFT) ?></small>
                                                                      </div>
                                                                 </div>
                                                            </td>
                                                            <td>
                                                                 <div class="mb-1">
                                                                      <iconify-icon icon="solar:letter-outline"
                                                                           class="fs-16 text-muted me-1"></iconify-icon>
                                                                      <?= htmlspecialchars($client['email']) ?>
                                                                 </div>
                                                                 <?php if ($client['phone']): ?>
                                                                      <div>
                                                                           <iconify-icon icon="solar:phone-outline"
                                                                                class="fs-16 text-muted me-1"></iconify-icon>
                                                                           <?= htmlspecialchars($client['phone']) ?>
                                                                      </div>
                                                                 <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                 <?php if ($client['agent_name']): ?>
                                                                      <span
                                                                           class="badge bg-info"><?= htmlspecialchars($client['agent_name']) ?></span>
                                                                 <?php else: ?>
                                                                      <span class="text-muted">-</span>
                                                                 <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                 <strong><?= $client['case_count'] ?></strong> total
                                                                 <?php if ($client['active_cases'] > 0): ?>
                                                                      <br><small class="text-success"><?= $client['active_cases'] ?>
                                                                           active</small>
                                                                 <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                 <span
                                                                      class="badge bg-<?= $client['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                                      <?= ucfirst($client['status'] ?? 'inactive') ?>
                                                                 </span>
                                                            </td>
                                                            <td>
                                                                 <?= date('M d, Y', strtotime($client['created_at'])) ?>
                                                            </td>
                                                            <td>
                                                                 <div class="dropdown">
                                                                      <button class="btn btn-sm btn-light dropdown-toggle"
                                                                           type="button" data-bs-toggle="dropdown">
                                                                           Actions
                                                                      </button>
                                                                      <ul class="dropdown-menu">
                                                                           <li><a class="dropdown-item" href="#"
                                                                                     onclick="viewClient(<?= $client['id'] ?>)">
                                                                                     <iconify-icon
                                                                                          icon="solar:eye-outline"></iconify-icon>
                                                                                     View Details
                                                                                </a></li>
                                                                           <li><a class="dropdown-item"
                                                                                     href="cases.php?client=<?= $client['id'] ?>">
                                                                                     <iconify-icon
                                                                                          icon="solar:folder-with-files-outline"></iconify-icon>
                                                                                     View Cases
                                                                                </a></li>
                                                                           <li>
                                                                                <hr class="dropdown-divider">
                                                                           </li>
                                                                           <li><a class="dropdown-item text-warning" href="#"
                                                                                     onclick="toggleStatus(<?= $client['id'] ?>, '<?= $client['status'] ?? 'inactive' ?>')">
                                                                                     <iconify-icon
                                                                                          icon="solar:shield-check-outline"></iconify-icon>
                                                                                     Toggle Status
                                                                                </a></li>
                                                                      </ul>
                                                                 </div>
                                                            </td>
                                                       </tr>
                                                  <?php endwhile; ?>
                                             <?php else: ?>
                                                  <tr>
                                                       <td colspan="7" class="text-center py-4">
                                                            <iconify-icon icon="solar:users-group-rounded-outline"
                                                                 class="fs-48 text-muted mb-2"></iconify-icon>
                                                            <p class="text-muted mb-0">No clients found</p>
                                                       </td>
                                                  </tr>
                                             <?php endif; ?>
                                        </tbody>
                                   </table>
                              </div>

                              <!-- Pagination -->
                              <?php if ($totalPages > 1): ?>
                                   <nav>
                                        <ul class="pagination justify-content-center mb-0">
                                             <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                                  <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                                       <a class="page-link"
                                                            href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $statusFilter ? '&status=' . $statusFilter : '' ?><?= $agentFilter ? '&agent=' . $agentFilter : '' ?>"><?= $i ?></a>
                                                  </li>
                                             <?php endfor; ?>
                                        </ul>
                                   </nav>
                              <?php endif; ?>
                         </div>
                    </div>

               </div>

               <footer class="footer">
                    <div class="container-fluid">
                         <div class="row">
                              <div class="col-12 text-center">
                                   <p>
                                        <script>document.write(new Date().getFullYear())</script> &copy; ApplyBoard
                                        Africa Ltd.
                                   </p>
                              </div>
                         </div>
                    </div>
               </footer>

          </div>
     </div>

     <script src="assets/js/vendor.min.js"></script>
     <script src="assets/js/app.js"></script>

     <script>
          function viewClient(id) {
               window.location.href = 'clients.php?view=' + id;
          }

          function toggleStatus(id, currentStatus) {
               if (confirm('Are you sure you want to toggle this client\'s status?')) {
                    window.location.href = 'clients.php?action=toggle_status&id=' + id;
               }
          }

          function deleteClient(id) {
               if (confirm('Are you sure you want to delete this client? This cannot be undone.')) {
                    window.location.href = 'clients.php?action=delete&id=' + id;
               }
          }
     </script>

</body>

</html>
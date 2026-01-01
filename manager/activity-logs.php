<?php
include "../config/config.php";
if (!isset($_SESSION['sdtravels_manager'])) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

// Search and filter functionality
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$actionFilter = isset($_GET['action']) ? mysqli_real_escape_string($conn, $_GET['action']) : '';
$userTypeFilter = isset($_GET['user_type']) ? mysqli_real_escape_string($conn, $_GET['user_type']) : '';
$fromDate = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$toDate = isset($_GET['to_date']) ? $_GET['to_date'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 50;
$offset = ($page - 1) * $limit;

// Build query
$where = ["1=1"];
$params = [];
$types = [];

if ($search) {
     $where[] = "(al.description LIKE ? OR al.action LIKE ? OR al.ip_address LIKE ?)";
     $searchTerm = "%$search%";
     $params[] = $searchTerm;
     $params[] = $searchTerm;
     $params[] = $searchTerm;
}
if ($actionFilter) {
     $where[] = "al.action = ?";
     $params[] = $actionFilter;
}
if ($userTypeFilter) {
     $where[] = "al.user_type = ?";
     $params[] = $userTypeFilter;
}
if ($fromDate) {
     $where[] = "DATE(al.created_at) >= ?";
     $params[] = $fromDate;
}
if ($toDate) {
     $where[] = "DATE(al.created_at) <= ?";
     $params[] = $toDate;
}

$whereClause = implode(' AND ', $where);

// Get activity logs with prepared statement
$sql = "SELECT al.*,
        CASE
            WHEN al.user_type = 'manager' THEN (SELECT email FROM admin WHERE id = al.user_id)
            WHEN al.user_type = 'agent' THEN (SELECT fullname FROM agents WHERE id = al.user_id)
            WHEN al.user_type = 'client' THEN (SELECT fullname FROM users WHERE id = al.user_id)
            ELSE 'System'
        END as user_name
        FROM activity_logs al
        WHERE $whereClause
        ORDER BY al.created_at DESC
        LIMIT $offset, $limit";

// For simplicity, using direct query with escapes
$queryWhere = [];
if ($search)
     $queryWhere[] = "(description LIKE '%$search%' OR action LIKE '%$search%' OR ip_address LIKE '%$search%')";
if ($actionFilter)
     $queryWhere[] = "action = '$actionFilter'";
if ($userTypeFilter)
     $queryWhere[] = "user_type = '$userTypeFilter'";
if ($fromDate)
     $queryWhere[] = "DATE(created_at) >= '$fromDate'";
if ($toDate)
     $queryWhere[] = "DATE(created_at) <= '$toDate'";
$queryWhereClause = implode(' AND ', $queryWhere);
if ($queryWhereClause)
     $queryWhereClause = 'WHERE ' . $queryWhereClause;

$getLogs = mysqli_query($conn, "
    SELECT al.*,
           CASE
               WHEN al.user_type = 'manager' THEN (SELECT email FROM admin WHERE id = al.user_id)
               WHEN al.user_type = 'agent' THEN (SELECT fullname FROM agents WHERE id = al.user_id)
               WHEN al.user_type = 'client' THEN (SELECT fullname FROM users WHERE id = al.user_id)
               ELSE 'System'
           END as user_name
    FROM activity_logs al
    $queryWhereClause
    ORDER BY al.created_at DESC
    LIMIT $limit OFFSET $offset
");

// Get total count
$countResult = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total FROM activity_logs al $queryWhereClause
"));
$totalCount = $countResult['total'];
$totalPages = ceil($totalCount / $limit);

// Get unique actions for filter
$actions = [
     'login' => 'Login',
     'logout' => 'Logout',
     'create' => 'Created',
     'update' => 'Updated',
     'delete' => 'Deleted',
     'approve' => 'Approved',
     'reject' => 'Rejected',
     'upload' => 'Uploaded',
     'download' => 'Downloaded'
];

// Get stats
$todayLogs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM activity_logs WHERE DATE(created_at) = CURDATE()"))['c'];
$weekLogs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM activity_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"))['c'];

function getActionBadge($action)
{
     $badges = [
          'login' => 'bg-success',
          'logout' => 'bg-secondary',
          'create' => 'bg-primary',
          'update' => 'bg-info',
          'delete' => 'bg-danger',
          'approve' => 'bg-success',
          'reject' => 'bg-warning',
          'upload' => 'bg-info',
          'download' => 'bg-secondary'
     ];
     return $badges[$action] ?? 'bg-secondary';
}

function getUserTypeBadge($type)
{
     $badges = [
          'manager' => 'bg-dark',
          'agent' => 'bg-info',
          'client' => 'bg-primary',
          'system' => 'bg-secondary'
     ];
     return $badges[$type] ?? 'bg-secondary';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="utf-8" />
     <title>Activity Logs | ApplyBoard Africa</title>
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
                                   <h4 class="mb-0">Activity Logs</h4>
                                   <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Activity Logs</li>
                                   </ol>
                              </div>
                         </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="row g-3 mb-4">
                         <div class="col-6 col-lg-4">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">Total Logs</p>
                                                  <h3 class="stat-value mb-1"><?= number_format($totalCount) ?></h3>
                                             </div>
                                             <div class="stat-icon primary">
                                                  <iconify-icon icon="solar:document-text-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-6 col-lg-4">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">Today</p>
                                                  <h3 class="stat-value mb-1"><?= number_format($todayLogs) ?></h3>
                                             </div>
                                             <div class="stat-icon success">
                                                  <iconify-icon icon="solar:calendar-outline"></iconify-icon>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-12 col-lg-4">
                              <div class="stat-card card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                             <div>
                                                  <p class="stat-label mb-1">This Week</p>
                                                  <h3 class="stat-value mb-1"><?= number_format($weekLogs) ?></h3>
                                             </div>
                                             <div class="stat-icon info">
                                                  <iconify-icon icon="solar:clock-circle-outline"></iconify-icon>
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
                                   <div class="col-md-3">
                                        <label class="form-label">Search</label>
                                        <input type="text" name="search" class="form-control"
                                             placeholder="Description, action, or IP"
                                             value="<?= htmlspecialchars($search) ?>">
                                   </div>
                                   <div class="col-md-2">
                                        <label class="form-label">Action</label>
                                        <select name="action" class="form-select">
                                             <option value="">All Actions</option>
                                             <?php foreach ($actions as $key => $label): ?>
                                                  <option value="<?= $key ?>" <?= $actionFilter === $key ? 'selected' : '' ?>>
                                                       <?= $label ?></option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                                   <div class="col-md-2">
                                        <label class="form-label">User Type</label>
                                        <select name="user_type" class="form-select">
                                             <option value="">All Users</option>
                                             <option value="manager" <?= $userTypeFilter === 'manager' ? 'selected' : '' ?>>Manager</option>
                                             <option value="agent" <?= $userTypeFilter === 'agent' ? 'selected' : '' ?>>
                                                  Agent</option>
                                             <option value="client" <?= $userTypeFilter === 'client' ? 'selected' : '' ?>>
                                                  Client</option>
                                        </select>
                                   </div>
                                   <div class="col-md-2">
                                        <label class="form-label">From Date</label>
                                        <input type="date" name="from_date" class="form-control"
                                             value="<?= $fromDate ?>">
                                   </div>
                                   <div class="col-md-2">
                                        <label class="form-label">To Date</label>
                                        <input type="date" name="to_date" class="form-control" value="<?= $toDate ?>">
                                   </div>
                                   <div class="col-md-1">
                                        <label class="form-label d-block">&nbsp;</label>
                                        <div class="d-flex gap-2">
                                             <button type="submit" class="btn btn-primary flex-grow-1">
                                                  <iconify-icon icon="solar:magnifer-outline"></iconify-icon>
                                             </button>
                                             <?php if ($search || $actionFilter || $userTypeFilter || $fromDate || $toDate): ?>
                                                  <a href="activity-logs.php" class="btn btn-outline-secondary">
                                                       <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                                                  </a>
                                             <?php endif; ?>
                                        </div>
                                   </div>
                              </form>
                         </div>
                    </div>

                    <!-- Logs Table -->
                    <div class="card">
                         <div class="card-header d-flex justify-content-between align-items-center">
                              <h5 class="mb-0">System Activity</h5>
                              <a href="activity-logs.php?export=true" class="btn btn-outline-primary btn-sm">
                                   <iconify-icon icon="solar:download-minimalistic-outline"></iconify-icon> Export Logs
                              </a>
                         </div>
                         <div class="card-body">
                              <div class="table-responsive">
                                   <table class="table table-striped mb-0">
                                        <thead>
                                             <tr>
                                                  <th>Timestamp</th>
                                                  <th>User</th>
                                                  <th>Action</th>
                                                  <th>Description</th>
                                                  <th>IP Address</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <?php if (mysqli_num_rows($getLogs) > 0): ?>
                                                  <?php while ($log = mysqli_fetch_assoc($getLogs)): ?>
                                                       <tr>
                                                            <td>
                                                                 <small
                                                                      class="text-muted"><?= date('M d, Y', strtotime($log['created_at'])) ?></small><br>
                                                                 <small><?= date('h:i A', strtotime($log['created_at'])) ?></small>
                                                            </td>
                                                            <td>
                                                                 <span
                                                                      class="badge <?= getUserTypeBadge($log['user_type']) ?>"><?= ucfirst($log['user_type']) ?></span>
                                                                 <div><?= htmlspecialchars($log['user_name'] ?? 'System') ?></div>
                                                            </td>
                                                            <td>
                                                                 <span
                                                                      class="badge <?= getActionBadge($log['action']) ?>"><?= ucfirst($log['action']) ?></span>
                                                            </td>
                                                            <td><?= htmlspecialchars($log['description']) ?></td>
                                                            <td><small
                                                                      class="text-muted"><?= htmlspecialchars($log['ip_address'] ?? '-') ?></small>
                                                            </td>
                                                       </tr>
                                                  <?php endwhile; ?>
                                             <?php else: ?>
                                                  <tr>
                                                       <td colspan="5" class="text-center py-4">
                                                            <iconify-icon icon="solar:document-text-outline"
                                                                 class="fs-48 text-muted mb-2"></iconify-icon>
                                                            <p class="text-muted mb-0">No activity logs found</p>
                                                       </td>
                                                  </tr>
                                             <?php endif; ?>
                                        </tbody>
                                   </table>
                              </div>

                              <!-- Pagination -->
                              <?php if ($totalPages > 1): ?>
                                   <nav class="mt-3">
                                        <ul class="pagination justify-content-center mb-0">
                                             <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                                  <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                                       <a class="page-link"
                                                            href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $actionFilter ? '&action=' . $actionFilter : '' ?><?= $userTypeFilter ? '&user_type=' . $userTypeFilter : '' ?><?= $fromDate ? '&from_date=' . $fromDate : '' ?><?= $toDate ? '&to_date=' . $toDate : '' ?>"><?= $i ?></a>
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

</body>

</html>
<?php
include "../config/config.php";
if (!isset($_SESSION['sdtravels_manager'])) {
     echo "<script>alert('Please Login First'); location.href = 'login.php'</script>";
}

// Report type
$reportType = isset($_GET['type']) ? $_GET['type'] : 'overview';
$period = isset($_GET['period']) ? $_GET['period'] : 'month';

// Calculate date range based on period
switch ($period) {
    case 'week':
        $startDate = date('Y-m-d', strtotime('-7 days'));
        $endDate = date('Y-m-d');
        break;
    case 'month':
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        break;
    case 'quarter':
        $currentQuarter = ceil(date('n') / 3);
        $startDate = date('Y-m-d', mktime(0, 0, 0, ($currentQuarter - 1) * 3 + 1, 1));
        $endDate = date('Y-m-d', mktime(0, 0, 0, $currentQuarter * 3, date('t', mktime(0, 0, 0, $currentQuarter * 3, 1))));
        break;
    case 'year':
        $startDate = date('Y-01-01');
        $endDate = date('Y-12-31');
        break;
    default:
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
}

// Get report data based on type
if ($reportType === 'overview') {
    // Overview stats
    $stats = [
        'totalRevenue' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(amount), 0) as c FROM commissions WHERE status = 'paid' AND created_at BETWEEN '$startDate' AND '$endDate'"))['c'],
        'totalCases' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM cases WHERE created_at BETWEEN '$startDate' AND '$endDate'"))['c'],
        'totalAgents' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM agents WHERE status = 'verified'"))['c'],
        'totalClients' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE role = 'client' AND created_at BETWEEN '$startDate' AND '$endDate'"))['c'],
        'pendingInquiries' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM inquiries WHERE status = 'new'"))['c'],
        'activeCases' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM cases WHERE status = 'active'"))['c'],
        'completedCases' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM cases WHERE status = 'completed' AND created_at BETWEEN '$startDate' AND '$endDate'"))['c'],
        'pendingCommissions' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM commissions WHERE status = 'pending'"))['c']
    ];

    // Cases by type
    $casesByType = mysqli_query($conn, "
        SELECT case_type, COUNT(*) as count,
               SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
        FROM cases
        WHERE created_at BETWEEN '$startDate' AND '$endDate'
        GROUP BY case_type
    ");

    // Agent performance
    $agentPerformance = mysqli_query($conn, "
        SELECT a.fullname, a.id,
               COUNT(DISTINCT c.id) as case_count,
               COUNT(DISTINCT CASE WHEN c.status = 'completed' THEN c.id END) as completed_cases,
               COALESCE(SUM(com.amount), 0) as total_commissions
        FROM agents a
        LEFT JOIN cases c ON a.id = c.agent_id AND c.created_at BETWEEN '$startDate' AND '$endDate'
        LEFT JOIN commissions com ON a.id = com.agent_id AND com.status = 'paid' AND com.created_at BETWEEN '$startDate' AND '$endDate'
        WHERE a.status = 'verified'
        GROUP BY a.id
        ORDER BY completed_cases DESC, case_count DESC
        LIMIT 10
    ");

    // Monthly trend for the year
    $monthlyTrend = [];
    for ($i = 1; $i <= 12; $i++) {
        $monthData = mysqli_fetch_assoc(mysqli_query($conn, "
            SELECT COUNT(*) as cases,
                   COALESCE(SUM(com.amount), 0) as revenue
            FROM cases c
            LEFT JOIN commissions com ON c.id = com.case_id AND com.status = 'paid'
            WHERE YEAR(c.created_at) = YEAR(CURDATE()) AND MONTH(c.created_at) = $i
        "));
        $monthlyTrend[] = [
            'month' => date('M', mktime(0, 0, 0, $i, 1)),
            'cases' => $monthData['cases'],
            'revenue' => $monthData['revenue']
        ];
    }
}

elseif ($reportType === 'agents') {
    // Agent detailed report
    $agentReport = mysqli_query($conn, "
        SELECT a.id, a.fullname, a.email, a.phone, a.status,
               a.created_at as joined_date,
               COUNT(DISTINCT c.id) as total_cases,
               COUNT(DISTINCT CASE WHEN c.status = 'active' THEN c.id END) as active_cases,
               COUNT(DISTINCT CASE WHEN c.status = 'completed' THEN c.id END) as completed_cases,
               COUNT(DISTINCT CASE WHEN c.status = 'pending' THEN c.id END) as pending_cases,
               COUNT(DISTINCT u.id) as total_clients,
               COALESCE(SUM(com.amount), 0) as total_earned,
               COALESCE(SUM(CASE WHEN com.status = 'pending' THEN com.amount ELSE 0 END), 0) as pending_commission
        FROM agents a
        LEFT JOIN cases c ON a.id = c.agent_id
        LEFT JOIN users u ON a.id = u.agent_id AND u.role = 'client'
        LEFT JOIN commissions com ON a.id = com.agent_id
        GROUP BY a.id
        ORDER BY total_earned DESC
    ");
}

elseif ($reportType === 'cases') {
    // Case analysis report
    $caseStats = mysqli_query($conn, "
        SELECT
            DATE_FORMAT(created_at, '%Y-%m') as month,
            case_type,
            COUNT(*) as total,
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending
        FROM cases
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY DATE_FORMAT(created_at, '%Y-%m'), case_type
        ORDER BY month DESC, case_type
    ");
}

elseif ($reportType === 'financial') {
    // Financial report
    $financialData = mysqli_query($conn, "
        SELECT
            DATE(com.created_at) as date,
            com.status,
            COUNT(*) as count,
            SUM(com.amount) as total
        FROM commissions com
        WHERE com.created_at BETWEEN '$startDate' AND '$endDate'
        GROUP BY DATE(com.created_at), com.status
        ORDER BY date DESC
    ");

    $financialSummary = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT
            COUNT(CASE WHEN status = 'paid' THEN 1 END) as paid_count,
            COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_count,
            COALESCE(SUM(CASE WHEN status = 'paid' THEN amount END), 0) as paid_amount,
            COALESCE(SUM(CASE WHEN status = 'pending' THEN amount END), 0) as pending_amount
        FROM commissions
        WHERE created_at BETWEEN '$startDate' AND '$endDate'
    "));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="utf-8" />
     <title>ApplyBoard Africa Ltd || Reports</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <link rel="shortcut icon" href="../images/favicon.png">
     <link href="https://fonts.googleapis.com/css2c4ad.css?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
     <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />
     <script src="assets/js/config.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
     <!-- Iconify -->
     <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>

     <style>
        .report-tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
        }
        .report-tab.active {
            border-bottom-color: #0F4C75;
            color: #0F4C75;
            font-weight: 600;
        }
        .stat-card-large {
            text-align: center;
            padding: 20px;
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
                                   <h4 class="mb-0">Reports & Analytics</h4>
                                   <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">ApplyBoard Africa Ltd</a></li>
                                        <li class="breadcrumb-item active">Reports</li>
                                   </ol>
                              </div>
                         </div>
                    </div>

                    <!-- Report Type Tabs -->
                    <div class="card mb-3">
                         <div class="card-body">
                              <div class="d-flex flex-wrap gap-4">
                                   <div class="report-tab <?= $reportType === 'overview' ? 'active' : '' ?>" onclick="location.href='reports.php?type=overview&period=<?= $period ?>'">
                                        <iconify-icon icon="solar:widget-2-outline"></iconify-icon> Overview
                                   </div>
                                   <div class="report-tab <?= $reportType === 'agents' ? 'active' : '' ?>" onclick="location.href='reports.php?type=agents'">
                                        <iconify-icon icon="solar:users-group-rounded-outline"></iconify-icon> Agent Performance
                                   </div>
                                   <div class="report-tab <?= $reportType === 'cases' ? 'active' : '' ?>" onclick="location.href='reports.php?type=cases'">
                                        <iconify-icon icon="solar:folder-with-files-outline"></iconify-icon> Case Analysis
                                   </div>
                                   <div class="report-tab <?= $reportType === 'financial' ? 'active' : '' ?>" onclick="location.href='reports.php?type=financial&period=<?= $period ?>'">
                                        <iconify-icon icon="solar:wallet-money-outline"></iconify-icon> Financial
                                   </div>
                              </div>
                         </div>
                    </div>

                    <?php if ($reportType === 'overview'): ?>
                        <!-- Overview Report -->
                        <div class="row mb-3">
                             <div class="col-md-3">
                                  <div class="card stat-card-large bg-primary bg-opacity-10">
                                       <iconify-icon icon="solar:wallet-money-outline" class="fs-32 text-primary"></iconify-icon>
                                       <h3>$<?= number_format($stats['totalRevenue'], 2) ?></h3>
                                       <p class="text-muted mb-0">Total Revenue</p>
                                  </div>
                             </div>
                             <div class="col-md-3">
                                  <div class="card stat-card-large bg-success bg-opacity-10">
                                       <iconify-icon icon="solar:check-circle-outline" class="fs-32 text-success"></iconify-icon>
                                       <h3><?= number_format($stats['completedCases']) ?></h3>
                                       <p class="text-muted mb-0">Completed Cases</p>
                                  </div>
                             </div>
                             <div class="col-md-3">
                                  <div class="card stat-card-large bg-info bg-opacity-10">
                                       <iconify-icon icon="solar:users-group-rounded-outline" class="fs-32 text-info"></iconify-icon>
                                       <h3><?= number_format($stats['totalAgents']) ?></h3>
                                       <p class="text-muted mb-0">Active Agents</p>
                                  </div>
                             </div>
                             <div class="col-md-3">
                                  <div class="card stat-card-large bg-warning bg-opacity-10">
                                       <iconify-icon icon="solar:user-plus-rounded" class="fs-32 text-warning"></iconify-icon>
                                       <h3><?= number_format($stats['totalClients']) ?></h3>
                                       <p class="text-muted mb-0">New Clients</p>
                                  </div>
                             </div>
                        </div>

                        <!-- Charts Row -->
                        <div class="row mb-3">
                             <div class="col-md-8">
                                  <div class="card">
                                       <div class="card-header d-flex justify-content-between">
                                            <h5 class="mb-0">Monthly Trend</h5>
                                            <select class="form-select form-select-sm" onchange="location.href='reports.php?type=overview&period='+this.value">
                                                 <option value="month" <?= $period === 'month' ? 'selected' : '' ?>>This Month</option>
                                                 <option value="quarter" <?= $period === 'quarter' ? 'selected' : '' ?>>This Quarter</option>
                                                 <option value="year" <?= $period === 'year' ? 'selected' : '' ?>>This Year</option>
                                            </select>
                                       </div>
                                       <div class="card-body">
                                            <canvas id="trendChart" height="100"></canvas>
                                       </div>
                                  </div>
                             </div>
                             <div class="col-md-4">
                                  <div class="card">
                                       <div class="card-header">
                                            <h5 class="mb-0">Cases by Type</h5>
                                       </div>
                                       <div class="card-body">
                                            <canvas id="typeChart" height="200"></canvas>
                                       </div>
                                  </div>
                             </div>
                        </div>

                        <!-- Top Agents -->
                        <div class="card">
                             <div class="card-header">
                                  <h5 class="mb-0">Top Performing Agents</h5>
                             </div>
                             <div class="card-body">
                                  <div class="table-responsive">
                                       <table class="table table-sm mb-0">
                                            <thead>
                                                 <tr>
                                                      <th>Agent</th>
                                                      <th>Cases</th>
                                                      <th>Completed</th>
                                                      <th>Earnings</th>
                                                 </tr>
                                            </thead>
                                            <tbody>
                                                 <?php while ($agent = mysqli_fetch_assoc($agentPerformance)): ?>
                                                      <tr>
                                                           <td>
                                                                <strong><?= htmlspecialchars($agent['fullname']) ?></strong>
                                                           </td>
                                                           <td><?= $agent['case_count'] ?></td>
                                                           <td>
                                                                <span class="badge bg-success"><?= $agent['completed_cases'] ?></span>
                                                           </td>
                                                           <td>$<?= number_format($agent['total_commissions'], 2) ?></td>
                                                      </tr>
                                                 <?php endwhile; ?>
                                            </tbody>
                                       </table>
                                  </div>
                             </div>
                        </div>

                    <?php elseif ($reportType === 'agents'): ?>
                        <!-- Agent Performance Report -->
                        <div class="card">
                             <div class="card-header">
                                  <h5 class="mb-0">Agent Performance Report</h5>
                             </div>
                             <div class="card-body">
                                  <div class="table-responsive">
                                       <table class="table table-striped mb-0">
                                            <thead>
                                                 <tr>
                                                      <th>Agent</th>
                                                      <th>Contact</th>
                                                      <th>Cases</th>
                                                      <th>Active/Pending/Completed</th>
                                                      <th>Clients</th>
                                                      <th>Earnings</th>
                                                      <th>Status</th>
                                                 </tr>
                                            </thead>
                                            <tbody>
                                                 <?php while ($agent = mysqli_fetch_assoc($agentReport)): ?>
                                                      <tr>
                                                           <td>
                                                                <strong><?= htmlspecialchars($agent['fullname']) ?></strong><br>
                                                                <small class="text-muted">Since: <?= date('M Y', strtotime($agent['joined_date'])) ?></small>
                                                           </td>
                                                           <td>
                                                                <?= htmlspecialchars($agent['email']) ?><br>
                                                                <?= $agent['phone'] ? htmlspecialchars($agent['phone']) : '-' ?>
                                                           </td>
                                                           <td><?= $agent['total_cases'] ?></td>
                                                           <td>
                                                                <span class="badge bg-info"><?= $agent['active_cases'] ?> Active</span>
                                                                <span class="badge bg-warning"><?= $agent['pending_cases'] ?> Pending</span>
                                                                <span class="badge bg-success"><?= $agent['completed_cases'] ?> Completed</span>
                                                           </td>
                                                           <td><?= $agent['total_clients'] ?></td>
                                                           <td>
                                                                <strong>$<?= number_format($agent['total_earned'], 2) ?></strong><br>
                                                                <small class="text-warning">Pending: $<?= number_format($agent['pending_commission'], 2) ?></small>
                                                           </td>
                                                           <td>
                                                                <span class="badge bg-<?= $agent['status'] === 'verified' ? 'success' : 'secondary' ?>">
                                                                     <?= ucfirst($agent['status']) ?>
                                                                </span>
                                                           </td>
                                                      </tr>
                                                 <?php endwhile; ?>
                                            </tbody>
                                       </table>
                                  </div>
                             </div>
                        </div>

                    <?php elseif ($reportType === 'cases'): ?>
                        <!-- Case Analysis Report -->
                        <div class="card">
                             <div class="card-header">
                                  <h5 class="mb-0">Case Analysis (Last 12 Months)</h5>
                             </div>
                             <div class="card-body">
                                  <canvas id="caseAnalysisChart" height="100"></canvas>
                             </div>
                        </div>

                    <?php elseif ($reportType === 'financial'): ?>
                        <!-- Financial Report -->
                        <div class="row mb-3">
                             <div class="col-md-3">
                                  <div class="card bg-success bg-opacity-10">
                                       <div class="card-body text-center">
                                            <h6>Paid Amount</h6>
                                            <h3>$<?= number_format($financialSummary['paid_amount'], 2) ?></h3>
                                            <small><?= $financialSummary['paid_count'] ?> transactions</small>
                                       </div>
                                  </div>
                             </div>
                             <div class="col-md-3">
                                  <div class="card bg-warning bg-opacity-10">
                                       <div class="card-body text-center">
                                            <h6>Pending Amount</h6>
                                            <h3>$<?= number_format($financialSummary['pending_amount'], 2) ?></h3>
                                            <small><?= $financialSummary['pending_count'] ?> transactions</small>
                                       </div>
                                  </div>
                             </div>
                             <div class="col-md-3">
                                  <div class="card bg-info bg-opacity-10">
                                       <div class="card-body text-center">
                                            <h6>Total (Paid + Pending)</h6>
                                            <h3>$<?= number_format($financialSummary['paid_amount'] + $financialSummary['pending_amount'], 2) ?></h3>
                                       </div>
                                  </div>
                             </div>
                             <div class="col-md-3">
                                  <div class="card">
                                       <div class="card-body text-center">
                                            <h6>Period</h6>
                                            <h5><?= date('M d, Y', strtotime($startDate)) ?> - <?= date('M d, Y', strtotime($endDate)) ?></h5>
                                       </div>
                                  </div>
                             </div>
                        </div>

                        <div class="card">
                             <div class="card-header">
                                  <h5 class="mb-0">Daily Financial Breakdown</h5>
                             </div>
                             <div class="card-body">
                                  <canvas id="financialChart" height="80"></canvas>
                             </div>
                        </div>
                    <?php endif; ?>

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

     <script src="assets/js/vendor.min.js"></script>
     <script src="assets/js/app.js"></script>

     <?php if ($reportType === 'overview'): ?>
     <script>
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Cases',
                    data: [<?= implode(',', array_column($monthlyTrend, 'cases')) ?>],
                    borderColor: '#0F4C75',
                    tension: 0.4,
                    fill: false
                }, {
                    label: 'Revenue ($)',
                    data: [<?= implode(',', array_column($monthlyTrend, 'revenue')) ?>],
                    borderColor: '#D4A853',
                    tension: 0.4,
                    fill: false
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });

        const typeCtx = document.getElementById('typeChart').getContext('2d');
        const typeLabels = [];
        const typeData = [];
        <?php
        mysqli_data_seek($casesByType, 0);
        while ($row = mysqli_fetch_assoc($casesByType)) {
            echo "typeLabels.push('{$row['case_type']}');\n";
            echo "typeData.push({$row['count']});\n";
        }
        ?>
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: typeLabels,
                datasets: [{
                    data: typeData,
                    backgroundColor: ['#0F4C75', '#3282B8', '#D4A853', '#E8C97A', '#6c757d']
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
     </script>
     <?php elseif ($reportType === 'cases'): ?>
     <script>
        const caseCtx = document.getElementById('caseAnalysisChart').getContext('2d');
        new Chart(caseCtx, {
            type: 'bar',
            data: {
                labels: ['<?= implode('","', array_unique(array_column(mysqli_fetch_all($caseStats), 'month'))) ?>'],
                datasets: [
                    { label: 'Study Abroad', data: [], backgroundColor: '#0F4C75' },
                    { label: 'Visa', data: [], backgroundColor: '#3282B8' },
                    { label: 'Travel', data: [], backgroundColor: '#D4A853' }
                ]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } }, scales: { x: { stacked: true }, y: { stacked: true } } }
        });
     </script>
     <?php elseif ($reportType === 'financial'): ?>
     <script>
        const finCtx = document.getElementById('financialChart').getContext('2d');
        new Chart(finCtx, {
            type: 'bar',
            data: {
                labels: ['<?= implode('","', array_unique(array_column(mysqli_fetch_all($financialData), 'date'))) ?>'],
                datasets: [
                    { label: 'Paid', data: [], backgroundColor: '#198754' },
                    { label: 'Pending', data: [], backgroundColor: '#ffc107' }
                ]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
     </script>
     <?php endif; ?>

</body>
</html>

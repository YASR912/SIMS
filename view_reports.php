<?php
include 'auth.php';
include 'head.php';

// ── Predictions from Python output (JSON) ──
$predictions_file = 'predictions/predicted_restock.json';
$dashboard_data = [];
if (file_exists($predictions_file)) {
    $dashboard_data = json_decode(file_get_contents($predictions_file), true);
}

// ── KPI 1: Total Inventory Items (real DB) ──
$totalItemsResult = mysqli_query($con, "SELECT COUNT(*) AS count FROM inventoryitem WHERE is_deleted = 0");
$totalItemsDB = (int)mysqli_fetch_assoc($totalItemsResult)['count'];

// ── KPI 2: Restock Alerts — low stock items (real DB) ──
$restockResult = mysqli_query($con, "SELECT COUNT(*) AS count FROM inventoryitem WHERE Quantity < 10 AND is_deleted = 0");
$restockAlertsDB = (int)mysqli_fetch_assoc($restockResult)['count'];

// ── KPI 3: Predicted Sales next 30 days (sum from predictions JSON) ──
$predictedSalesTotal = 0;
if (!empty($dashboard_data)) {
    foreach ($dashboard_data as $row) {
        $predictedSalesTotal += isset($row['PredictedQuantity']) ? (int)$row['PredictedQuantity'] : 0;
    }
}

// ── Report files ──
$requestedReportType = isset($_GET['reportType']) ? $_GET['reportType'] : null;
$reportDirectory = 'reports/';
if (!is_dir($reportDirectory)) {
    mkdir($reportDirectory, 0777, true);
}
$reportFiles = array_diff(scandir($reportDirectory), ['.', '..']);
$filteredReportFiles = [];
if ($requestedReportType) {
    foreach ($reportFiles as $reportFile) {
        if (strpos($reportFile, $requestedReportType) !== false) {
            $filteredReportFiles[] = $reportFile;
        }
    }
} else {
    $filteredReportFiles = array_slice($reportFiles, 0, 5);
}

// ── Inventory Status for Distribution Chart (real DB) ──
$distResult = mysqli_query($con, "
    SELECT 
        SUM(CASE WHEN Quantity = 0 THEN 1 ELSE 0 END)              AS out_of_stock,
        SUM(CASE WHEN Quantity BETWEEN 1 AND 10 THEN 1 ELSE 0 END) AS low_stock,
        SUM(CASE WHEN Quantity > 10 THEN 1 ELSE 0 END)             AS in_stock
    FROM InventoryItem WHERE is_deleted = 0
");
$distData = mysqli_fetch_assoc($distResult);
$distCritical  = (int)$distData['out_of_stock'];
$distModerate  = (int)$distData['low_stock'];
$distHealthy   = (int)$distData['in_stock'];

// ── Forecast Chart: Monthly predicted quantities from JSON ──
$forecastLabels = [];
$forecastValues = [];
if (!empty($dashboard_data)) {
    foreach ($dashboard_data as $row) {
        $forecastLabels[] = $row['ItemName'] ?? 'Unknown';
        $forecastValues[] = (int)($row['PredictedQuantity'] ?? 0);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Inventory Dashboard & Reports</title>
    <?php include 'head.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(to right, #6a11cb 0%, #2575fc 100%);
            --card-shadow: 0 4px 20px 0 rgba(0,0,0,0.05);
        }
        .content-wrapper { background: #f4f7fa; }
        .card { border: none; border-radius: 15px; box-shadow: var(--card-shadow); margin-bottom: 20px; }
        .card-title { font-weight: 700; color: #333; margin-bottom: 1.5rem; }
        .stat-card { transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .filter-section { background: white; padding: 20px; border-radius: 15px; margin-bottom: 25px; box-shadow: var(--card-shadow); }
        .chart-container { position: relative; height: 300px; width: 100%; }
        .report-list-item { border-left: 5px solid #2575fc; margin-bottom: 10px; transition: all 0.2s; }
        .report-list-item:hover { background: #f8f9fa; transform: translateX(5px); }
        .kpi-value { font-size: 2rem; font-weight: 800; margin-bottom: 0; }
        .kpi-label { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.8; }
        @media print { .no-print { display: none !important; } .main-panel { width: 100% !important; } }
    </style>
</head>
<body>
<div class="container-scroller">
    <?php include 'navBar.php'; ?>
    <div class="container-fluid page-body-wrapper">
        <?php include 'sidebar.php'; ?>
        <div class="main-panel">
            <div class="content-wrapper">

                <!-- Header -->
                <div class="page-header no-print">
                    <h3 class="page-title">
                        <span class="page-title-icon bg-gradient-primary text-white me-2">
                            <i class="mdi mdi-view-dashboard"></i>
                        </span>
                        Inventory Intelligence Dashboard
                    </h3>
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">
                                <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                            </li>
                        </ul>
                    </nav>
                </div>

                <!-- KPI Row — Real DB values via PHP -->
                <div class="row no-print">
                    <div class="col-md-3 stretch-card grid-margin">
                        <div class="card bg-gradient-danger card-img-holder text-white stat-card">
                            <div class="card-body">
                                <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Total Items <i class="mdi mdi-cube-outline mdi-24px float-right"></i></h4>
                                <h2 class="kpi-value"><?php echo $totalItemsDB; ?></h2>
                                <p class="kpi-label">In Inventory</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white stat-card">
                            <div class="card-body">
                                <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Restock Alerts <i class="mdi mdi-alert-outline mdi-24px float-right"></i></h4>
                                <h2 class="kpi-value"><?php echo $restockAlertsDB; ?></h2>
                                <p class="kpi-label">Immediate Action</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 stretch-card grid-margin">
                        <div class="card bg-gradient-success card-img-holder text-white stat-card">
                            <div class="card-body">
                                <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Predicted Restock <i class="mdi mdi-chart-line mdi-24px float-right"></i></h4>
                                <h2 class="kpi-value"><?php echo number_format($predictedSalesTotal); ?></h2>
                                <p class="kpi-label">Total Units Needed</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 stretch-card grid-margin">
                        <div class="card bg-gradient-primary card-img-holder text-white stat-card">
                            <div class="card-body">
                                <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Reports Generated <i class="mdi mdi-file-check mdi-24px float-right"></i></h4>
                                <h2 class="kpi-value"><?php echo count($reportFiles); ?></h2>
                                <p class="kpi-label">Total History</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row no-print">
                    <div class="col-lg-7 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h4 class="card-title">Inventory Forecast Trends</h4>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                type="button" id="chartFilter"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            This Month
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="chartFilter">
                                            <li><a class="dropdown-item" href="#" onclick="changeFilter('day')">Today</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="changeFilter('month')">This Month</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="changeFilter('year')">This Year</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="chart-container">
                                    <canvas id="forecastChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Stock Distribution</h4>
                                <div class="chart-container">
                                    <canvas id="distributionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prediction Table -->
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="card-title">Predictive Analysis Details</h4>
                                    <div class="no-print">
                                        <button class="btn btn-sm btn-gradient-primary" onclick="window.print()">
                                            <i class="mdi mdi-printer"></i> Export Dashboard
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover" id="predictionTable">
                                        <thead>
                                            <tr>
                                                <th>Item Name</th>
                                                <th>Predicted Restock Date</th>
                                                <th>Quantity Needed</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($dashboard_data)): ?>
                                                <?php foreach ($dashboard_data as $row): ?>
                                                    <tr>
                                                        <td><strong><?php echo htmlspecialchars($row['ItemName']); ?></strong></td>
                                                        <td><?php echo htmlspecialchars($row['PredictedRestockDate']); ?></td>
                                                        <td>
                                                            <?php
                                                                $percent = min(100, ($row['PredictedQuantity'] / 500) * 100);
                                                                $color = $percent > 70 ? 'bg-danger' : ($percent > 40 ? 'bg-warning' : 'bg-success');
                                                            ?>
                                                            <div class="progress" style="height: 10px; width: 100px;">
                                                                <div class="progress-bar <?php echo $color; ?>" role="progressbar" style="width: <?php echo $percent; ?>%"></div>
                                                            </div>
                                                            <small><?php echo $row['PredictedQuantity']; ?> units</small>
                                                        </td>
                                                        <td>
                                                            <?php if ($row['PredictedQuantity'] > 100): ?>
                                                                <label class="badge badge-danger">Critical</label>
                                                            <?php else: ?>
                                                                <label class="badge badge-info">Stable</label>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="4" class="text-center">No predictive data available. Run analysis first.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historical Reports -->
                <div class="row no-print">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Historical Text Reports</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="list-group">
                                            <?php foreach ($filteredReportFiles as $file): ?>
                                                <a href="?reportFile=<?php echo urlencode($file); ?>" class="list-group-item list-group-item-action report-list-item">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h6 class="mb-1 text-truncate"><?php echo htmlspecialchars($file); ?></h6>
                                                    </div>
                                                    <small class="text-muted">Generated: <?php echo date("F d, Y", filemtime($reportDirectory . $file)); ?></small>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <?php
                                        $selectedFile = isset($_GET['reportFile']) ? $_GET['reportFile'] : (count($filteredReportFiles) > 0 ? $filteredReportFiles[0] : null);
                                        if ($selectedFile && file_exists($reportDirectory . $selectedFile)):
                                            $content = file_get_contents($reportDirectory . $selectedFile);
                                        ?>
                                            <div class="p-3 border rounded bg-light" style="max-height: 400px; overflow-y: auto;">
                                                <h6>Content of: <?php echo htmlspecialchars($selectedFile); ?></h6>
                                                <hr>
                                                <pre><?php echo htmlspecialchars($content); ?></pre>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center p-5 border rounded bg-light">
                                                <i class="mdi mdi-file-find mdi-48px text-muted"></i>
                                                <p>Select a report from the list to view its contents.</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <?php include 'footer.php'; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Forecast Chart data from PHP/JSON predictions ──
    const allLabels = <?php echo json_encode($forecastLabels); ?>;
    const allValues = <?php echo json_encode($forecastValues); ?>;

    let forecastChart = null;

    function loadChart(labels, values) {
        const ctx = document.getElementById('forecastChart').getContext('2d');
        if (forecastChart) forecastChart.destroy();
        forecastChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Predicted Quantity Needed',
                    data: values,
                    backgroundColor: 'rgba(106, 17, 203, 0.7)',
                    borderColor: 'rgba(106, 17, 203, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // Filter slices prediction data by count
    window.changeFilter = function(type) {
        const btn = document.getElementById('chartFilter');
        let sliceCount;
        if (type === 'day')   { btn.innerText = 'Today';      sliceCount = 3; }
        if (type === 'month') { btn.innerText = 'This Month'; sliceCount = 7; }
        if (type === 'year')  { btn.innerText = 'This Year';  sliceCount = allLabels.length; }
        loadChart(allLabels.slice(0, sliceCount), allValues.slice(0, sliceCount));
    };

    // Default load — This Month
    loadChart(allLabels.slice(0, 7), allValues.slice(0, 7));

    // ── Distribution Chart — Real DB data via PHP ──
    new Chart(document.getElementById('distributionChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Out of Stock', 'Low Stock (1–10)', 'In Stock (>10)'],
            datasets: [{
                data: [
                    <?php echo $distCritical; ?>,
                    <?php echo $distModerate; ?>,
                    <?php echo $distHealthy; ?>
                ],
                backgroundColor: ['#fe7c96', '#f6e384', '#1bcfb4']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
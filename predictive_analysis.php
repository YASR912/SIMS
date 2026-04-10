<?php 
include 'auth.php';
include 'head.php';

$analysis_message = "";
$analysis_status = "";

if (isset($_POST['run_analysis'])) {
    $output = [];
    $return_var = 0;
    exec('python3 data_analysis.py 2>&1', $output, $return_var);
    if ($return_var === 0) {
        $analysis_message = "Analysis completed successfully!";
        $analysis_status = "success";
    } else {
        $analysis_message = "Analysis failed. Error: " . implode("\n", $output);
        $analysis_status = "danger";
    }
}

$decisions_result = $con->query("
    SELECT i.ItemName, pr.*
    FROM predictions pr
    JOIN inventoryitem i ON pr.product_id = i.ItemID
    ORDER BY pr.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Predictive Analysis</title>
  <style>
    .loading-overlay {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(255, 255, 255, 0.7);
      z-index: 9999;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }
    .spinner-border { width: 3rem; height: 3rem; }
  </style>
  <script>
    function showLoading() {
      document.getElementById('loadingOverlay').style.display = 'flex';
      return true;
    }
  </script>
</head>
<body>
  <div id="loadingOverlay" class="loading-overlay">
    <div class="spinner-border text-primary" role="status"></div>
    <h4 class="mt-3">Running Predictive Analysis...</h4>
    <p>This may take a few moments depending on your inventory size.</p>
  </div>

  <div class="container-scroller">
    <?php include 'navBar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">

          <div class="page-header">
            <h3 class="page-title">
              <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-chart-line"></i>
              </span>
              Predictive Analysis
            </h3>
          </div>

          <?php if ($analysis_message): ?>
          <div class="alert alert-<?= $analysis_status ?> alert-dismissible fade show" role="alert">
            <strong><?= $analysis_status == 'success' ? 'Success!' : 'Error!'; ?></strong>
            <?= nl2br(htmlspecialchars($analysis_message)); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          <?php endif; ?>

          <!-- Inventory Forecasting Card -->
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Inventory Forecasting</h4>
                    <form method="post" onsubmit="return showLoading();">
                      <button type="submit" name="run_analysis" class="btn btn-gradient-primary btn-icon-text">
                        <i class="mdi mdi-reload btn-icon-prepend"></i> Run Analysis for All Inventory
                      </button>
                    </form>
                  </div>

                  <?php
                  $predictions_file = 'predictions/predicted_restock.json';
                  if (file_exists($predictions_file)) {
                    echo "<hr/>";
                    echo "<div class='text-center mt-4'>";
                    if (file_exists('plots/sales_predictions.html')) {
                      echo "<h5>Sales Predictions</h5>";
                      echo "<iframe src='plots/sales_predictions.html' width='100%' height='500' style='border:none;'></iframe><br/><br/>";
                    }
                    if (file_exists('plots/cumulative_sales.html')) {
                      echo "<h5>Cumulative Sales Trend</h5>";
                      echo "<iframe src='plots/cumulative_sales.html' width='100%' height='500' style='border:none;'></iframe><br/><br/>";
                    }
                    if (file_exists('plots/sales_distribution.html')) {
                      echo "<h5>Sales Distribution by Product</h5>";
                      echo "<iframe src='plots/sales_distribution.html' width='100%' height='500' style='border:none;'></iframe><br/><br/>";
                    }
                    echo "</div>";

                    $predictions_json = file_get_contents($predictions_file);
                    $predictions = json_decode($predictions_json, true);

                    if (!empty($predictions)) {
                      echo "<h4 class='mb-4 mt-5 text-primary'>Predicted Restock Requirements</h4>";
                      echo "<div class='table-responsive'>";
                      echo "<table class='table table-hover table-bordered'>";
                      echo "<thead class='bg-light'><tr>
                          <th class='text-center'>Item Name</th>
                          <th class='text-center'>Predicted Restock Date</th>
                          <th class='text-center'>Predicted Quantity Needed</th>
                        </tr></thead><tbody>";
                      foreach ($predictions as $prediction) {
                        $qtyClass = $prediction['PredictedQuantity'] > 50 ? 'bg-warning' : 'bg-info';
                        echo "<tr class='align-middle'>
                            <td class='text-center'>" . htmlspecialchars($prediction['ItemName']) . "</td>
                            <td class='text-center'>" . htmlspecialchars($prediction['PredictedRestockDate']) . "</td>
                            <td class='text-center'><span class='badge $qtyClass'>" . htmlspecialchars($prediction['PredictedQuantity']) . "</span></td>
                          </tr>";
                      }
                      echo "</tbody></table></div>";
                      echo "<div class='mt-4'>";
                      echo "<form method='post' action='save_prediction.php' onsubmit='return confirm(\"Are you sure you want to save these predictions to the database?\");'>";
                      echo "<input type='hidden' name='data' value='" . htmlspecialchars($predictions_json) . "'>";
                      echo "<button type='submit' class='btn btn-success'>
                              <i class='mdi mdi-content-save'></i> Save Predictions to Database
                            </button>";
                      echo "</form></div>";
                    } else {
                      echo "<div class='alert alert-info'>No prediction data found in the results file.</div>";
                    }
                  } else {
                    echo "<div class='text-center py-5'>
                            <i class='mdi mdi-information-outline text-info' style='font-size:3rem;'></i>
                            <p class='mt-3'>No predictions file found. Click <strong>Run Analysis</strong> to generate forecasts.</p>
                          </div>";
                  }
                  ?>
                </div>
              </div>
            </div>
          </div>

          <!-- Inventory Decisions Card -->
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title mb-4">Inventory Decisions</h4>
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                      <thead class="bg-light">
                        <tr>
                          <th class="text-center">Product</th>
                          <th class="text-center">Avg Daily Sales</th>
                          <th class="text-center">Safety Stock</th>
                          <th class="text-center">Reorder Point</th>
                          <th class="text-center">Current Stock</th>
                          <th class="text-center">Decision</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if ($decisions_result && $decisions_result->num_rows > 0): ?>
                          <?php while ($row = $decisions_result->fetch_assoc()): ?>
                          <tr class="align-middle">
                            <td class="text-center"><?= $row['ItemName'] ?></td>
                            <td class="text-center"><?= round($row['avg_daily_sales'], 2) ?></td>
                            <td class="text-center"><?= $row['safety_stock'] ?></td>
                            <td class="text-center"><?= $row['reorder_point'] ?></td>
                            <td class="text-center"><?= $row['current_stock'] ?></td>
                            <td class="text-center">
                              <?php if ($row['decision'] == 'Reorder Now'): ?>
                                <span class="badge bg-danger">🔔 Reorder Now</span>
                              <?php elseif ($row['decision'] == 'Reorder Soon'): ?>
                                <span class="badge bg-warning text-dark">⚠️ Reorder Soon</span>
                              <?php else: ?>
                                <span class="badge bg-success">✅ Stock OK</span>
                              <?php endif; ?>
                            </td>
                          </tr>
                          <?php endwhile; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="6" class="text-center">No decisions data found. Run Analysis first.</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
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
</body>
</html>
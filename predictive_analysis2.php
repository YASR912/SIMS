<?php
include 'auth.php';
include 'head.php';

$result = $con->query("
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
  <title>Inventory Decisions</title>
</head>
<body>
<div class="container-scroller">
  <?php include 'navBar.php'; ?>
  <div class="container-fluid page-body-wrapper">
    <?php include 'sidebar.php'; ?>
    <div class="main-panel">
      <div class="content-wrapper">

        <div class="page-header">
          <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
              <i class="mdi mdi-clipboard-check"></i>
            </span>
            Inventory Decisions
          </h3>
        </div>

        <div class="row">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Predictive Inventory Decisions</h4>
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
                      <?php while ($row = $result->fetch_assoc()): ?>
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
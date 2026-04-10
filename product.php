<?php
include 'auth.php';
include 'head.php';

// Handle Image Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['product_image']) && isset($_POST['item_id'])) {
    $itemId = mysqli_real_escape_string($con, $_POST['item_id']);
    $targetDir = "uploads/products/";
    
    // Create directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = basename($_FILES["product_image"]["name"]);
    $targetFilePath = $targetDir . time() . "_" . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Allow certain file formats
    $allowTypes = array('jpg', 'png','webp','jfif', 'jpeg','avif', 'gif');
    if (in_array(strtolower($fileType), $allowTypes)) {
        // Upload file to server
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFilePath)) {
            // Update image path in database
            $updateQuery = "UPDATE InventoryItem SET Image = '$targetFilePath' WHERE ItemID = '$itemId'";
            if (mysqli_query($con, $updateQuery)) {
                $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
            } else {
                $statusMsg = "File upload failed, please try again.";
            }
        } else {
            $statusMsg = "Sorry, there was an error uploading your file.";
        }
    } else {
        $statusMsg = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.';
    }
    // Redirect to avoid form resubmission
    header("Location: product.php?status=" . urlencode($statusMsg));
    exit;
}

// Product and Sales details
$productQuery = "
    SELECT i.ItemID, i.ItemName, i.Category, i.Quantity, i.Price, i.Image, COALESCE(SUM(s.QuantitySold), 0) AS TotalSold, COALESCE(SUM(s.TotalPrice), 0) AS TotalRevenue
    FROM InventoryItem i
    LEFT JOIN SalesTransaction s ON i.ItemID = s.ItemID
    WHERE i.is_deleted = 0
    GROUP BY i.ItemID
    ORDER BY i.ItemName ASC
";
$productResult = mysqli_query($con, $productQuery);

// Data for charts and display
$products = [];
while ($row = mysqli_fetch_assoc($productResult)) {
    $products[] = $row;
}

// Sort products by TotalRevenue in descending order
usort($products, function($a, $b) {
    return $b['TotalRevenue'] <=> $a['TotalRevenue'];
});

// Split products into top 3 and the rest
$topProducts = array_slice($products, 0, 3);
$restProducts = array_slice($products, 3);

// Prepare data for charts
$productNames = [];
$totalSold = [];
$quantityLeft = [];
$totalRevenue = [];

foreach ($products as $product) {
    $productNames[] = $product['ItemName'];
    $totalSold[] = $product['TotalSold'];
    $quantityLeft[] = $product['Quantity'];
    $totalRevenue[] = $product['TotalRevenue'];
}

// Convert data arrays to JSON for chart.js
$productNamesJson = json_encode($productNames);
$totalSoldJson = json_encode($totalSold);
$quantityLeftJson = json_encode($quantityLeft);
$totalRevenueJson = json_encode($totalRevenue);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>Product and Sales Details</title>
    <!-- Bootstrap CSS (Assuming it's used based on classes) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<style>
    .card3:hover {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }
    .image-container {
        position: relative;
        display: inline-block;
    }
    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s;
        border-radius: 50%;
        cursor: pointer;
    }
    .image-container:hover .image-overlay {
        opacity: 1;
    }
</style>
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
                            <i class="mdi mdi-cube"></i>
                        </span>
                        Product and Sales Details
                    </h3>
                </div>

                <?php if (isset($_GET['status'])): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($_GET['status']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Product Overview Cards (Top 3 Most Revenue Products) -->
                <div class="row">
                    <?php foreach ($topProducts as $row): ?>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card card3 bg-gradient-light shadow">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="image-container" onclick="openUploadModal('<?php echo $row['ItemID']; ?>', '<?php echo htmlspecialchars($row['ItemName']); ?>')">
                                            <img src="<?php echo !empty($row['Image']) ? htmlspecialchars($row['Image']) : 'https://ui-avatars.com/api/?name='.urlencode($row['ItemName']).'&background=6a11cb&color=fff&size=80&rounded=true'; ?>" 
                                                 alt="<?php echo htmlspecialchars($row['ItemName']); ?>" 
                                                 class="img-fluid rounded-circle border border-primary p-2" 
                                                 style="width: 80px; height: 80px; object-fit: cover;">
                                            <div class="image-overlay">
                                                <i class="mdi mdi-camera"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3">
                                            <h4 class="card-title mb-0 text-primary"><?php echo htmlspecialchars($row['ItemName']); ?></h4>
                                            <p class="text-muted"><i class="mdi mdi-tag"></i> <?php echo htmlspecialchars($row['Category']); ?></p>
                                        </div>
                                    </div>
                                    <ul class="list-unstyled">
                                        <li class="mb-3 p-2 bg-light rounded d-flex justify-content-between align-items-center">
                                            <span><i class="mdi mdi-cash text-success"></i> Total Revenue:</span>
                                            <span class="text-success">₹<?php echo number_format($row['TotalRevenue'], 2); ?></span>
                                        </li>
                                        <li class="mb-3 p-2 bg-light rounded d-flex justify-content-between align-items-center">
                                            <span><i class="mdi mdi-chart-line text-info"></i> Total Sold:</span>
                                            <span class="text-info"><?php echo htmlspecialchars($row['TotalSold']); ?></span>
                                        </li>
                                        <li class="p-2 bg-light rounded d-flex justify-content-between align-items-center">
                                            <span><i class="mdi mdi-cube-outline text-warning"></i> Quantity Left:</span>
                                            <span class="text-warning"><?php echo htmlspecialchars($row['Quantity']); ?></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Rest of the Products in Table Format -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h4 class="card-title mb-4">
                                    <i class="mdi mdi-format-list-bulleted text-primary"></i>
                                    Other Products Performance
                                </h4>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="bg-gradient-primary text-white">
                                            <tr>
                                                <th class="align-middle text-center">Image</th>
                                                <th class="align-middle">Product Name</th>
                                                <th class="align-middle text-center">
                                                    <i class="mdi mdi-chart-line"></i> Total Sold
                                                </th>
                                                <th class="align-middle text-center">
                                                    <i class="mdi mdi-cube-outline"></i> In Stock
                                                </th>
                                                <th class="align-middle text-center">
                                                    <i class="mdi mdi-currency-inr"></i> Revenue
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($restProducts as $product): ?>
                                                <tr class="align-middle">
                                                    <td class="text-center">
                                                        <div class="image-container" onclick="openUploadModal('<?php echo $product['ItemID']; ?>', '<?php echo htmlspecialchars($product['ItemName']); ?>')">
                                                            <img src="<?= !empty($product['Image']) ? htmlspecialchars($product['Image']) : 'https://ui-avatars.com/api/?name='.urlencode($product['ItemName']).'&background=6a11cb&color=fff&size=80&rounded=true' ?>"
                                                                 class="rounded-circle border border-primary p-1"
                                                                 style="width:80px;height:80px;object-fit:cover;"
                                                                 onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($product['ItemName']) ?>&background=6a11cb&color=fff&size=80&rounded=true'">
                                                            <div class="image-overlay">
                                                                <i class="mdi mdi-camera"></i>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php echo htmlspecialchars($product['ItemName']); ?>
                                                        <br>
                                                        <small class="text-muted"><?php echo htmlspecialchars($product['Category']); ?></small>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-info">
                                                            <?php echo $product['TotalSold']; ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-warning">
                                                            <?php echo $product['Quantity']; ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center text-success">
                                                        ₹<?php echo number_format($product['TotalRevenue'], 2); ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Summary Charts -->
                <div class="row mt-4">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card shadow-lg border-0 rounded-lg">
                            <div class="card-body bg-gradient-light">
                                <h4 class="card-title mb-4">
                                    <i class="mdi mdi-chart-bar text-primary me-2"></i>
                                    Total Items Sold
                                </h4>
                                <div class="p-3 bg-white rounded shadow-sm">
                                    <canvas id="totalSoldChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card shadow-lg border-0 rounded-lg">
                            <div class="card-body bg-gradient-light">
                                <h4 class="card-title mb-4">
                                    <i class="mdi mdi-package-variant text-warning me-2"></i>
                                    Quantity Left in Inventory
                                </h4>
                                <div class="p-3 bg-white rounded shadow-sm">
                                    <canvas id="quantityLeftChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card shadow-lg border-0 rounded-lg">
                            <div class="card-body bg-gradient-light">
                                <h4 class="card-title mb-4">
                                    <i class="mdi mdi-chart-line text-success me-2"></i>
                                    Revenue Analysis
                                </h4>
                                <div class="p-4 bg-white rounded shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted">Product Revenue Distribution</span>
                                        <div class="badge bg-success px-3 py-2">
                                            <i class="mdi mdi-currency-inr"></i>
                                            Total Revenue: ₹<?php echo number_format(array_sum($totalRevenue), 2); ?>
                                        </div>
                                    </div>
                                    <canvas id="totalRevenueChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="product.php" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="uploadModalLabel">Update Product Image</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Updating image for: <strong id="modalProductName"></strong></p>
          <input type="hidden" name="item_id" id="modalItemId">
          <div class="mb-3">
            <label for="product_image" class="form-label">Select Image from Device</label>
            <input class="form-control" type="file" name="product_image" id="product_image" accept="image/*" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Upload Image</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function openUploadModal(id, name) {
        document.getElementById('modalItemId').value = id;
        document.getElementById('modalProductName').innerText = name;
        var myModal = new bootstrap.Modal(document.getElementById('uploadModal'));
        myModal.show();
    }

    // Chart.js - Total Items Sold
    var ctxTotalSold = document.getElementById('totalSoldChart').getContext('2d');
    new Chart(ctxTotalSold, {
        type: 'bar',
        data: {
            labels: <?php echo $productNamesJson; ?>,
            datasets: [{
                label: 'Total Items Sold',
                data: <?php echo $totalSoldJson; ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Chart.js - Quantity Left in Inventory
    var ctxQuantityLeft = document.getElementById('quantityLeftChart').getContext('2d');
    new Chart(ctxQuantityLeft, {
        type: 'bar',
        data: {
            labels: <?php echo $productNamesJson; ?>,
            datasets: [{
                label: 'Quantity Left',
                data: <?php echo $quantityLeftJson; ?>,
                backgroundColor: 'rgba(255, 206, 86, 0.7)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Chart.js - Total Revenue per Product
    var ctxTotalRevenue = document.getElementById('totalRevenueChart').getContext('2d');
    new Chart(ctxTotalRevenue, {
        type: 'line',
        data: {
            labels: <?php echo $productNamesJson; ?>,
            datasets: [{
                label: 'Total Revenue',
                data: <?php echo $totalRevenueJson; ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.4)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>

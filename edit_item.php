<?php include 'auth.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Smart Inventory</title>
  <?php include 'head.php'; ?>
</head>
<body>
<?php
$id = $_GET['id'];
$query = "SELECT * FROM InventoryItem WHERE ItemID = $id";
$result = mysqli_query($con, $query);
$item = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemName   = mysqli_real_escape_string($con, $_POST['itemName']);
    $category   = mysqli_real_escape_string($con, $_POST['category']);
    $quantity   = mysqli_real_escape_string($con, $_POST['quantity']);
    $location   = mysqli_real_escape_string($con, $_POST['location']);
    $price      = mysqli_real_escape_string($con, $_POST['price']);
    $supplierID = mysqli_real_escape_string($con, $_POST['supplierID']);
    $last_updated = date('Y-m-d H:i:s');

    // Handle image upload
    $imageName = $item['Image']; // keep old image by default

    if (!empty($_FILES['image']['name'])) {
        $uploadDir = 'assets/Images/InvonentryItems/';

        // Create folder if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array(strtolower($ext), $allowedExt)) {
            $imageName = 'item_' . $id . '_' . time() . '.' . $ext;
            $uploadPath = $uploadDir . $imageName;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                echo "<script>alert('Error uploading image.');</script>";
                $imageName = $item['Image'];
            }
        } else {
            echo "<script>alert('Invalid image format. Use JPG, PNG, or WEBP.');</script>";
            $imageName = $item['Image'];
        }
    }

    $imageName = mysqli_real_escape_string($con, $imageName);
    $query = "UPDATE InventoryItem SET 
                ItemName='$itemName', Category='$category', Quantity='$quantity', 
                Location='$location', Price='$price', SupplierID='$supplierID', 
                Image='$imageName', UpdatedAt='$last_updated' 
              WHERE ItemID = $id";

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Item updated successfully!'); window.location.href = 'inventory.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}
?>

<div class="container-scroller">
  <?php include 'navBar.php'; ?>
  <div class="container-fluid page-body-wrapper">
    <?php include 'sidebar.php'; ?>
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="page-header">
          <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
              <i class="mdi mdi-view-list"></i>
            </span>
            Inventory Management
          </h3>
        </div>

        <div class="row justify-content-center">
          <div class="col-md-8">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title mb-4">Edit Inventory Item</h4>

                <!-- Current Image -->
                <div class="text-center mb-4">
                  <?php
                  $imgPath = 'assets/Images/InvonentryItems/' . $item['Image'];
                  $src = (!empty($item['Image']) && file_exists($imgPath))
                       ? $imgPath
                       : 'https://ui-avatars.com/api/?name=' . urlencode($item['ItemName']) . '&background=6a11cb&color=fff&size=120&rounded=true';
                  ?>
                  <img id="previewImg" src="<?= htmlspecialchars($src) ?>"
                       class="rounded-circle border border-primary"
                       style="width:120px;height:120px;object-fit:cover;">
                  <p class="text-muted mt-2" style="font-size:0.85rem;">Current Image</p>
                </div>

                <form action="edit_item.php?id=<?= $item['ItemID'] ?>" method="POST" enctype="multipart/form-data">
                  
                  <!-- Image Upload -->
                  <div class="form-group mb-3">
                    <label class="fw-semibold">Product Image</label>
                    <input type="file" name="image" id="imageInput" class="form-control" accept="image/*"
                           onchange="previewImage(this)">
                    <small class="text-muted">JPG, PNG, WEBP — leave empty to keep current image</small>
                  </div>

                  <div class="form-group mb-3">
                    <label>Item Name</label>
                    <input type="text" name="itemName" class="form-control" value="<?= htmlspecialchars($item['ItemName']) ?>" required>
                  </div>
                  <div class="form-group mb-3">
                    <label>Category</label>
                    <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($item['Category']) ?>" required>
                  </div>
                  <div class="form-group mb-3">
                    <label>Quantity</label>
                    <input type="number" name="quantity" class="form-control" value="<?= htmlspecialchars($item['Quantity']) ?>" required>
                  </div>
                  <div class="form-group mb-3">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($item['Location']) ?>" required>
                  </div>
                  <div class="form-group mb-3">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($item['Price']) ?>" required>
                  </div>
                  <div class="form-group mb-4">
                    <label>Supplier ID</label>
                    <input type="number" name="supplierID" class="form-control" value="<?= htmlspecialchars($item['SupplierID']) ?>">
                  </div>

                  <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-gradient-primary">
                      <i class="mdi mdi-content-save me-1"></i> Update Item
                    </button>
                    <a href="inventory.php" class="btn btn-secondary">Cancel</a>
                  </div>
                </form>
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
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('previewImg').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>
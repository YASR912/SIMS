<?php
include 'auth.php';
include 'head.php';

$SupplierID = $_GET['id'];
$query = "SELECT * FROM Supplier WHERE SupplierID = ? AND is_deleted = 0";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $SupplierID);
$stmt->execute();
$result = $stmt->get_result();
$supplier = $result->fetch_assoc();

if (isset($_POST['edit_supplier'])) {
    $SupplierName = mysqli_real_escape_string($con, $_POST['SupplierName']);
    $ContactPerson = mysqli_real_escape_string($con, $_POST['ContactPerson']);
    $ContactEmail = mysqli_real_escape_string($con, $_POST['ContactEmail']);
    $ContactPhone = mysqli_real_escape_string($con, $_POST['ContactPhone']);
    $Address = mysqli_real_escape_string($con, $_POST['Address']);
    $userID = $_SESSION['user_id']; 
    $role = $_SESSION['role'];

    // To update supplier data
    $updateQuery = "UPDATE Supplier 
                    SET SupplierName = ?, ContactPerson = ?, ContactEmail = ?, ContactPhone = ?, Address = ?, UpdatedAt = NOW() 
                    WHERE SupplierID = ?";
    $stmtUpdate = $con->prepare($updateQuery);
    $stmtUpdate->bind_param("sssssi", $SupplierName, $ContactPerson, $ContactEmail, $ContactPhone, $Address, $SupplierID);

    if ($stmtUpdate->execute()) {
        // Log the action in supplier_activity_log
        $logQuery = "INSERT INTO supplier_activity_log (supplier_id, action, performed_by, role)
                     VALUES (?, 'Updated', ?, ?)";
        $stmtLog = $con->prepare($logQuery);
        $stmtLog->bind_param("iis", $SupplierID, $userID, $role);
        $stmtLog->execute();
        $stmtLog->close();

        $_SESSION['message'] = "Supplier updated successfully!";
        header('Location: supplier.php');
        exit();
    } else {
        $_SESSION['error'] = "Failed to update supplier!";
    }
    $stmtUpdate->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Supplier</title>
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
                <i class="mdi mdi-truck menu-icon"></i>
              </span>
              Edit Supplier
            </h3>
          </div>

          <div class="container">
            <?php
            if (isset($_SESSION['error'])) {
              echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
              unset($_SESSION['error']);
            }
            ?>

            <form method="POST" action="edit_supplier.php?id=<?php echo $SupplierID; ?>">
              <div class="form-group">
                <label for="SupplierName">Supplier Name</label>
                <input type="text" name="SupplierName" class="form-control" value="<?php echo $supplier['SupplierName']; ?>" required>
              </div>
              <div class="form-group">
                <label for="ContactPerson">Contact Person</label>
                <input type="text" name="ContactPerson" class="form-control" value="<?php echo $supplier['ContactPerson']; ?>" required>
              </div>
              <div class="form-group">
                <label for="ContactEmail">Contact Email</label>
                <input type="email" name="ContactEmail" class="form-control" value="<?php echo $supplier['ContactEmail']; ?>" required>
              </div>
              <div class="form-group">
                <label for="ContactPhone">Contact Phone</label>
                <input type="text" name="ContactPhone" class="form-control" value="<?php echo $supplier['ContactPhone']; ?>" required>
              </div>
              <div class="form-group">
                <label for="Address">Address</label>
                <textarea name="Address" class="form-control" required><?php echo $supplier['Address']; ?></textarea>
              </div>
              <button type="submit" name="edit_supplier" class="btn btn-primary">Update Supplier</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include 'footer.php'; ?>
</body>
</html>

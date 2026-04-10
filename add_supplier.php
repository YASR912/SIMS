<?php
include 'auth.php'; 
include 'head.php'; 

if (isset($_POST['add_supplier'])) {
  // Escape user inputs for security
  $SupplierName = mysqli_real_escape_string($con, $_POST['SupplierName']);
  $ContactPerson = mysqli_real_escape_string($con, $_POST['ContactPerson']);
  $ContactEmail = mysqli_real_escape_string($con, $_POST['ContactEmail']);
  $ContactPhone = mysqli_real_escape_string($con, $_POST['ContactPhone']);
  $Address = mysqli_real_escape_string($con, $_POST['Address']);
  $userID = 1;  // Default user ID
  $role = $_SESSION['role']; // Get user role from session

  // Insert supplier data into the Supplier table
  $query = "INSERT INTO Supplier (SupplierName, ContactPerson, ContactEmail, ContactPhone, Address, CreatedAt, UpdatedAt)
        VALUES ('$SupplierName', '$ContactPerson', '$ContactEmail', '$ContactPhone', '$Address', NOW(), NOW())";

  if (mysqli_query($con, $query)) {
    // Get the ID of the newly inserted supplier
    $supplierID = mysqli_insert_id($con);

    // Log the action in the supplier_activity_log table
    $logQuery = "INSERT INTO supplier_activity_log (supplier_id, action, performed_by, role, timestamp)
           VALUES ('$supplierID', 'Added', '$userID', '$role', NOW())";
    mysqli_query($con, $logQuery);

    // Set success message and redirect to supplier list page
    $_SESSION['message'] = "Supplier added successfully!";
    header('Location: supplier.php');
    exit();
  } else {
    // Set error message if the query fails
    $_SESSION['error'] = "Failed to add supplier!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Supplier</title>
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
              Add New Supplier
            </h3>
          </div>

          <div class="container">
            <?php
            if (isset($_SESSION['error'])) {
              echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
              unset($_SESSION['error']);
            }
            ?>

            <form method="POST" action="add_supplier.php">
              <div class="form-group">
                <label for="SupplierName">Supplier Name</label>
                <input type="text" name="SupplierName" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="ContactPerson">Contact Person</label>
                <input type="text" name="ContactPerson" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="ContactEmail">Contact Email</label>
                <input type="email" name="ContactEmail" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="ContactPhone">Contact Phone</label>
                <input type="text" name="ContactPhone" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="Address">Address</label>
                <textarea name="Address" class="form-control" required></textarea>
              </div>
              <button type="submit" name="add_supplier" class="btn btn-primary">Add Supplier</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include 'footer.php'; ?>
</body>
</html>

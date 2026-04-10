<?php
include 'auth.php';
include 'head.php';

if (isset($_GET['id'])) {
    $SupplierID = $_GET['id'];
    $userID = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    // Log the restore action
    $logQuery = "INSERT INTO supplier_activity_log (supplier_id, action, performed_by, role)
                 VALUES (?, 'Restored', ?, ?)";
    $stmtLog = $con->prepare($logQuery);
    $stmtLog->bind_param("iis", $SupplierID, $userID, $role);
    $stmtLog->execute();
    $stmtLog->close();

    // To restore the supplier
    $query = "UPDATE Supplier SET is_deleted = 0 WHERE SupplierID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $SupplierID);

    if ($stmt->execute()) {
        echo "<script>alert('Supplier restored successfully!'); window.location.href='supplier.php';</script>";
        exit();
    } else {
        $_SESSION['error'] = "Failed to restore supplier!";
    }
    $stmt->close();
}
?>
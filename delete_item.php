<?php include 'head.php'; ?>
<?php include 'auth.php'; ?>

<?php
$id = $_GET['id'];

$query = "DELETE FROM InventoryItem WHERE ItemID = $id";

if (mysqli_query($con, $query)) {
    echo "<script>alert('Item deleted successfully!'); window.location.href = 'inventory.php';</script>";
} else {
    echo "<script>alert('Error deleting item: " . mysqli_error($con) . "');</script>";
}
?>

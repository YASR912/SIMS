<?php
$conn = new mysqli("localhost","root","","smart_inventory_db");

$result = $conn->query("
SELECT p.name, pr.reorder_point, pr.current_stock
FROM predictions pr
JOIN products p ON pr.product_id = p.id
ORDER BY pr.created_at DESC
LIMIT 5
");

$labels = $rop = $stock = [];

while($row = $result->fetch_assoc()){
    $labels[] = $row['name'];
    $rop[] = $row['reorder_point'];
    $stock[] = $row['current_stock'];
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<canvas id="inventoryChart"></canvas>

<script>
const ctx = document.getElementById('inventoryChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [
            {
                label: 'Reorder Point (ROP)',
                data: <?= json_encode($rop) ?>,
                backgroundColor: 'rgba(255,99,132,0.7)'
            },
            {
                label: 'Current Stock',
                data: <?= json_encode($stock) ?>,
                backgroundColor: 'rgba(54,162,235,0.7)'
            }
        ]
    }
});
</script>
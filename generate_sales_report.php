<?php
session_start();

/* ====== Access Control ====== */
if (!isset($_SESSION['Role']) || !in_array($_SESSION['Role'], ['Admin', 'Manager'])) {
    http_response_code(403);
    exit('Access Denied');
}

/* ====== DB Connection ====== */
$conn = new mysqli("localhost", "root", "", "smart_inventory_db");
if ($conn->connect_error) {
    die("Database connection failed");
}

/* ====== Date Filter ====== */
$from = $_GET['from'] ?? null;
$to   = $_GET['to'] ?? null;

if (!$from || !$to) {
    die("Invalid date range");
}

/* ====== Query ====== */
$sql = "
    SELECT product_name, quantity, unit_price, total_price, sale_date
    FROM salestransaction
    WHERE sale_date BETWEEN ? AND ?
    ORDER BY sale_date ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $from, $to);
$stmt->execute();
$result = $stmt->get_result();

/* ====== Create Reports Folder ====== */
$reportDir = __DIR__ . "/reports";
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0777, true);
}

$filename = "sales_report_" . date("Ymd_His") . ".txt";
$filePath = $reportDir . "/" . $filename;

/* ====== Write Report ====== */
$file = fopen($filePath, "w");

fwrite($file, "SALES REPORT\n");
fwrite($file, "From: $from To: $to\n");
fwrite($file, str_repeat("=", 40) . "\n\n");

while ($row = $result->fetch_assoc()) {
    fwrite($file,
        "{$row['sale_date']} | {$row['product_name']} | Qty: {$row['quantity']} | Total: {$row['total_price']}\n"
    );
}

fclose($file);

/* ====== Response ====== */
echo json_encode([
    "success" => true,
    "file" => $filename
]);

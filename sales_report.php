<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
    <style>
        body { font-family: Arial; background:#f4f6f9; }
        .box {
            width: 500px;
            margin: 50px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }
        button {
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover { background:#45a049; }
    </style>
</head>
<body>

<div class="box">
    <h2>Generate Sales Report</h2>

    <form action="generate_sales_report.php" method="GET">
        <label>From Date & Time</label>
        <input type="datetime-local" name="from" required>

        <label>To Date & Time</label>
        <input type="datetime-local" name="to" required>

        <button type="submit">Generate Report</button>
    </form>
</div>

</body>
</html>

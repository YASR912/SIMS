<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db   = getenv('DB_NAME') ?: 'smart_inventory_db';

$con = mysqli_connect($host, $user, $pass, $db);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css" />
<link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css" />
<link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css" />
<link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css" />
<link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" />
<link rel="stylesheet" href="assets/css/style.css" />
<link rel="shortcut icon" href="assets/images/smart-inventory.png" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
/* ── Page Title Animation ── */
.page-title {
    animation: fadeInDown 0.6s ease-in-out;
}
@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-20px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Cards ── */
.card {
    border: none !important;
    border-radius: 15px !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08) !important;
    transition: transform 0.25s ease, box-shadow 0.25s ease !important;
}
.card:hover {
    transform: translateY(-4px) !important;
    box-shadow: 0 8px 30px rgba(0,0,0,0.13) !important;
}
.card-title {
    font-size: 1rem;
    font-weight: 600;
    color: #4a4a6a;
    margin-bottom: 1rem;
}
.card-body {
    padding: 1.5rem;
}

/* ── Tables ── */
.table {
    border-radius: 10px;
    overflow: hidden;
    font-size: 0.9rem;
}
.table thead th {
    background: linear-gradient(135deg, #6a11cb, #2575fc) !important;
    color: white !important;
    font-weight: 600;
    border: none !important;
    padding: 12px 16px;
    text-align: center;
    letter-spacing: 0.5px;
}
.table tbody tr {
    transition: background 0.2s;
}
.table tbody tr:hover {
    background-color: #f0f4ff !important;
}
.table tbody td {
    vertical-align: middle;
    padding: 11px 16px;
    border-color: #f0f0f0 !important;
    text-align: center;
}
.table-bordered {
    border: 1px solid #e8e8f0 !important;
}

/* ── Badges ── */
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 500;
}

/* ── Buttons ── */
.btn {
    border-radius: 8px !important;
    font-weight: 500;
    padding: 8px 18px;
    transition: all 0.2s ease;
}
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.btn-gradient-primary {
    background: linear-gradient(135deg, #6a11cb, #2575fc) !important;
    color: white !important;
    border: none !important;
}
.btn-gradient-primary:hover {
    opacity: 0.9;
    color: white !important;
}

/* ── Forms ── */
.form-control, .form-select {
    border-radius: 8px !important;
    border: 1.5px solid #e0e0e0 !important;
    padding: 10px 14px;
    font-size: 0.92rem;
    transition: border-color 0.2s;
}
.form-control:focus, .form-select:focus {
    border-color: #6a11cb !important;
    box-shadow: 0 0 0 3px rgba(106,17,203,0.1) !important;
}

/* ── Page header ── */
.page-header {
    margin-bottom: 1.5rem;
}
.page-title-icon {
    border-radius: 10px !important;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* ── Alerts ── */
.alert {
    border-radius: 10px !important;
    border: none !important;
    font-size: 0.9rem;
}

/* ── Smooth page load ── */
.content-wrapper {
    animation: fadeIn 0.4s ease-in-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>
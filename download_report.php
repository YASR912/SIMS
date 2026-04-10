<?php
session_start();

// 🔐 Allow Admin and Manager to download reports
// Fixed: Using 'Admin' and 'Manager' to match the rest of the application
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Manager'])) {
    header('HTTP/1.0 403 Forbidden');
    die("Access Denied: You do not have permission to download reports.");
}

$file = $_GET['file'] ?? null;

if (!$file) {
    die("Error: No file specified.");
}

// Security: Use basename to prevent directory traversal
$safe_filename = basename($file);
$path = __DIR__ . "/reports/" . $safe_filename;

if (!file_exists($path)) {
    die("Error: The requested report file does not exist.");
}

// Determine Content-Type (default to text/plain for .txt files)
$content_type = "text/plain";
if (pathinfo($safe_filename, PATHINFO_EXTENSION) === 'pdf') {
    $content_type = "application/pdf";
}

header("Content-Type: " . $content_type);
header("Content-Disposition: attachment; filename=\"" . $safe_filename . "\"");
header("Content-Length: " . filesize($path));
header("Pragma: no-cache");
header("Expires: 0");

readfile($path);
exit();
?>

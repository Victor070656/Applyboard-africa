<?php

$conn = mysqli_connect("localhost", "root", "", "sdtravels");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Include Auth Helper globally
include_once __DIR__ . '/auth_helper.php';

// Include function helpers
include_once __DIR__ . '/function.php';

// Global Referral Tracking
if (isset($_GET['ref'])) {
    $ref_code = htmlspecialchars($_GET['ref']);
    setcookie("sdtravels_ref", $ref_code, time() + (86400 * 30), "/"); // 30 days
}
?>
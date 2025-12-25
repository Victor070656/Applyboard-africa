<?php
include '../config/config.php';
// Auth Helper included in config
logout('manager');
echo "<script>window.location.href = 'login.php';</script>";
?>

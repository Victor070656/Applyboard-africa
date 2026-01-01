<?php
include '../config/config.php';
// Auth Helper included in config
logout('admin');
echo "<script>window.location.href = 'login.php';</script>";
?>
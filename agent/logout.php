<?php
include '../config/auth_helper.php';

session_start();
logout('agent');
echo "<script>window.location.href = 'login.php';</script>";
?>

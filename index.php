<?php
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location:dashboardNotLog.php");
} else {
    header("Location:dashboard.php");
}
?>
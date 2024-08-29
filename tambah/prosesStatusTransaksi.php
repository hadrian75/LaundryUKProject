<?php
$beforePage = $_SESSION['beforePage'];
$id = $_GET['id'];
$status = $_GET['status'];

mysqli_query($koneksi, "UPDATE tb_transaksi SET status = '$status' WHERE id = '$id'");

if ($beforePage == "detail") {
    echo "<script>window.location.href = 'dashboard.php?page=detailTransaksi';</script>";
} elseif ($beforePage == "laporan") {
    echo "<script>window.location.href = 'dashboard.php?page=laporan';</script>";
}
<?php
include "../koneksi.php";

$namaOutlet = $_POST['namaOutlet'];
$alamat = $_POST['alamat'];
$telepon = $_POST['telepon'];

// Create a prepared statement
$query = "INSERT INTO tb_outlet VALUES (NULL, ?, ?, ?)";
$stmt = mysqli_prepare($koneksi, $query);

// Bind parameters to the prepared statement
mysqli_stmt_bind_param($stmt, "sss", $namaOutlet, $alamat, $telepon);

// Execute the statement
$hasil = mysqli_stmt_execute($stmt);

// Check for success
if (!$hasil) {
    echo "Gagal Tambah Data Outlet: " . mysqli_stmt_error($stmt);
} else {
    header('Location:../dashboard.php?page=outlet');
    exit;
}

// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>

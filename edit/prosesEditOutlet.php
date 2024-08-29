<?php
include "../koneksi.php";

// Assuming you're using $_POST for user input
$id = $_POST['id'];
$nama_outlet = $_POST['namaOutlet'];
$alamat = $_POST['alamatOutlet'];
$telepon = $_POST['telepon'];

// Create a prepared statement
$query = "UPDATE tb_outlet SET nama=?, alamat=?, telepon=? WHERE id=?";
$stmt = mysqli_prepare($koneksi, $query);

// Bind parameters to the prepared statement
mysqli_stmt_bind_param($stmt, "sssi", $nama_outlet, $alamat, $telepon, $id);

// Execute the statement
$hasil = mysqli_stmt_execute($stmt);

// Check for success
if (!$hasil) {
    echo "Gagal Edit Data Outlet: " . mysqli_stmt_error($stmt);
} else {
    header('Location:dashboard.php?page=outlet');
    exit;
}

// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>
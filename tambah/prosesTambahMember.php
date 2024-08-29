<?php
include "../koneksi.php";

$namaMember = $_POST['namaMember'];
$alamat = $_POST['alamat'];
$telepon = $_POST['telepon'];
$gender = $_POST['jenis_kelamin'];

// Create a prepared statement
$query = "INSERT INTO tb_member (nama, alamat, jenis_kelamin, telepon) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($koneksi, $query);

// Bind parameters to the prepared statement
mysqli_stmt_bind_param($stmt, "ssss", $namaMember, $alamat, $gender, $telepon);

// Execute the statement
$hasil = mysqli_stmt_execute($stmt);

// Check for success
if (!$hasil) {
    echo "Gagal Tambah Data Pelanggan: " . mysqli_stmt_error($stmt);
} else {
    header('Location:../dashboard.php?page=member');
    exit;
}

// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($koneksi);

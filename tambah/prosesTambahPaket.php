<?php

include "../koneksi.php";

$idOutlet = $_POST['idOutlet'];
$namaPaket = $_POST['namaPaket'];
$harga = $_POST['harga'];
$jenisPaket = $_POST['jenisPaket'];

// Create a prepared statement
$query = "INSERT INTO tb_paket VALUES (NULL, ?, ?, ?, ?)";
$stmt = mysqli_prepare($koneksi, $query);

// Bind parameters to the prepared statement
mysqli_stmt_bind_param($stmt, "issi", $idOutlet, $jenisPaket, $namaPaket, $harga);

// Execute the statement
$hasil = mysqli_stmt_execute($stmt);

// Check for success
if (!$hasil) {
    echo "Gagal Tambah Data Outlet: " . mysqli_stmt_error($stmt);
} else {
    header('Location:../dashboard.php?page=paket');
    exit;
}

// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($koneksi);

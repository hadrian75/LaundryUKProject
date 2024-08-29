<?php
include "koneksi.php";

$id = $_POST['id'];
$id_outlet = $_POST['idOutlet'];
$jenis = $_POST['jenis'];
$nama_paket = $_POST['namaPaket'];
$harga = $_POST['harga'];

$query = "UPDATE tb_paket SET id_outlet=?, jenis=?, nama_paket=?, harga=? WHERE id=?";
$stmt = mysqli_prepare($koneksi, $query);

mysqli_stmt_bind_param($stmt, "isssi", $id_outlet, $jenis, $nama_paket, $harga, $id);

$hasil = mysqli_stmt_execute($stmt);

if (!$hasil) {
    echo "Gagal Edit Data Paket: " . mysqli_stmt_error($stmt);
} else {
    header('Location:dashboard.php?page=paket');
    exit;
}

mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>
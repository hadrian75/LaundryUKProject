<?php
include "koneksi.php";

$namaLengkap = $_POST['namaLengkap'];
$usernameRegist = $_POST['username'];
$passwordRegist = $_POST['pass'];
$idOutlet = $_POST['idOutlet'];
$role = $_POST['role'];

// Prepare the SQL statement with placeholders
$query = "INSERT INTO tb_user (nama, username, `password`, id_outlet, `role`) VALUES (?, ?, ?, ?, ?)";

// Initialize a prepared statement
$stmt = mysqli_stmt_init($koneksi);

// Prepare the prepared statement
if (mysqli_stmt_prepare($stmt, $query)) {
    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "sssss", $namaLengkap, $usernameRegist, $passHash, $idOutlet, $role);

    // Hash the password
    $passHash = password_hash($passwordRegist, PASSWORD_DEFAULT);

    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Redirect to login page if registration is successful
        header('Location:dashboard.php?page=user');
        exit;
    } else {
        // Error handling if execution fails
        echo "Gagal Register : " . mysqli_error($koneksi);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    // Error handling if preparation fails
    echo "Prepared statement error: " . mysqli_error($koneksi);
}

// Close the connection
mysqli_close($koneksi);
?>

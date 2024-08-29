<?php
include "koneksi.php";

// Check if ID is set and is numeric
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    // Assign ID
    $id = $_POST['id'];

    // Prepare statement
    $sql = "UPDATE tb_member SET nama = ?, alamat = ?, jenis_kelamin = ?, telepon = ? WHERE id = ?";
    if ($stmt = mysqli_prepare($koneksi, $sql)) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ssssi", $nama, $alamat, $jenis, $tlp, $id);

        // Assign values from $_POST
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $jenis = $_POST['gender'];
        $tlp = $_POST['tlp'];

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect upon successful update
            header('Location:dashboard.php?page=member');
        } else {
            // Error handling if execution fails
            echo "Update Customer Failed : " . mysqli_error($koneksi);
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        // Error handling if prepare fails
        echo "Error: " . mysqli_error($koneksi);
    }
} else {
    // Handle invalid or missing ID
    echo "Invalid or Missing ID";
}
?>
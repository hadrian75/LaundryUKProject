<?php
include "../koneksi.php";

// Check if the id parameter is set
if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the DELETE statement
    $query = $koneksi->prepare("DELETE FROM tb_detail_transaksi WHERE id = ?");
    
    // Bind the parameter to the query
    $query->bind_param("i", $id);
    
    // Execute the query
    if ($query->execute()) {
        // Redirect to the index.php?page=DetailTransaction page on success
        header('Location:dashboard.php?page=detailTransaksi');
        exit;
    } else {
        // Handle errors if the query fails
        echo "Failed Delete Detail Transaction : " . $query->error;
    }
} else {
    // Handle the case if id parameter is not set
    echo "ID parameter is missing";
}
?>

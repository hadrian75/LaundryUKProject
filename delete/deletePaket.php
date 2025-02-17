<?php
include_once ("../koneksi.php");

// Check if the id parameter is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the DELETE statement
    $query = $koneksi->prepare("DELETE FROM tb_paket WHERE id = ?");

    // Bind the parameter to the query
    $query->bind_param("i", $id);

    // Execute the query
    if ($query->execute()) {
        // Redirect to the dashboard.php?page=paket page on success
        header('Location:../dashboard.php?page=paket');
        exit;
    } else {
        // Handle errors if the query fails
        echo "Failed to delete data paket: " . $query->error;
    }
} else {
    // Handle the case if id parameter is not set
    echo "ID parameter is missing";
}
?>
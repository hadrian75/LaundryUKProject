<?php
include_once("../koneksi.php");

// Check if the id parameter is set
if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the DELETE statement
    $query = $koneksi->prepare("DELETE FROM tb_outlet WHERE id = ?");
    
    // Bind the parameter to the query
    $query->bind_param("i", $id);
    
    // Execute the query
    if ($query->execute()) {
        // Redirect to the dashboard.php?page=outlet page on success
        header('Location:../dashboard.php?page=outlet'); 
        exit;
    } else {
        // Handle errors if the query fails
        echo "Failed to delete data outlet: " . $query->error;
    }
} else {
    // Handle the case if id parameter is not set
    echo "ID parameter is missing";
}
?>

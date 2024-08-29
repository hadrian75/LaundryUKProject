<?php
include 'koneksi.php';

// Check if the 'id' parameter is set
if (isset($_GET['id'])) {
    // Assign the 'id' parameter to a variable
    $id = $_GET['id'];

    // Prepare the DELETE statement with a placeholder for the id
    $sql = "DELETE FROM tb_user WHERE id = ?";
    $query = $koneksi->prepare($sql);

    // Bind the id parameter to the prepared statement
    $query->bind_param("i", $id);

    // Execute the prepared statement
    if ($query->execute()) {
        // Redirect to the index.php?page=Employee page on success
        header('Location:dashboard.php?page=user');
        exit;
    } else {
        // Output an error message if the execution fails
        echo "Delete Employee Failed: " . $query->error;
    }
} else {
    // Output a message if the 'id' parameter is not set
    echo "ID parameter is missing";
}
?>

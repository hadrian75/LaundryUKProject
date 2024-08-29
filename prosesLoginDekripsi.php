<?php
session_start();
$user = $_POST['username'];
$password_login = $_POST['password'];

include "koneksi.php";

// Prepare the SQL statement with placeholders
$query = "SELECT 
tb_user.*, 
tb_outlet.nama AS namaOutlet 
FROM 
tb_user 
INNER JOIN 
tb_outlet 
ON 
tb_user.id_outlet = tb_outlet.id 
WHERE 
username=?
";
$stmt = mysqli_stmt_init($koneksi);

// Initialize a prepared statement
if (mysqli_stmt_prepare($stmt, $query)) {
    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "s", $user);

    // Execute the prepared statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Fetch the row
    $query_lvluser = mysqli_fetch_assoc($result);

    // Check if user exists and password is correct
    if ($query_lvluser && password_verify($password_login, $query_lvluser['password'])) {
        // Store user information in session variables
        $_SESSION['username'] = $user;
        $_SESSION['role'] = $query_lvluser['role'];
        $_SESSION['id_user'] = $query_lvluser['id'];
        $_SESSION['id_outlet'] = $query_lvluser['id_outlet'];
        $_SESSION['nama_outlet'] = $query_lvluser['namaOutlet'];
        // Redirect to dashboard after successful login
        echo "<script>alert('Berhasil Login');location.href='dashboard.php?page=home'</script>";
    } else {
        // Redirect back to login page if login fails
        echo "<script>alert('Gagal Login');location.href='login.php'</script>";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    // Error handling if preparation fails
    echo "<script>alert('Gagal Login');location.href='login.php'</script>";
}

// Close the connection
mysqli_close($koneksi);
?>
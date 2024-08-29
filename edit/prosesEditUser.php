<?php
include '../koneksi.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$username = $_POST['username'];
$id_outlet = $_POST['idOutlet'];
$role = $_POST['role'];
$password_lama = $_POST['passConfirm'];
$password = $_POST['pass'];

if (!$koneksi) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit;
}

$sqluser = "SELECT * FROM tb_user WHERE id = ?";
$stmtuser = mysqli_prepare($koneksi, $sqluser);

if (!$stmtuser) {
    echo "Error preparing statement: " . mysqli_error($koneksi);
    exit;
}

mysqli_stmt_bind_param($stmtuser, "i", $id);
mysqli_stmt_execute($stmtuser);
$resultuser = mysqli_stmt_get_result($stmtuser);
$userData = mysqli_fetch_assoc($resultuser);

if ($password) {  // Only check old password if a new password is provided
    if (!password_verify($password_lama, $userData['password'])) {
        echo "<script>alert('Old password is incorrect.');window.location.href='../dashboard.php?page=user'</script>";
        exit;
    }
}

if ($password) {
    $pass_hash = password_hash($password, PASSWORD_DEFAULT);
} else {
    $pass_hash = $userData['password'];
}

$sql = "UPDATE tb_user SET nama = ?, username = ?, password = ?, id_outlet = ?, role = ? WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    echo "Error preparing statement: " . mysqli_error($koneksi);
    exit;
}

mysqli_stmt_bind_param($stmt, "sssisi", $nama, $username, $pass_hash, $id_outlet, $role, $id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header('Location:dashboard.php?page=user');
    exit;
} else {
    error_log("Failed to update user: " . mysqli_error($koneksi));
    echo "<script>alert('Failed to update user.');window.location.href='dashboard.php?page=user'</script>";
}

mysqli_stmt_close($stmtuser);
mysqli_close($koneksi);

?>
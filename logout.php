<?php
session_start();
session_destroy();
header("Location: login.php");
exit;
echo "<script>alert('Berhasil Logout');location.href='login.php'</script>";
?>
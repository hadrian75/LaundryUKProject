<?php
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}
$id = $_GET['id'];

$query = mysqli_query($koneksi, "SELECT tb_user. *, tb_outlet.nama as namaOutlet, tb_outlet.id as idOutlet  FROM tb_user INNER JOIN tb_outlet ON tb_user.id_outlet = tb_outlet.id WHERE tb_user.id ='$id'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit User</title>
</head>

<body>
  <form action="dashboard.php?page=prosesEditUser" method="POST" class="bg-gray-900 max-w-xl mx-auto p-10 mt-5">
    <h2 class="text-2xl pb-2 text-white font-bold">Edit User</h2>
    <input type="text" hidden name="id" value="<?= $data['id'] ?>">
    <div class="mb-5">
      <div class="mb-5">
        <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama User</label>
        <input type="text" name="nama" id="nama"
          class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 p-2"
          placeholder="Nama User" value="<?= $data["nama"] ?>">
      </div>
      <div class="mb-5">
        <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
        <input type="text" name="username" id="username"
          class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 p-2"
          placeholder="Username User" value="<?= $data["username"] ?>">
      </div>
      <label for="idOutlet" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Outlet</label>
      <select name="idOutlet" id="idOutlet"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        <?php
        $paketSql = mysqli_query($koneksi, "SELECT * FROM tb_outlet");
        while ($dataOutlet = mysqli_fetch_assoc($paketSql)) {
          ?>
          <option value="<?= $dataOutlet['id'] ?>" <?php if ($data["idOutlet"] == $dataOutlet["id"]) {
              echo "selected";
            } ?>>
            <?= $dataOutlet["nama"] ?>
          </option>
        <?php } ?>
      </select>
    </div>
    <div class="mb-5">
      <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Role</label>
      <select name="role" id="role"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        <option value="" selected hidden>Pilih Role</option>
        <option value="admin" <?php echo ($data["role"] == "admin") ? 'selected' : ''; ?>>Admin</option>
        <option value="kasir" <?php echo ($data["role"] == "kasir") ? 'selected' : ''; ?>>Kasir</option>
        <option value="owner" <?php echo ($data["role"] == "owner") ? 'selected' : ''; ?>>Owner</option>
      </select>
    </div>



    <div class="mb-5">
      <label for="pass" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password Lama</label>
      <input type="password" name="passConfirm" id="pass"
        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 p-2"
        placeholder="Isi Password Lama">
    </div>
    <div class="mb-5">
      <label for="pass" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password Baru</label>
      <input type="password" name="pass" id="pass"
        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 p-2"
        placeholder="Isi Password Baru">
    </div>

    <button type="submit"
      class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
  </form>
  <?= $_SESSION['id_outlet'] ?>
</body>

</html>
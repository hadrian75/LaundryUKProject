<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$id = $_GET['id'];

$query = mysqli_query($koneksi, "SELECT * FROM tb_member WHERE id='$id'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member</title>
</head>

<body>
    <form action="dashboard.php?page=prosesEditMember" method="POST" class="bg-gray-900 max-w-xl mx-auto p-10 mt-5">
        <h2 class="text-2xl pb-2 rounded-md text-white font-bold">Edit Member</h2>
        <input type="text" hidden name="id" value="<?= $data['id'] ?>">
        <div class="mb-5">
            <label for="namaMember" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                Member</label>
            <input type="text" name="nama" id="namaMember"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Isi Nama Member Anda" value="<?= $data["nama"] ?>" required>
        </div>
        <div class="mb-5">
            <label for="telp" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telepon</label>
            <input type="text" name="tlp" id="telp"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Isi Nomor Member" value="<?= $data["telepon"] ?>" required>
        </div>
        <div class="mb-5">
            <select name="gender" id="gender"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="" selected hidden>Pilih Gender</option>
                <option value="male" <?php if ($data["jenis_kelamin"] == "male") {
                    echo "selected";
                } ?>>Laki - laki
                </option>
                <option value="female" <?php if ($data["jenis_kelamin"] == "female") {
                    echo "selected";
                } ?>>Perempuan
                </option>
            </select>
        </div>

        <div class="mb-5">
            <label for="alamatMember" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat
                Member</label>
            <textarea name="alamat" id="alamatMember" rows="5"
                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 p-2"
                placeholder="Isi Alamat Member"><?= $data["alamat"] ?></textarea>
        </div>

        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
    </form>

</body>

</html>
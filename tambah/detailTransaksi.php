<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$_SESSION['beforePage'] = "detail";
if (@$_GET['idtransaksi']) {
    $idtransaksi = $_GET['idtransaksi'];
    $_SESSION['idtransaksi'] = $idtransaksi;
} else if (@$_SESSION['idtransaksi']) {
    $idtransaksi = $_SESSION['idtransaksi'];
}

$data_transaksi = mysqli_fetch_row(mysqli_query($koneksi, "SELECT * FROM tb_transaksi INNER JOIN tb_member ON tb_transaksi.id_member = tb_member.id INNER JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id INNER JOIN tb_user ON tb_transaksi.id_user = tb_user.id WHERE tb_transaksi.id = '$idtransaksi'"));

if (@$_POST['pilih_paket']) {
    $quantity = $_POST['quantity'];
    $nama_paket = $_POST['nama_paket'];
    $row_paket = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_paket WHERE nama_paket = '$nama_paket'"));
    $harga_paket = $row_paket['harga'];
    $total_harga = $quantity * $harga_paket;
    $id_paket = $row_paket['id'];
    $keterangan = $_POST['keterangan'];
    mysqli_query($koneksi, "INSERT INTO tb_detail_transaksi VALUES(NULL, '$idtransaksi', '$id_paket', '$quantity', '$keterangan', '$harga_paket', '$total_harga')");
    header("Location: " . $_SERVER['REQUEST_URI']);
}

if (@$_POST['bayar_sekarang']) {
    $tgl_bayar = date('Y-m-d H:i:s');
    mysqli_query($koneksi, "UPDATE tb_transaksi SET dibayar = 'dibayar', tgl_bayar = '$tgl_bayar' WHERE id = '$idtransaksi'");
    header("Location: " . $_SERVER['REQUEST_URI']);
}

if ($data_transaksi['11'] == 'belum_dibayar') {
    $pembayaran = 'Belum dibayar';
} else {
    $pembayaran = 'Sudah dibayar';
}

if (@$_POST['tombol_biaya_tambahan']) {
    $biaya_tambahan = $_POST['biaya_tambahan'];
    mysqli_query($koneksi, "UPDATE tb_transaksi SET biaya_tambahan = '$biaya_tambahan' WHERE id = '$idtransaksi'");
    header("Location: " . $_SERVER['REQUEST_URI']);
}
?>


<h1 class="text-center text-3xl font-bold no-print">
    Detail Transaksi
</h1>
<h1 class="text-center text-3xl font-bold print hidden">
    Invoice Transaksi
</h1>
<div class="container grid grid-cols-3 headGrid gap-4 px-1 changeStylePrint ">
    <div class="flex flex-row items-center justify-end my-2 col-span-3 no-print">
        <div class="block mx-2">
            <span class="text-[14px] font-bold">Progress Detail Transaksi</span>
        </div>
        <div class="w-[300px] flex justify-end">
            <?php
            if ($data_transaksi['10'] == 'baru') {
                ?>
                <span
                    class="bg-blue-700 text-white font-semibold text-[14px] w-[25%] block rounded-[25px] max-h-[30px] items-center  p-2">
                    <p class="leading-[2px]">25%</p>
                </span>
                <?php
            } else if ($data_transaksi['10'] == 'proses') {
                ?>
                    <span
                        class="bg-blue-700 text-white font-semibold text-[14px] rounded-[25px] w-[50%] block rounded-[25px] max-h-[30px] items-center  p-2">
                        <p class="leading-[2px]">50%</p>
                    </span>
                <?php
            } else if ($data_transaksi['10'] == 'selesai') {
                ?>
                        <span
                            class="bg-blue-700 text-white font-semibold text-[14px] rounded-[25px] w-[75%] block rounded-[25px] max-h-[30px] items-center  p-2">
                            <p class="leading-[2px]">75%</p>
                        </span>
                <?php
            } else if ($data_transaksi['10'] == 'diambil') {
                ?>
                            <span
                                class="bg-blue-700 text-white font-semibold text-[14px] w-[100%] block rounded-[25px] max-h-[30px] items-center  p-2">
                                <p class="leading-[2px]">100%</< /span>
                        <?php
            }
            ?>
        </div>
    </div>
    <div class="col-span-1 rounded-sm h-auto childGridOne">
        <div class="rounded-sm p-1 bg-gray-800">

            <table border="0" cellspacing="0" class="table-auto w-full ">
                <thead class="border-b">
                    <th class="text-left">
                        <span class="text-white text-[12px] h-[30px]">Status Bayar</span>
                    </th>
                    <th class="text-left">
                        <span class="text-white text-[12px] h-[30px]">:</span>
                    </th>
                    <th class="text-left">
                        <span
                            class="text-[12px] h-[30px] <?= $pembayaran == "Belum dibayar" ? "text-red-500" : "text-green-500" ?>">
                            <?= $pembayaran ?>
                        </span>
                    </th>
                </thead>
                <tbody>
                    <tr
                        class="bg-gray-700 border-b dark:bg-gray-800 dark:border-gray-700 text-white text-[12px] h-[30px] ">
                        <td>Invoice</td>
                        <td scope="col" class="w-2">:</td>
                        <td>
                            <?= $data_transaksi['2'] ?>
                        </td>
                    </tr>
                    <tr
                        class="bg-gray-700 border-b dark:bg-gray-800 dark:border-gray-700 text-white text-[12px] h-[30px]">
                        <td>Customer</td>
                        <td>:</td>
                        <td>
                            <?= $data_transaksi['14'] ?>
                        </td>
                    </tr>
                    <tr
                        class="bg-gray-700 border-b dark:bg-gray-800 dark:border-gray-700 text-white text-[12px] h-[30px]">
                        <td>Phone Number</td>
                        <td>:</td>
                        <td>
                            <?= $data_transaksi['17'] ?>
                        </td>
                    </tr>
                    <tr
                        class="bg-gray-700 border-b dark:bg-gray-800 dark:border-gray-700 text-white text-[12px] h-[30px]">
                        <td>Customer Address</td>
                        <td>:</td>
                        <td>
                            <?= $data_transaksi['15'] ?>
                        </td>
                    </tr>
                    <tr
                        class="bg-gray-700 border-b dark:bg-gray-800 dark:border-gray-700 text-white text-[12px] h-[30px]">
                        <td>Employee</td>
                        <td>:</td>
                        <td>
                            <?= ucfirst($data_transaksi['23']) ?>
                        </td>
                    </tr>
                    <tr
                        class="bg-gray-700 border-b dark:bg-gray-800 dark:border-gray-700 text-white text-[12px] h-[30px]">
                        <td>Expired</td>
                        <td>:</td>
                        <td>
                            <?php
                            $data_transaksi['5'];
                            $pecah_string_tanggal = explode(" ", $data_transaksi['5']);
                            $pecah_string_hari = explode("-", $pecah_string_tanggal[0]);
                            $pecah_string_jam = explode(":", $pecah_string_tanggal[1]);

                            echo "Date : " . $pecah_string_hari[2] . "-" . $pecah_string_hari[1] . "-" . $pecah_string_hari[0];
                            echo "<br>";
                            echo "Time : " . $pecah_string_jam[0] . ":" . $pecah_string_jam[1];
                            ?>
                        </td>
                    </tr>
                    <tr
                        class="bg-gray-700 border-b dark:bg-gray-800 dark:border-gray-700 text-white text-[12px] h-[30px]">
                        <td>Status</td>
                        <td>:</td>
                        <td>
                            <select
                                onchange="pilihStatus(this.options[this.selectedIndex].value, <?= $data_transaksi['0'] ?>)"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="baru" <?php if ($data_transaksi['10'] == 'baru') {
                                    echo "selected";
                                } ?>>
                                    Baru
                                </option>
                                <option value="proses" <?php if ($data_transaksi['10'] == 'proses') {
                                    echo "selected";
                                } ?>>
                                    Proses
                                </option>
                                <option value="selesai" <?php if ($data_transaksi['10'] == 'selesai') {
                                    echo "selected";
                                } ?>>
                                    Selesai
                                </option>
                                <option value="diambil" <?php if ($data_transaksi['10'] == 'diambil') {
                                    echo "selected";
                                } ?>>
                                    Diambil
                                </option>
                            </select>
                            <script>
                                function pilihStatus(value, id) {
                                    window.location.href = "dashboard.php?page=prosesStatusTransaksi&status=" + value + "&id=" + id;
                                }
                            </script>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
        if ($data_transaksi['11'] == 'belum_dibayar') {
            ?>
            <div class="no-print">
                <form action="" method="post">
                    <div class="">
                        <div class="">
                            <i class=""></i>
                            <input type="text"
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 p-2 my-1"
                                name="nama_paket" list="nama_paket" id="" placeholder="Package" autocomplete="off" required>
                            <datalist id="nama_paket">
                                <?php
                                $id_outlet = $data_transaksi['18'];
                                $query_paket = mysqli_query($koneksi, "SELECT nama_paket FROM tb_paket WHERE id_outlet = '$id_outlet'");
                                while ($data_paket = mysqli_fetch_assoc($query_paket)) {
                                    ?>
                                    <option value="<?= $data_paket['nama_paket'] ?>"></option>
                                    <?php
                                }
                                ?>
                            </datalist>
                        </div>
                        <div class="">
                            <i class=""></i>
                            <input type="number"
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 p-2 my-1"
                                name="quantity" id="" placeholder="quantity" autocomplete="off" min="0" required>
                        </div>
                        <div class="">
                            <i class=""></i>
                            <input type="text"
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 p-2 my-1"
                                name="keterangan" id="" placeholder="Keterangan" autocomplete="off">
                        </div>
                        <div class="">
                            <input type="submit"
                                class="py-1 px-2 bg-blue-600 hover:bg-blue-700 w-[120px] text-white rounded-md"
                                name="pilih_paket" value="Insert Package">
                        </div>
                    </div>
                </form>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="col-span-2 h-auto childGridTwo">

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg w-[100%] mr-2">
            <table class="w-full text-sm text-left rtl:text-left text-gray-500 dark:text-gray-400" id="report-content">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr class="bg-gray-700 border-b dark:bg-gray-800 dark:border-gray-700">
                        <th class="px-6 py-3 text-left" scope="col">Package</th>
                        <th class="px-6 py-3 text-left" scope="col">Description</th>
                        <th class="px-6 py-3 text-left" scope="col">Quantity</th>
                        <th class="px-6 py-3 text-left" scope="col">Price</th>
                        <th class="px-6 py-3 text-left" scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>

        </div>
        <?php
        $result_detail = mysqli_query($koneksi, "SELECT * FROM tb_detail_transaksi WHERE id_transaksi = '$idtransaksi'");
        while ($detail = mysqli_fetch_assoc($result_detail)) {
            ?>
            <tr class="bg-gray-700 border-b dark:bg-gray-800 dark:border-gray-700">
                <td scope="col" class="px-6 py-3 text-left" scope="col">
                    <?php
                    $idpaket = $detail['id_paket'];
                    $paket = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_paket, jenis, harga FROM tb_paket WHERE id = '$idpaket'"));
                    echo $paket['nama_paket'];
                    echo "<br>";
                    echo $paket['jenis'];
                    ?>
                </td>
                <td scope="col" class="px-6 py-3 text-left " scope="col">
                    <?= $detail['keterangan'] ?>
                </td>
                <td scope="col" class="px-6 py-3 text-left" scope="col">
                    <?= $detail['quantity'] ?>
                </td>
                <td scope="col" class="px-6 py-3 text-left" scope="col">Rp.
                    <?= number_format($detail['harga_paket'], 0, ',', '.') ?>
                </td>
                <td scope="col" class="px-6 py-3 text-left" scope="col">
                    <form action="handlers/process_delete_package_transaction.php" method="get">
                        <input type="text" name="id" value="<?= $detail['id'] ?>" hidden>
                        <?php if ($data_transaksi['11'] == 'belum_dibayar') { ?>
                            <p class="text-blue-500">Rp.
                                <?= number_format($detail['total_harga'], 0, ',', '.') ?></button>
                            <?php } else { ?>
                                <span class="text-blue-500 font-bold">Rp.
                                    <?= number_format($detail['total_harga'], 0, ',', '.') ?>
                                </span>
                            <?php } ?>
                    </form>
                </td>
            </tr>
            <?php
        }
        ?>
        <?php
        $grand_total = mysqli_fetch_row(mysqli_query($koneksi, "SELECT SUM(total_harga) FROM tb_detail_transaksi INNER JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id WHERE id_transaksi = '$idtransaksi'"));
        if (!$grand_total['0'] == '0') {
            ?>
            <tr class="bg-gray-700 border-b dark:bg-gray-800 dark:border-gray-700">
                <td scope="col" colspan="4" class="text-left px-6  border-gray-300 font-bold">
                    Pajak</td>
                <td scope="col" class="text-left px-6 py-3  font-bold" scope="col">
                    <?php
                    echo "0.75%";
                    $pajak = $grand_total['0'] * $data_transaksi['9'];
                    echo "( Rp." . number_format($pajak, 0, ',', '.') . ")";
                    ?>
                </td>
            </tr>
            <?php if ($data_transaksi['7'] != 0) { ?>
                <tr class="bg-gray-700 border-b dark:bg-gray-800 dark:border-gray-700">
                    <td scope="col" colspan="4" class="text-left px-6 py-3 border-gray-300 font-bold">
                        Biaya Tambahan</td>
                    <td scope="col" class=" px-6 py-3 text-left border-r font-bold" scope="col">
                        <?= "Rp." . number_format($data_transaksi['7'], 0, ',', '.') ?>
                    </td>
                </tr>
            <?php } ?>
            <?php if ($data_transaksi['8'] != 0) { ?>
                <tr class="bg-gray-700 border-b dark:bg-gray-800 dark:border-gray-700">
                    <td scope="col" colspan="4" class="text-left px-6 py-3   border-gray-300 font-bold">
                        Discount</td>
                    <td scope="col" class="r px-6 py-3 text-left  font-bold" scope="col">
                        <?php
                        echo "10%";
                        echo "<br>";
                        $diskon = $grand_total['0'] * $data_transaksi['8'];
                        echo "Rp." . number_format($diskon, 0, ',', '.');
                        ?>
                    </td>
                </tr>
            <?php } else {
                $diskon = 0;
            } ?>
            <tr class="bg-gray-700 border-b dark:bg-gray-800 dark:border-gray-700">
                <td scope="col" colspan="4" class="text-left px-6 py-3    border-gray-300 font-bold">
                    Total Keseluruhan</td>
                <td scope="col" class=" px-6 py-3 text-left  font-bold text-green-500">
                    <?php
                    $total_keseluruhan = ($grand_total['0'] + $data_transaksi['7'] + $pajak) - $diskon;
                    echo "Rp." . number_format($total_keseluruhan, 0, ',', '.');
                    ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
        </table>
        <?php if (!$grand_total['0'] == '0') { ?>
            <div class="container mt-2">
                <form action="" method="post" class="pl-2 flex flex-start gap-2 items-center">
                    <div class="" class=" flex items-center  max-w-[120px]<?php if ($data_transaksi['11'] == 'dibayar') {
                        echo "none";
                    } ?>;">
                        <input type="number" name="biaya_tambahan" placeholder="Biaya Tambahan" autocomplete="off" class="outline-none border-[1px] no-print border-black rounded-md placeholder:pl-2  <?php if ($data_transaksi['11'] == 'dibayar') {
                            echo "hidden";
                        } ?>">
                    </div>
                    <div class="">
                        <input type="submit" value="Add" name="tombol_biaya_tambahan" class="bg-yellow-400 no-print cursor-pointer hover:bg-yellow-500 rounded-md font-semibold w-[60px]  <?php if ($data_transaksi['11'] == 'dibayar') {
                            echo "hidden";
                        } ?>">
                    </div>
                </form>
                <div class="flex justify-between mt-4 p-2">
                    <form action="" method="post">
                        <input type="submit" value="Pay Now" name="bayar_sekarang"
                            onclick="return confirm('Really want to pay?')" class="bg-green-600 rounded-sm cursor-pointer hover:bg-green-700 w-[100px] text-white p-2 mt-2 no-print h-[40px] <?php if ($data_transaksi['11'] == 'dibayar') {
                                echo "hidden";
                            } ?>" scope="col">
                    </form>
                    <button onclick="window.print()"
                        class="w-[120px] block  bg-blue-600 text-white p-2 rounded-sm mt-2 hover:bg-blue-700 h-[40px] no-print">Print
                        Report</button>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
</div>
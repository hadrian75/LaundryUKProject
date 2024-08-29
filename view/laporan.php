<?php
$_SESSION['beforePage'] = "laporan";

// Define the default status condition
$statusCondition = "";

// Check if status is set in GET parameters and construct the status condition accordingly
$filterStatus = isset($_GET['status']) ? $_GET['status'] : '';
if ($filterStatus != '') {
    $statusCondition = "WHERE status = '$filterStatus'";
}
$outletCondition = "";

// Construct the SQL query based on user role and status condition
if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'owner') {
    $sql = "SELECT *, tb_outlet.id AS id_outlet_tb_outlet, tb_outlet.nama AS nama_outlet, tb_transaksi.id AS id_transaksi, tb_member.nama AS nama_member 
            FROM tb_detail_transaksi 
            INNER JOIN tb_transaksi ON tb_detail_transaksi.id_transaksi = tb_transaksi.id 
            INNER JOIN tb_member ON tb_transaksi.id_member = tb_member.id 
            INNER JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id 
            INNER JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id 
            INNER JOIN tb_user ON tb_transaksi.id_user = tb_user.id 
            $statusCondition 
            GROUP BY kode_invoice";
} else {
    // Get outlet from session
    $sessionOutlet = $_SESSION['id_outlet'];

    // Append outlet condition based on whether status condition is set or not
    if (!empty($statusCondition)) {
        $outletCondition = "AND tb_outlet.id = '$sessionOutlet'";
    } else {
        $outletCondition = "WHERE tb_outlet.id = '$sessionOutlet'";
    }

    // Construct the SQL query
    $sql = "SELECT *, tb_outlet.id AS id_outlet_tb_outlet, tb_outlet.nama AS nama_outlet, tb_transaksi.id AS id_transaksi, tb_member.nama AS nama_member 
            FROM tb_detail_transaksi 
            INNER JOIN tb_transaksi ON tb_detail_transaksi.id_transaksi = tb_transaksi.id 
            INNER JOIN tb_member ON tb_transaksi.id_member = tb_member.id 
            INNER JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id 
            INNER JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id 
            INNER JOIN tb_user ON tb_transaksi.id_user = tb_user.id 
            $statusCondition 
            $outletCondition 
            GROUP BY kode_invoice";
}

// Pagination logic
$records_per_page = 9;
$current_page = isset($_GET['viewPage']) ? intval($_GET['viewPage']) : 1;

// Calculate offset based on current page
$offset = ($current_page - 1) * $records_per_page;

// Query to get total number of records
$total_records_query = "SELECT COUNT(*) AS total FROM tb_detail_transaksi 
                        INNER JOIN tb_transaksi ON tb_detail_transaksi.id_transaksi = tb_transaksi.id 
                        INNER JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id 
                        INNER JOIN tb_user ON tb_transaksi.id_user = tb_user.id 
                        $statusCondition 
                        $outletCondition 
                        GROUP BY kode_invoice";

$total_records_result = mysqli_query($koneksi, $total_records_query);

// Get the total number of records
$total_records = mysqli_num_rows($total_records_result);

// Calculate total number of pages
$total_pages = ceil($total_records / $records_per_page);

// Append LIMIT clause to SQL query
$sql .= " LIMIT $offset, $records_per_page";

// Execute the query
$datas = mysqli_query($koneksi, $sql);

// Check if there are rows returned
if (mysqli_num_rows($datas) != 0) {
    ?>


    <div class="container mx-auto py-8 px-2 ">
        <h1 class="text-3xl font-bold text-center no-print">Laporan</h1>
        <div class="flex justify-between items-center mb-4  ">
            <div class="flex items-center gap-4 ">
                <form action="dashboard.php?page=cetakLaporan" method="POST" class="flex gap-4">
                    <div class="relative max-w-sm">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input datepicker datepicker-format="mm/dd/yyyy" type="text" name="tanggalAwal"
                            class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Pilih Tanggal Awal">
                    </div>

                    <div class="relative max-w-sm">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input datepicker datepicker-format="mm/dd/yyyy" type="text" name="tanggalAkhir"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Pilih Tanggal Akhir">
                    </div>


                    <input type="submit" class="bg-blue-500 hover:bg-blue-600 cursor-pointer px-5 rounded-md"
                        value="Generate">
                </form>
            </div>
            <div class="flex items-center justify-between">
                <select onchange="pilihStatus(this.options[this.selectedIndex].value)"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="" selected>All Status</option>
                    <option value="baru" <?php if (@$_GET['status'] == 'baru') {
                        echo "selected";
                    } ?>>New</option>
                    <option value="proses" <?php if (@$_GET['status'] == 'proses') {
                        echo "selected";
                    } ?>>Process</option>
                    <option value="selesai" <?php if (@$_GET['status'] == 'selesai') {
                        echo "selected";
                    } ?>>Done</option>
                    <option value="diambil" <?php if (@$_GET['status'] == 'diambil') {
                        echo "selected";
                    } ?>>Taked</option>
                </select>
                <script>
                    function pilihStatus(value) {
                        window.location = "dashboard.php?page=laporan&status=" + value;
                    }
                </script>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            <?php
            $datas = mysqli_query($koneksi, $sql);

            while ($data = mysqli_fetch_row($datas)) {
                ?>
                <div class="border border-gray-300 rounded p-4 bg-gray-900 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <span>
                            <?= $data['9'] ?>
                        </span>
                        <a class="p-1" style="border: 2px solid <?php if ($data['18'] == 'belum_dibayar') {
                            echo "#0096c7";
                        } else {
                            echo "#38b000";
                        } ?>; color: <?php if ($data['18'] == 'belum_dibayar') {
                             echo "#0096c7";
                         } else {
                             echo "#38b000";
                         } ?>;" href="dashboard.php?page=detailTransaksi&idtransaksi=<?= $data['7'] ?>">See Detail</a>
                    </div>
                    <div class="mb-2">
                        <span>
                            <?php $idoutlet = $data['8'];
                            $sqloutlet = "SELECT * FROM tb_outlet WHERE id = '$idoutlet'";
                            $datasoutlet = mysqli_query($koneksi, $sqloutlet);
                            $dataoutlet = mysqli_fetch_assoc($datasoutlet);
                            echo "Outlet Name : " . $dataoutlet['nama']; ?>
                        </span>
                    </div>
                    <div class="mb-2 flex flex-col">
                        <span class="">
                            <?php
                            $pecah_string_tanggal = explode(" ", $data['12']);
                            $pecah_string_hari = explode("-", $pecah_string_tanggal[0]);
                            $pecah_string_jam = explode(":", $pecah_string_tanggal[1]);

                            echo "Deadline : " . $pecah_string_hari[2] . "-" . $pecah_string_hari[1] . "-" . $pecah_string_hari[0];
                            echo "<br>";
                            echo "Time : " . $pecah_string_jam[0] . ":" . $pecah_string_jam[1];
                            ?>
                        </span>
                        <span class="">
                            <?php
                            $idmember = $data['10'];
                            $sqlmember = "SELECT * FROM tb_member WHERE id = '$idmember'";
                            $datasmember = mysqli_query($koneksi, $sqlmember);
                            $datamember = mysqli_fetch_assoc($datasmember);

                            echo $datamember['nama'];
                            ?>
                        </span>
                    </div>
                    <div class="mb-2">
                        <span class="">
                            <?php
                            $id_transaksi = $data['7'];
                            $dataspaket = mysqli_query($koneksi, "SELECT * FROM tb_detail_transaksi INNER JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id WHERE id_transaksi = '$id_transaksi'");
                            while ($datapaket = mysqli_fetch_assoc($dataspaket)) {
                                echo $datapaket['nama_paket'];
                                echo "<br>";
                            }
                            ?>
                        </span>
                        <span class="">
                            <?php
                            $id_transaksi = $data['7'];
                            $grand_total = mysqli_fetch_row(mysqli_query($koneksi, "SELECT SUM(total_harga) FROM tb_detail_transaksi INNER JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id WHERE id_transaksi = '$id_transaksi'"));
                            $pajak = $grand_total[0] * $data['16'];
                            $diskon = $grand_total[0] * $data['15'];
                            $total_keseluruhan = ($grand_total[0] + $data['14'] + $pajak) - $diskon;

                            echo "Total : Rp. " . number_format($total_keseluruhan, 0, ',', '.');
                            ?>
                        </span>
                    </div>
                    <div class="mb-2" style="border: <?php if ($_SESSION['role'] == 'owner') {
                        echo "none";
                    } ?>;">
                        <select <?php if ($_SESSION['role'] == 'owner') {
                            echo "hidden";
                        } ?> onchange="gantiStatus(this.options[this.selectedIndex].value, <?= $data['1'] ?>)"
                            class="px-2 py-1 border border-gray-300 bg-gray-600 rounded">
                            <option value="baru" <?php if ($data['17'] == 'baru') {
                                echo "selected";
                            } ?>>New</option>
                            <option value="proses" <?php if ($data['17'] == 'proses') {
                                echo "selected";
                            } ?>>Process</option>
                            <option value="selesai" <?php if ($data['17'] == 'selesai') {
                                echo "selected";
                            } ?>>Done</option>
                            <option value="diambil" <?php if ($data['17'] == 'diambil') {
                                echo "selected";
                            } ?>>Taked</option>
                        </select>
                        <script>
                            function gantiStatus(value, id) {
                                window.location = "dashboard.php?page=prosesStatusTransaksi&status=" + value + "&id=" + id;
                            }
                        </script>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
        $nowPage = strtok($_SERVER['REQUEST_URI'], '?'); // Get the current page URL without query parameters
        $currentQuery = $_SERVER['QUERY_STRING']; // Get the current query parameters
    
        // Construct the base URL with existing query parameters
        $baseUrl = $nowPage;
        if ($currentQuery !== '') {
            $baseUrl .= '?' . $currentQuery;
        }

        // Remove the 'viewPage' parameter from the query string
        $baseUrl = preg_replace('/[&?]viewPage=\d+/', '', $baseUrl);
        $baseUrl = rtrim($baseUrl, '&?'); // Remove trailing '&' or '?'
    
        // Append the 'status' parameter if it exists
        if (isset($_GET['status'])) {
            $baseUrl .= '&status=' . $_GET['status'];
        }

        ?>

        <div class="flex justify-start p-2 <?php if ($total_records_query <= 9) {
            echo "hidden";
        } ?>">
            <nav aria-label="Page navigation example">
                <ul class="inline-flex -space-x-px text-sm">
                    <li class="<?php if ($current_page == 1) {
                        echo "hidden";
                    } ?>">
                        <a href="<?= $baseUrl . '&viewPage=' . ($current_page - 1) ?>"
                            class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-1 border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                            << Before </a>
                    </li>
                    <?php
                    $min_page = max(1, $current_page - 3);
                    $max_page = min($total_pages, $current_page + 3);

                    for ($i = $min_page; $i <= $max_page; $i++) {
                        ?>
                        <li>
                            <a href="<?= $baseUrl . '&viewPage=' . $i ?>"
                                class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-1 border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php } ?>
                    <li class="<?php if ($current_page == $total_pages) {
                        echo "hidden";
                    } ?>">
                        <a href="<?= $baseUrl . '&viewPage=' . ($current_page + 1) ?>"
                            class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-1 border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                            Next >>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>


        <?php
} else {
    ?>
        <center class="w-[200px] mx-auto h-auto">

            <h1 class="text-[40px]">Sorry, no data have been found</h1>
            <a href="dashboard.php?page=outlet" class="no-underline text-[14px] text-blue-700 px-4 py-2">See others</a>

        </center>
    <?php } ?>
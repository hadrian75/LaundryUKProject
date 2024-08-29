<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
if (isset($_POST['selanjutnya'])) {
    $id_outlet = $_SESSION['id_outlet'];
    $nama_member = $_POST['id_member'];

    $kode_invoice = generateInvoiceCode($koneksi);
    $id_member = getMemberId($koneksi, $nama_member);
    $diskon = calculateDiscount($koneksi, $id_member);

    $transactionId = addTransaction($koneksi, $id_outlet, $kode_invoice, $id_member, $diskon);
    if ($transactionId) {
        $_SESSION['idtransaksi'] = $transactionId;
        header('Location: dashboard.php?page=detailTransaksi');
        exit;
    } else {
        echo "Failed to Add Transaction";
    }
}
//Untuk Membuat Kode Invoice
function generateInvoiceCode($koneksi)
{
    date_default_timezone_set("Asia/Makassar");
    $today = date("Y/m/d");
    $query = "SELECT MAX(kode_invoice) AS last_invoice FROM tb_transaksi WHERE kode_invoice LIKE 'INV/$today/%'";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);

    //Untuk Membuat Nomor Terakhir Invoice
    if ($data && $data['last_invoice']) {
        $invoiceNumber = (int) substr($data['last_invoice'], -1) + 1;
    } else {
        $invoiceNumber = 1;
    }

    return "INV/$today/$invoiceNumber";
}

//Untuk Mengambil ID Pelanggan
function getMemberId($koneksi, $nama_member)
{
    $stmt = $koneksi->prepare("SELECT id FROM tb_member WHERE nama = ?");
    $stmt->bind_param("s", $nama_member);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['id'];
    } else {
        return null; // Member not found or handle the error
    }
}

//Fungsi Diskon Jika User sudah Melakukan 3 Kali Transaksi
function calculateDiscount($koneksi, $id_member)
{
    $stmt = $koneksi->prepare("SELECT COUNT(*) AS trans_count FROM tb_transaksi WHERE id_member = ?");
    $stmt->bind_param("i", $id_member);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data['trans_count'] > 0 && $data['trans_count'] % 3 == 0) {
        return 0.1; // 10% discount
    } else {
        return 0; // No discount
    }
}


function addTransaction($koneksi, $id_outlet, $kode_invoice, $id_member, $diskon)
{
    date_default_timezone_set("Asia/Makassar");
    $tanggal = date('Y-m-d H:i:s');
    $tgl_bayar = "0000-00-00 00:00:00";
    // echo $tanggal;
    $batas_waktu = date('Y-m-d H:i:s', strtotime($tanggal . ' +3 days'));
    $biaya_tambahan = 0;
    $pajak = 0.0075;
    $status = "baru";
    $dibayar = "belum_dibayar";
    $id_user = $_SESSION['id_user'];

    //Menyiapkan PDO Statement
    $stmt = $koneksi->prepare("INSERT INTO tb_transaksi (id_outlet, kode_invoice, id_member, tgl,  batas_waktu,tgl_bayar,biaya_tambahan, diskon, pajak, status, dibayar, id_user) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isisssdddssi", $id_outlet, $kode_invoice, $id_member, $tanggal, $batas_waktu, $tgl_bayar, $biaya_tambahan, $diskon, $pajak, $status, $dibayar, $id_user);

    if ($stmt->execute()) {
        // Returns the last inserted ID
        return $koneksi->insert_id;
    } else {
        return false;
    }
}
$datas = mysqli_query($koneksi, "SELECT tb_transaksi.*, tb_outlet.nama AS namaOutlet
, tb_detail_transaksi.total_harga AS totalHarga FROM tb_transaksi INNER JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id INNER JOIN tb_detail_transaksi ON tb_transaksi.id  = tb_detail_transaksi.id_transaksi ORDER BY id DESC LIMIT 5");
$chartDataMonth = mysqli_query($koneksi, "SELECT MONTHNAME(tgl) AS month, COUNT(*) as akumulasi FROM tb_transaksi GROUP BY MONTH(tgl)");
$chartDataOutlet = mysqli_query($koneksi, "SELECT tb_outlet.nama, COUNT(tb_transaksi.id_outlet) as total_transaksi_outlet
    FROM tb_outlet
    INNER JOIN tb_transaksi ON tb_outlet.id = tb_transaksi.id_outlet
    GROUP BY tb_outlet.nama
    ORDER BY total_transaksi_outlet DESC
    LIMIT 5");

?>
<section class="w-full grid gap-2 grid-cols-2 my-2 px-8 mt-10">

    <div class="container mx-auto w-[480px] h-auto cols-1">
        <form action="" method="POST" class="bg-gray-900 p-10 rounded-lg">
            <div>
                <h1 class="text-xl font-bold text-white pb-4 py-1">
                    Search Member - Detail Transaksi
                </h1>
                <input type="text" list="name_member" name="id_member"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    id="" placeholder="Nama Member">
                <datalist id="name_member" required>
                    <?php
                    $sql = "SELECT * FROM tb_member";
                    $query = mysqli_query($koneksi, $sql);

                    while ($result = mysqli_fetch_assoc($query)) {
                        ?>
                        <option value="<?= $result['nama'] ?>">
                            <?= $result['nama'] ?>
                        </option>
                        <?php
                    }
                    ?>
                </datalist>
            </div>
            <?php
            if (@$_SESSION['idtransaksi']) {
                ?>
                <div class="grid grid-cols-2 mt-10 gap-6">

                    <div class="">
                        <input type="submit"
                            class="bg-blue-600 hover:bg-blue-700 cursor-pointer h-8 px-6 text-sm text-white  rounded-md "
                            value="SELANJUTNYA" name="selanjutnya">
                    </div>
                    <div class="">
                        <a href="dashboard.php?page=detailTransaksi"
                            class="bg-yellow-400 hover:bg-yellow-500 w-full h-8  flex items-center justify-center text-white text-sm rounded-md ">
                            Back To Last Transaction
                        </a>

                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="flex justify-start mt-10">
                    <input type="submit" value="SELANJUTNYA"
                        class="bg-blue-600 hover:bg-blue-700 cursor-pointer py-2 w-full text-large text-white text-[12px] rounded-md "
                        name="selanjutnya">
                </div>
                <?php
            }
            ?>

        </form>
    </div>
    <div class="grid grid-cols-3  gap-5">
        <div>
            <h1 class="text-black text-3xl font-semibold">
                Recently Added Transaksi
            </h1>
        </div>
        <?php
        while ($row = mysqli_fetch_array($datas)) {
            ?>
            <div class="childContentRecentUser bg-gray-900 text-gray-500 h-[75px] p-2 rounded-md">

                <span class="flex flex-start gap-1 items-center">
                    <p class="text-white text-[12px]">
                        <?= $row['kode_invoice'] ?>
                    </p>
                    <p>
                        |
                    </p>
                    <p class="text-white text-[12px]">
                        <?= $row['namaOutlet'] ?>
                    </p>
                </span>
                <h2 class="font-semibold text-md text-white mt-2">Total Harga :
                    <?= $row['totalHarga'] ?>
                </h2>
            </div>
            <?php
        }
        ?>
    </div>
    <div class=" my-5">

        <canvas id="monthlyTransactionChart" width="730" height="200"
            class="border-solid border-2 p-1 rounded-md my-2 mt-1"></canvas>
    </div>
    <div class="my-5">

        <canvas id="topOutletsChart" width="730" height="300"
            class="border-solid border-2 p-1 rounded-md my-2 mt-1"></canvas>
    </div>
    <script>
        var ctxMonth = document.getElementById('monthlyTransactionChart').getContext('2d');

        // Define the month labels using Chart.js Utils.months() function or manually
        var labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        // PHP code to fetch data from the database
        <?php
        $dataMonth = array();
        $labels = array();
        while ($row = mysqli_fetch_assoc($chartDataMonth)) {
            $labels[] = $row['month'];
            $dataMonth[] = $row['akumulasi'];
        }
        ?>

        // JavaScript code to create the monthly transaction counts chart
        var dataMonth = {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Monthly Transactions',
                backgroundColor: '#3498db',
                borderColor: '#3498db',
                borderWidth: 1,
                data: <?php echo json_encode($dataMonth); ?>
            }]
        };

        var optionsMonth = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Transaction Counts',
                    font: {
                        size: 16
                    },
                    padding: {
                        top: 10,
                        bottom: 10
                    }
                },
                legend: {
                    display: false
                }
            }
        };

        var myChartMonth = new Chart(ctxMonth, {
            type: 'line', // Change chart type to line
            data: dataMonth,
            options: optionsMonth
        });
    </script>
    <script>
        var ctxOutlet = document.getElementById('topOutletsChart').getContext('2d');

        <?php
        $labelsOutlet = [];
        $dataOutlet = [];

        while ($row = mysqli_fetch_assoc($chartDataOutlet)) {
            $labelsOutlet[] = $row['nama'];
            $dataOutlet[] = $row['total_transaksi_outlet'];
        }
        ?>

        // JavaScript code to create the top 5 outlets by transaction counts chart
        var dataOutlet = {
            labels: <?php echo json_encode($labelsOutlet); ?>,
            datasets: [{
                label: 'Total Transactions',
                backgroundColor: '#3b82f6',
                borderColor: '#fff',
                borderWidth: 1,
                data: <?php echo json_encode($dataOutlet); ?>
            }]
        };

        var optionsOutlet = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Top Outlets by Transaction Counts',
                    font: {
                        size: 16
                    },
                    padding: {
                        top: 10,
                        bottom: 10
                    }
                },
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        fontColor: '#333'
                    }
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            var label = data.labels[tooltipItem.index] || '';
                            var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                            return label + ': ' + value + ' transactions';
                        }
                    }
                }
            }
        };

        var myChartOutlet = new Chart(ctxOutlet, {
            type: 'bar',
            data: dataOutlet,
            options: optionsOutlet
        });
    </script>


</section>
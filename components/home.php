<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Include database connection file

// Total transactions by outlet
$chartDataOutletResult = mysqli_query($koneksi, "SELECT tb_outlet.nama, COUNT(tb_transaksi.id_outlet) AS total_transaksi
                                           FROM tb_outlet
                                           INNER JOIN tb_transaksi ON tb_outlet.id = tb_transaksi.id_outlet
                                           GROUP BY tb_outlet.nama ORDER BY total_transaksi DESC LIMIT 3");
$chartDataOutlet = [];
while ($row = mysqli_fetch_assoc($chartDataOutletResult)) {
    $chartDataOutlet[] = $row;
}

// Total members by gender
$chartDataGenderResult = mysqli_query($koneksi, "SELECT jenis_kelamin, COUNT(*) as total FROM tb_member GROUP BY jenis_kelamin");
$chartDataGender = [];
while ($row = mysqli_fetch_assoc($chartDataGenderResult)) {
    $chartDataGender[] = $row;
}

// Total orders by package
$chartDataPackageResult = mysqli_query($koneksi, "SELECT tb_paket.nama_paket, COUNT(tb_detail_transaksi.id_paket) as total_orders
                                            FROM tb_paket
                                            INNER JOIN tb_detail_transaksi ON tb_paket.id = tb_detail_transaksi.id_paket
                                            GROUP BY tb_paket.nama_paket
                                            ORDER BY total_orders DESC
                                            LIMIT 5");
$chartDataPackage = [];
while ($row = mysqli_fetch_assoc($chartDataPackageResult)) {
    $chartDataPackage[] = $row;
}

// Total transactions by month
$chartDataMonthResult = mysqli_query($koneksi, "SELECT MONTHNAME(tgl) AS month, COUNT(*) as akumulasi FROM tb_transaksi GROUP BY MONTH(tgl)");
$chartDataMonth = [];
while ($row = mysqli_fetch_assoc($chartDataMonthResult)) {
    $chartDataMonth[] = $row;
}
?>


<section class="bg-gray-50 p-3 sm:p-5 h-full min-h-screen">
    <center>
        <h1 class="text-bold text-6xl font-bold">Halo Selamat Datang
            <?= $_SESSION['username'] ?>
        </h1>
        <h1 class="text-bold text-4xl text-blue-600 font-bold">Selamat Bekerja
        </h1>
    </center>
    <h2 class="font-semibold text-xl text-center mt-10">Top Rekapan Data</h2>
    <div class="mt-2 grid grid-cols-2 gap-6">
        <div>
            <!-- Chart to display total transactions by outlet -->
            <canvas id="outletChart" class="border-2 border-gray-300"></canvas>
        </div>
        <div>
            <!-- Chart to display total members by gender -->
            <canvas id="genderChart" class="border-2 border-gray-300"></canvas>
        </div>
        <div>
            <!-- Chart to display total orders by package -->
            <canvas id="packageChart" class="border-2 border-gray-300"></canvas>
        </div>
        <div>
            <!-- Chart to display total transactions by month -->
            <canvas id="monthlyChart" class="border-2 border-gray-300"></canvas>
        </div>
    </div>
</section>

<script>
    var outletData = <?php echo json_encode($chartDataOutlet); ?>;
    var genderData = <?php echo json_encode($chartDataGender); ?>;
    var packageData = <?php echo json_encode($chartDataPackage); ?>;
    var monthlyData = <?php echo json_encode($chartDataMonth); ?>;

    function createChart(id, labels, data, label, title) {
        var ctx = document.getElementById(id).getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: title
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Create charts with the provided data
    createChart('outletChart', outletData.map(data => data.nama), outletData.map(data => data.total_transaksi), 'Total Transactions', 'Total Transactions by Outlet');
    createChart('genderChart', genderData.map(data => data.jenis_kelamin), genderData.map(data => data.total), 'Total Members', 'Total Members by Gender');
    createChart('packageChart', packageData.map(data => data.nama_paket), packageData.map(data => data.total_orders), 'Total Orders', 'Total Orders by Package');
    createChart('monthlyChart', monthlyData.map(data => data.month), monthlyData.map(data => data.akumulasi), 'Total Transactions', 'Total Transactions by Month');
</script>
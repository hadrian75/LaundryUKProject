<?php

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$datas = mysqli_query($koneksi, "SELECT tb_paket.*, tb_outlet.nama AS nama_outlet FROM tb_paket INNER JOIN tb_outlet ON tb_paket.id_outlet = tb_outlet.id ORDER BY id DESC LIMIT 8");
if (mysqli_num_rows($datas) > 0) {
    $chartData = mysqli_query($koneksi, "SELECT tb_paket.nama_paket, COUNT(tb_detail_transaksi.id_paket) as total_orders
    FROM tb_paket
    INNER JOIN tb_detail_transaksi  ON tb_paket.id = tb_detail_transaksi.id_paket
    GROUP BY tb_paket.nama_paket
    ORDER BY total_orders DESC
    LIMIT 5;
    ");
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tambah Paket</title>
    </head>

    <body>
        <section class="w-full grid grid-cols-2 my-2 px-8 mt-10">

            <form action="tambah/prosesTambahPaket.php" method="POST"
                class="bg-gray-900 w-[500px] max-h-[600px] col-1 p-10  rounded-md">
                <h1 class="text-white text-2xl mb-5">Tambah Paket</h1>

                <div class="mb-5">
                    <label for="namaPaket" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                        Paket</label>
                    <input type="text" id="namaPaket" name="namaPaket"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Isi Nama Paket Anda" required>
                </div>
                <div class="mb-5">
                    <label for="idOutlet" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                        Outlet</label>
                    <select name="idOutlet" id="idOutlet"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="" selected hidden>Pilih Outlet</option>
                        <?php
                        $paketSql = mysqli_query($koneksi, "SELECT * FROM tb_outlet");
                        while ($data = mysqli_fetch_assoc($paketSql)) {
                            ?>
                            <option value="<?= $data['id'] ?>">
                                <?= $data["nama"] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-5">
                    <label for="jenisPaket" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis
                        Paket</label>
                    <select name="jenisPaket" id="jenisPaket"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        id="">
                        <option value="" selected hidden>Pilih Jenis</option>
                        <option value="kiloan">Kiloan</option>
                        <option value="selimut">Selimut</option>
                        <option value="bed_cover">Bed Cover</option>
                        <option value="kaos">Kaos</option>
                        <option value="lain">Lainnya</option>
                    </select>
                </div>
                <div class="mb-5">
                    <label for="harga" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga
                        Paket</label>
                    <input type="text" id="harga" name="harga" rows="5"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 p-2"
                        placeholder="Isi Harga Paket">
                </div>

                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
            </form>
            <div class="grid grid-rows-2">
                <div class="grid grid-cols-3 gap-x-2">
                    <h1 class="text-black text-3xl font-semibold max-h-[75px]">
                        Recently Added Package
                    </h1>
                    <?php
                    while ($row = mysqli_fetch_array($datas)) {
                        $namaPackage = $row['nama_paket'];
                        $firstLetter = substr($namaPackage, 0, 1);

                        // Check if the first letter is an alphabet character
                        if (ctype_alpha($firstLetter)) {
                            // Wrap the first letter in a span tag
                            $firstMemberLetter = '<span class="flex object-cover h-[32px] w-[32px] justify-center items-center bg-blue-500 text-white rounded-[25px]">' . $firstLetter . '</span>';
                        }
                        ?>
                        <div class="childContentRecentUser bg-gray-900 text-gray-500 h-[75px] p-2 rounded-md">
                            <div class="userCardName flex items-center gap-2">
                                <?= $firstMemberLetter ?>
                                <span class="flex gap-1 items-center">
                                    <p class="text-white text-[12px]">
                                        <?= $namaPackage ?>
                                    </p>
                                    |
                                    <p class="text-[10px]">
                                        <?php
                                        echo $row['nama_outlet'];
                                        ?>
                                    </p>

                                </span>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="my-5">

                    <canvas id="paketChart" width="730" height="300"
                        class="border-solid border-2 p-1 rounded-md my-2 mt-1"></canvas>
                </div>
            </div>


            <script>
                var ctx = document.getElementById('paketChart').getContext('2d');

                // PHP code to fetch data
                <?php
                // Fetching data from database
            
                // Creating arrays to store labels and data
                $labels = [];
                $data = [];

                // Fetching data from the query result
                while ($row = mysqli_fetch_assoc($chartData)) {
                    $labels[] = $row['nama_paket'];
                    $data[] = $row['total_orders'];
                }
                ?>

                // JavaScript code to create the chart
                var data = {
                    labels: <?php echo json_encode($labels); ?>,
                    datasets: [{
                        label: 'Total',
                        backgroundColor: ['#3498db', '#e74c3c', '#2ecc71', '#f1c40f', '#9b59b6'], // You can add more colors as needed
                        borderColor: '#fff',
                        borderWidth: 1,
                        data: <?php echo json_encode($data); ?>
                    }]
                };

                var options = {
                    responsive: false,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Most Used Package', // Title of the chart
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
                                    return label + ': ' + value + ' members';
                                }
                            }
                        }
                    }
                };

                var myChart = new Chart(ctx, {
                    type: 'pie',
                    data: data,
                    options: options
                });
            </script>
        </section>
    </body>
    <?php
} ?>

</html>
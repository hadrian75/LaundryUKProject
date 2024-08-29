<?php
$datas = mysqli_query($koneksi, "SELECT * FROM tb_user ORDER BY id DESC LIMIT 8");
if (mysqli_num_rows($datas) > 0) {
        $chartData = mysqli_query($koneksi, "SELECT tb_user.nama, COUNT(tb_transaksi.id_user) as total_job
        FROM tb_user
        INNER JOIN tb_transaksi  ON tb_user.id = tb_transaksi.id_user
        GROUP BY tb_user.nama
        ORDER BY total_job DESC
        LIMIT 5;
        ");
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Tambah User</title>
        </head>

        <body>
                <section class="w-full grid grid-cols-2 my-2 px-8 mt-10">

                        <form action="prosesRegisterEnkripsi.php" class="bg-gray-900 w-[500px] col-1 p-10  rounded-md"
                                method="POST" class="bg-gray-600 max-w-xl mx-auto p-10 mt-5 rounded-md">
                                <h1 class="text-white text-2xl mb-5">Registrasi User</h1>

                                <div class="mb-5">
                                        <label for="namaLengkap"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                                                Lengkap</label>
                                        <input type="text" id="namaLengkap" name="namaLengkap"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="Isi Nama Lengkap..." required>
                                </div>
                                <div class="mb-5">
                                        <label for="username"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                                        <input type="text" id="username" name="username"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="Isi Username..." required>
                                </div>
                                <div class="mb-5">
                                        <label for="pass"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                                        <input type="password" id="pass" name="pass"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="&#8226&#8226&#8226&#8226&#8226&#8226&#8226&#8226&#8226" required>
                                </div>
                                <div class="mb-5">
                                        <label for="idOutlet"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
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
                                        <label for="role"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                                        <select name="role" id="role"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option value="" selected hidden>Pilih Role</option>
                                                <option value="kasir">Kasir</option>
                                                <option value="owner">Owner</option>
                                                <option value="admin">Admin</option>
                                        </select>
                                </div>

                                <button type="submit"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                        </form>
                        <div class="grid grid-rows-2">
                                <div class="grid grid-cols-3  gap-5">
                                        <div>
                                                <h1 class="text-black text-3xl font-semibold">
                                                        Recently Added User
                                                </h1>
                                        </div>
                                        <?php
                                        while ($row = mysqli_fetch_array($datas)) {
                                                $memberName = $row['username'];
                                                $firstLetter = substr($memberName, 0, 1);

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
                                                                                <?= $memberName ?>
                                                                        </p>
                                                                        |
                                                                        <p class="text-[10px]">
                                                                                <?php
                                                                                echo $row['nama'];
                                                                                ?>
                                                                        </p>

                                                                </span>
                                                        </div>
                                                </div>
                                                <?php
                                        }
                                        ?>
                                </div>
                                <div class="grid grid-cols-2">

                                        <canvas id="userChart" width="730" height="200"
                                                class="border-solid border-2 p-1 rounded-md my-2 mt-1"></canvas>
                                </div>
                        </div>
                        <script>
                                var ctx = document.getElementById('userChart').getContext('2d');

                                // PHP code to fetch data
                                <?php
                                // Fetching data from database
                        
                                // Creating arrays to store labels and data
                                $labels = [];
                                $data = [];

                                // Fetching data from the query result
                                while ($row = mysqli_fetch_assoc($chartData)) {
                                        $labels[] = $row['nama'];
                                        $data[] = $row['total_job'];
                                }
                                ?>

                                // JavaScript code to create the chart
                                var data = {
                                        labels: <?php echo json_encode($labels); ?>,
                                        datasets: [{
                                                label: 'Total Transasction',
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
                                                        text: 'Most Active User', // Title of the chart
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
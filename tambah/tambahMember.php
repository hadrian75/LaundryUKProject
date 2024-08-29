<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$datas = mysqli_query($koneksi, "SELECT * FROM tb_member ORDER BY id DESC LIMIT 8");
if (mysqli_num_rows($datas) > 0) {
    $chartData = mysqli_query($koneksi, "SELECT jenis_kelamin, COUNT(*) as total FROM tb_member GROUP BY jenis_kelamin");
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tambah Pelanggan</title>
    </head>

    <body>
        <section class="w-full grid grid-cols-2 my-2 px-8 mt-10">
            <form action="tambah/prosesTambahMember.php" method="POST" class="bg-gray-900 w-[500px] col-1 p-10  rounded-md">
                <h1 class="text-white text-2xl mb-5">Registrasi Pelanggan</h1>
                <div class="mb-5">
                    <label for="namaMember" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                        Member</label>
                    <input type="text" id="namaMember" name="namaMember"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Isi Nama Member " required>
                </div>
                <div class="mb-5">
                    <label for="alamatMember"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat</label>
                    <input type="text" id="alamatMember" name="alamat" rows="5"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 p-2"
                        placeholder="Isi Alamat Member">
                </div>
                <div class="mb-5">
                    <label for="telp" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telepon</label>
                    <input type="text" id="telp" name="telepon"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Isi Nomor Telepon" required>
                </div>
                <div class="mb-5">
                    <label for="jenis_kelamin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis
                        Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="" selected hidden>Pilih Gender</option>
                        <option value="male">Laki - laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                </div>

                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
            </form>
            <div class="grid grid-rows-2">
                <div class="grid grid-cols-3  gap-5">
                    <div>
                        <h1 class="text-black text-3xl font-semibold">
                            Recently Added Member
                        </h1>
                    </div>
                    <?php
                    while ($row = mysqli_fetch_array($datas)) {
                        $memberName = $row['nama'];
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
                                        if ($row[3] == 'male') {
                                            echo "Laki - Laki";
                                        } else
                                            echo "Perempuan";
                                        ?>
                                    </p>

                                </span>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="grid grid-cols-2 my-5">

                    <canvas id="memberChart" width="730" height="200"
                        class="border-solid border-2 p-1 rounded-md my-2 mt-1"></canvas>
                </div>
            </div>


            <script>
                var ctx = document.getElementById('memberChart').getContext('2d');

                var data = {
                    labels: ['Laki - laki', 'Perempuan'], // Labels for each data point
                    datasets: [{
                        label: 'Total',
                        backgroundColor: ['#3498db', '#e74c3c'], // Background color for each segment
                        borderColor: '#fff', // Border color
                        borderWidth: 1, // Border width
                        data: [
                            <?php
                            $maleCount = 0;
                            $femaleCount = 0;
                            while ($row = mysqli_fetch_assoc($chartData)) {
                                if ($row['jenis_kelamin'] == 'male') {
                                    $maleCount = $row['total'];
                                } else if ($row['jenis_kelamin'] == 'female') {
                                    $femaleCount = $row['total'];
                                }
                            }
                            echo $maleCount . ', ' . $femaleCount; // Output both male and female counts
                            ?>
                        ] // Actual data values
                    }]
                };

                // Configure the chart options
                var options = {
                    responsive: false, // Disable automatic resizing
                    maintainAspectRatio: false, // Disable aspect ratio
                    plugins: {
                        title: {
                            display: true,
                            text: 'Chart Member by Gender', // Title text
                            font: {
                                size: 16 // Title font size
                            },
                            padding: {
                                top: 10, // Top padding
                                bottom: 10 // Bottom padding
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

                // Create the chart
                var myChart = new Chart(ctx, {
                    type: 'pie', // Specify the type of chart (e.g., pie chart)
                    data: data, // Provide the data for the chart
                    options: options // Provide the chart options
                });
            </script>



        </section>

    </body>
    <?php
} ?>

</html>
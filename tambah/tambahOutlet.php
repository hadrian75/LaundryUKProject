<?php
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}
$datas = mysqli_query($koneksi, "SELECT * FROM tb_outlet ORDER BY id DESC LIMIT 8");
if (mysqli_num_rows($datas) > 0) {
  $chartData = mysqli_query($koneksi, "SELECT tb_outlet.nama, COUNT(tb_transaksi.id_outlet) AS total_transaksi
  FROM tb_outlet
  INNER JOIN tb_transaksi ON tb_outlet.id = tb_transaksi.id_outlet
  GROUP BY tb_outlet.nama ORDER BY total_transaksi DESC LIMIT 3;
  ");
  ?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Outlet</title>
  </head>

  <body>
    <section class="w-full grid grid-cols-2 my-2 px-8 mt-10">

      <form action="tambah/prosesTambahOutlet.php" method="POST" class="bg-gray-900 w-[500px] col-1 p-10  rounded-md">
        <h1 class="text-white text-2xl mb-5">Tambah Outlet</h1>
        <div class="mb-5">
          <label for="namaOutlet" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
            Outlet</label>
          <input type="text" id="namaOutlet" name="namaOutlet"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Isi Nama Outlet Anda" required>
        </div>
        <div class="mb-5">
          <label for="telp" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telepon</label>
          <input type="text" id="telp" name="telepon"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Isi Nomor Outlet" required>
        </div>
        <div class="mb-5">
          <label for="alamatOutlet" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat
            Outlet</label>
          <input type="text" id="alamatOutlet" name="alamat" rows="5"
            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 p-2"
            placeholder="Isi Alamat Outlet">
        </div>

        <button type="submit"
          class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
      </form>
      <div class="grid grid-rows-2">
        <div class="grid grid-cols-3  gap-5">
          <div>
            <h1 class="text-black text-3xl font-semibold">
              Recently Added Outlet
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
                <span class="flex flex-col ">
                  <p class="text-white text-[12px]">
                    <?= $memberName ?>
                  </p>

                  <p class="text-[10px]">
                    <?= $row['alamat']; ?>
                  </p>

                </span>
              </div>
            </div>
            <?php
          }
          ?>
        </div>
        <div class="grid grid-cols-2">

          <canvas id="outletChart" width="730" height="200"
            class="border-solid border-2 p-1 rounded-md my-2 mt-1"></canvas>
        </div>
      </div>
    </section>

    <script>
      const data = JSON.parse('<?php echo json_encode($chartData); ?>');
      const labels = [];
      const dataValues = [];

      <?php
      while ($row = mysqli_fetch_assoc($chartData)) {
        ?>
        labels.push("<?php echo $row["nama"]; ?>"); // Replace with your label column
        dataValues.push("<?php echo $row['total_transaksi']; ?>");
      <?php }
      ?>

      const ctx = document.getElementById('outletChart').getContext('2d');
      const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Total Transaksi', // Optional label for the data series
            data: dataValues,
            backgroundColor: "#3b82f6"
          }
          ]
        },
        options: {
          responsive: false,
          maintainAspectRatio: false,
          plugins: {
            title: {
              display: true,
              text: 'Most Transaction Happened by Outlet', // Title of the chart
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
                  return data.labels[tooltipItem.index] + ': ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                }
              }
            }
          }

        }
      });
    </script>
    <?php
} ?>
</body>

</html>
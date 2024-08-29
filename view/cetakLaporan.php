<?php
include "koneksi.php";

$tanggalAwal = isset($_POST['tanggalAwal']) ? date('Y-m-d', strtotime($_POST['tanggalAwal'])) : "";
$tanggalAkhir = isset($_POST['tanggalAkhir']) ? date('Y-m-d', strtotime($_POST['tanggalAkhir'])) : "";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan</title>
    <style>
        @media print {
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        hr {
            height: 4px !important;
        }
    </style>
</head>

<body class="box-content p-2">
    <h2 class="text-[32px] text-black font-bold">Laporan Pendapatan</h2>
    <h3 class="text-[24] font-bold">Periode:
        <?= $tanggalAwal ?> sampai
        <?= $tanggalAkhir ?>
    </h3>

    <?php
    if (isset($_SESSION['role']) && ($_SESSION['role'] == "admin" || $_SESSION['role'] == "owner")) {
        $namaPaketQuery = "SELECT nama_paket, COUNT(nama_paket) AS jumlah_penggunaan FROM tb_transaksi INNER JOIN tb_detail_transaksi ON tb_transaksi.id = tb_detail_transaksi.id_transaksi INNER JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id WHERE tgl BETWEEN '$tanggalAwal 00:00:00' AND '$tanggalAkhir 23:59:59' GROUP BY nama_paket ORDER BY jumlah_penggunaan DESC";
        $namaPaketResult = mysqli_query($koneksi, $namaPaketQuery);
        $namaPaket = mysqli_fetch_assoc($namaPaketResult);
        echo "<h3><b>Paket yang sering dipesan pelanggan: " . @$namaPaket['nama_paket'] . "</b></h3>";
    }
    ?>

    <hr class="h-[4px] bg-black my-2">

    <?php
    if (isset($_SESSION['role']) && ($_SESSION['role'] == "admin" || $_SESSION['role'] == "owner")) {
        $queryOutlet = mysqli_query($koneksi, "SELECT tb_outlet.id AS id_outlet, tb_outlet.nama AS nama_outlet FROM tb_detail_transaksi INNER JOIN tb_transaksi ON tb_detail_transaksi.id_transaksi = tb_transaksi.id INNER JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id WHERE tb_transaksi.tgl BETWEEN '$tanggalAwal 00:00:00' AND '$tanggalAkhir 23:59:59' AND dibayar = 'dibayar' GROUP BY tb_outlet.id");
    } else {
        $idOutlet = $_SESSION['id_outlet'];
        $queryOutlet = mysqli_query($koneksi, "SELECT tb_outlet.id AS id_outlet, tb_outlet.nama AS nama_outlet FROM tb_detail_transaksi INNER JOIN tb_transaksi ON tb_detail_transaksi.id_transaksi = tb_transaksi.id INNER JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id WHERE tb_transaksi.tgl BETWEEN '$tanggalAwal 00:00:00' AND '$tanggalAkhir 23:59:59' AND dibayar = 'dibayar' AND tb_outlet.id = $idOutlet GROUP BY tb_outlet.id");
    }
    ?>

    <center>
        <?php
        $totalSemuaOutlet = 0; // Initialize total for all outlets
        if (mysqli_num_rows($queryOutlet) > 0) {
            while ($baris_outlet = mysqli_fetch_assoc($queryOutlet)) {
                $id_outlet = $baris_outlet['id_outlet'];
                $query = mysqli_query($koneksi, "SELECT tb_transaksi.id AS id_transaksi, tb_member.nama AS nama_member, tb_transaksi.pajak AS pajak, tb_transaksi.diskon AS diskon, tb_transaksi.biaya_tambahan AS biaya_tambahan FROM tb_detail_transaksi INNER JOIN tb_transaksi ON tb_detail_transaksi.id_transaksi = tb_transaksi.id INNER JOIN tb_member ON tb_transaksi.id_member = tb_member.id INNER JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id INNER JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id INNER JOIN tb_user ON tb_transaksi.id_user = tb_user.id WHERE tb_transaksi.tgl BETWEEN '$tanggalAwal 00:00:00' AND '$tanggalAkhir 23:59:59' AND dibayar = 'dibayar' AND tb_outlet.id = '$id_outlet'");

                // Display outlet name
                echo "";
                // Display transactions for the outlet
                if (mysqli_num_rows($query) > 0) {
                    echo "<table>";
                    echo "<tr><th colspan='4' class='bg-blue-500'><h2 class='text-left font-bold text-lg'>Nama Outlet : " . $baris_outlet['nama_outlet'] . "</h2></th></tr>";
                    echo "<tr><th>No</th><th>Nama Pelanggan</th><th>Detail Pesanan</th><th>Total Harga</th></tr>";
                    $no = 1;
                    $totalOutlet = 0;
                    while ($baris = mysqli_fetch_assoc($query)) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . $baris["nama_member"] . "</td>";
                        echo "<td>";
                        $id_transaksi = $baris["id_transaksi"];
                        $queryPaket = mysqli_query($koneksi, "SELECT nama_paket, quantity FROM tb_detail_transaksi INNER JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id WHERE id_transaksi = $id_transaksi");
                        while ($dataPaket = mysqli_fetch_assoc($queryPaket)) {
                            echo $dataPaket["nama_paket"] . " x " . $dataPaket['quantity'] . "<br>";
                        }
                        echo "</td>";
                        echo "<td>";
                        $grand_total_result = mysqli_query($koneksi, "SELECT SUM(total_harga) FROM tb_detail_transaksi INNER JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id WHERE id_transaksi = $id_transaksi");
                        $grand_total_row = mysqli_fetch_row($grand_total_result);
                        $grand_total = $grand_total_row[0];
                        $pajak = $grand_total * $baris['pajak'];
                        $diskon = $grand_total * $baris['diskon'];
                        $total_keseluruhan = ($grand_total + $baris['biaya_tambahan'] + $pajak) - $diskon;
                        $tampil_total = number_format($total_keseluruhan, 0, ',', '.');
                        echo 'Rp. ' . $tampil_total;
                        echo "</td>";
                        echo "</tr>";
                        $totalOutlet += $total_keseluruhan;
                    }
                    echo "<tr><td colspan='3'><b>Total Pendapatan Outlet:</b></td><td><b>Rp. " . number_format($totalOutlet, 0, ",", ".") . "</b></td></tr>";
                    echo "</table>";
                    $totalSemuaOutlet += $totalOutlet; // Accumulate total for all outlets
                } else {
                    echo "No transactions found for this outlet.";
                }
            }
        } else {
            echo "No outlets found.";
        }

        // Display total income from all outlets
        echo "<h2 class='text-left font-bold text-lg pt-4'>Total Pendapatan Semua Outlet : Rp. " . number_format($totalSemuaOutlet, 0, ",", ".") . "</h2>";
        ?>
    </center>

    <script>
        window.print();
    </script>
</body>



</html>
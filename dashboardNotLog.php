<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}
include "koneksi.php"; ?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<section class="bg-gray-50  p-3 sm:p-5 h-full min-h-screen  ">
    <?php include ("components/navbar.php") ?>
    <div class="container mx-auto flex max-h-[80%] justify-betweenpy-8 px-4">
        <div class="left-column flex-1 p-8">
            <img class=" mb-4 object-cover h-[40%] rounded-lg w-full" src="img/hero1.jpg " alt="Logo">
            <h1 class="text-black font-bold text-lg my-4">Logo</h1>
            <p class="motto text-2xl font-bold mb-4">D'Wash? <span class="text-blue-600">Memberikan Kelembutan pada
                    Setiap Serat</span></p>
            <p class="text-md font-medium text-justify pr-10">
                D'wash, pilihan utama untuk layanan laundry terbaik di Bali. Menawarkan keahlian profesional, teknologi
                canggih, dan pelayanan ramah, kami memberikan kepuasan maksimal dalam mencuci pakaian Anda.
            </p>
            <a href="login.php">
                <button class=" bg-blue-500 text-white font-semibold mt-10 py-2 px-4 rounded-lg hover:bg-blue-600">Join
                    Sekarang</button></a>
        </div>
        <div class="right-column flex-1 p-8">
            <img class="boject-cover h-full rounded-md w-full" src="img/hero2.jpg" alt="Laundry Image">
        </div>
    </div>
    <?php include ("components/footer.php") ?>
</section>
<?php
ob_end_flush(); // Flush the output buffer and send the content to the browser
?>
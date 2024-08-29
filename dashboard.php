<?php
ob_start();
session_start();
include "koneksi.php";
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

?>
<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @media print {

            .no-print {
                display: none !important;
            }

            button {
                display: none;
            }

            .headGrid {
                grid-template-columns: auto auto !important;
                width: 100% !important;
                padding: 12px;
                margin: 0;
                gap: 10px;
            }

            .childGridOne {
                margin: 0 !important;
                border: 1px solid black;
                padding: 1px !important;

                * {
                    color: black !important;

                }
            }

            .childGridTwo {
                margin: 0 !important;
                border: 1px solid black;
                padding: 0 !important;
            }

            .print {
                display: block !important;
            }

            .changeStylePrint {
                display: flex !important;
                flex-direction: row;
                justify-content: start;
                align-items: center;
                margin: 0 auto !important;
            }
        }
    </style>
</head>

<body>
    <?php include("components/navbar.php");
    @$page = $_GET["page"];
    switch ($page) {
        case 'home':
            include "components/home.php";
            break;

        case 'outlet':
            include "view/viewOutlet.php";
            break;
        case 'tambahOutlet':
            include "tambah/tambahOutlet.php";
            break;
        case 'editOutlet':
            include "edit/editOutlet.php";
            break;
        case 'deleteOutlet':
            include "delete/deleteOutlet.php";
            break;
        case 'prosesEditOutlet':
            include "edit/prosesEditOutlet.php";
            break;

        case 'member':
            include "view/viewMember.php";
            break;
        case 'tambahMember':
            include "tambah/tambahMember.php";
            break;
        case 'editMember':
            include "edit/editMember.php";
            break;
        case 'deleteMember':
            include "delete/deleteMember.php";
            break;
        case 'prosesEditMember':
            include "edit/prosesEditMember.php";
            break;


        case 'user':
            include "view/viewUser.php";
            break;
        case 'register':
            include "register.php";
            break;
        case 'editUser':
            include "edit/editUser.php";
            break;
        case 'deleteUser':
            include "delete/deleteUser.php";
            break;
        case 'prosesEditUser':
            include "edit/prosesEditUser.php";
            break;


        case 'paket':
            include "view/viewPaket.php";
            break;
        case 'tambahPaket':
            include "tambah/tambahPaket.php";
            break;
        case 'editPaket':
            include "edit/editPaket.php";
            break;
        case 'prosesEditPaket':
            include "edit/prosesEditPaket.php";
            break;

        case 'transaksi':
            include "view/viewTransaksi.php";
            break;
        case 'tambahTransaksi':
            include "tambah/tambahTransaksi.php";
            break;
        case 'editTransaksi':
            include "edit/editTransaksi.php";
            break;
        case 'detailTransaksi':
            include "tambah/detailTransaksi.php";
            break;

        case 'laporan':
            include "view/laporan.php";
            break;

        case 'prosesStatusTransaksi':
            include "tambah/prosesStatusTransaksi.php";
            break;


        case 'logout':
            include "logout.php";
            break;

        case 'cetakLaporan':
            include "view/cetakLaporan.php";
            break;



        default:
            include "components/home.php";
            break;
    }
    ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const dropdownButton = document.getElementById("filterDropdownButton");
            const dropdownMenu = document.getElementById("filterDropdown");

            dropdownButton.addEventListener("click", function () {
                dropdownMenu.classList.toggle("hidden");
            });

            document.addEventListener("click", function (event) {
                if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.add("hidden");
                }
            });
        });

        function showDropdown(dropdownId) {
            var dropdown = document.getElementById(dropdownId);
            dropdown.classList.toggle("hidden");
        }

        document.addEventListener('DOMContentLoaded', function () {
            var profileBtn = document.getElementById('btnProfile');
            var profileDropdown = document.getElementById('profileDropdown');

            profileBtn.addEventListener('click', function () {
                showDropdown('profileDropdown');
            });

            var navbarBtn = document.getElementById('dropdownNavbarLink');
            var navbarDropdown = document.getElementById('dropdownNavbar');

            navbarBtn.addEventListener('click', function () {
                showDropdown('dropdownNavbar');
            });
        });



    </script>
    <section class="w-full ">
        <?php include("components/footer.php"); ?>

    </section>
</body>

</html>
<?php
ob_end_flush();
?>
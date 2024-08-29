<?php
if ($_SESSION['username'] == "") {
    header("Location:login.php");
    exit(); // Add exit to stop further execution
}

require_once "koneksi.php"; // Include your database connection file

$id = 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filterGender = isset($_GET['jenis_kelamin']) ? $_GET['jenis_kelamin'] : '';

$dataQuery = "SELECT * FROM tb_member";
$filterConditions = array();

if ($search != "") {
    // If search query is not empty
    $searchTerm = mysqli_real_escape_string($koneksi, "%$search%");
    $filterConditions[] = "nama LIKE '$searchTerm'";
}
if ($filterGender != '') {
    $filterConditions[] = "jenis_kelamin = '$filterGender'";
}

if (!empty($filterConditions)) {
    $dataQuery .= " WHERE " . implode(" AND ", $filterConditions);
}

$records_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$current_page = isset($_GET['viewPage']) ? intval($_GET['viewPage']) : 1;

// Calculate offset based on current page
$offset = ($current_page - 1) * $records_per_page;

// Query to get total number of records
$total_records_query = "SELECT COUNT(*) AS total FROM tb_member";
if (!empty($filterConditions)) {
    $total_records_query .= " WHERE " . implode(" AND ", $filterConditions);
}
$total_records_result = $koneksi->query($total_records_query);
$total_records = $total_records_result->fetch_assoc()['total'];

// Calculate total number of pages
$total_pages = ceil($total_records / $records_per_page);

// Append filter parameters to pagination links
$pagination_params = "";
if (!empty($filterConditions)) {
    $pagination_params = "&" . http_build_query(array('search' => $search, 'jenis_kelamin' => $filterGender));
}

// Query to retrieve records for the current page
$dataQuery .= " LIMIT $offset, $records_per_page";
$result = $koneksi->query($dataQuery);

$datas = mysqli_query($koneksi, $dataQuery);
if (mysqli_num_rows($datas) != 0) {
    ?>

    <section class="bg-gray-50  p-3 sm:p-5 h-auto ">
        <div class="mx-auto max-w-screen-2xl px-4 lg:px-12">
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <form class="flex items-center" method="GET">
                            <input type="hidden" name="page"
                                value="<?php echo isset($_GET['page']) ? $_GET['page'] : ''; ?>">
                            <!-- Include the current page value -->

                            <label for="simple-search" class="sr-only">Search</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="simple-search" name="search"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Search Member Name" value="<?= $search ?>">
                            </div>
                        </form>
                    </div>
                    <div
                        class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                        <a href="dashboard.php?page=tambahMember">
                            <button type="button"
                                class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                </svg>
                                Tambah Member
                            </button>
                        </a>
                        <form class="flex items-center space-x-3 w-full md:w-auto relative" action="" id="filterGender"
                            method="GET">
                            <input type="hidden" name="page"
                                value="<?php echo isset($_GET['page']) ? $_GET['page'] : ''; ?>">
                            <select name="jenis_kelamin" id="jenis_kelamin"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected hidden>Pilih Gender</option>
                                <option value="male" <?php if ($filterGender === "male") {
                                    echo "selected";
                                } ?>>Laki - laki
                                </option>
                                <option value="female" <?php if ($filterGender === "female") {
                                    echo "selected";
                                } ?>>Perempuan
                                </option>
                            </select>
                        </form>

                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-x text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3 ">ID</th>
                                <th scope="col" class="px-4 py-3 ">Nama Member</th>
                                <th scope="col" class="px-4 py-3 ">Alamat</th>
                                <th scope="col" class="px-4 py-3 ">Jenis Kelamin</th>
                                <th scope="col" class="px-4 py-3 ">Telepon</th>
                                <th scope="col" class="px-4 py-3 text-center <?php if ($_SESSION['role'] != "admin") {
                                    echo "hidden";
                                } ?>">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            while ($data = mysqli_fetch_assoc($datas)) {

                                $idMember = $data['id'];
                                $checkQuery = "SELECT * 
                    FROM tb_member
                    INNER JOIN tb_transaksi ON tb_transaksi.id_member = tb_member.id
                    WHERE tb_member.id = '$idMember'";
                                $hideDelete = mysqli_query($koneksi, $checkQuery);
                                @$hasConnections = mysqli_num_rows($hideDelete) > 0;
                                ?>
                                <tr class="border-b dark:border-gray-700">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <?= $id++ ?>
                                    </th>
                                    <td class="px-4 py-3 ">
                                        <?= $data['nama'] ?>
                                    </td>
                                    <td class="px-4 py-3 ">
                                        <?= $data['alamat'] ?>
                                    </td>
                                    <td class="px-4 py-3 ">
                                        <?php
                                        if ($data['jenis_kelamin'] == "male") {
                                            echo "Laki - Laki";
                                        } else {
                                            echo "Perempuan";
                                        }
                                        ?>
                                    </td>
                                    <td class="px-4 py-3 ">
                                        <?= $data['telepon'] ?>
                                    </td>


                                    <td class="px-3 py-3 flex  gap-2 items-center justify-center <?php if ($_SESSION['role'] != "admin") {
                                        echo "hidden";
                                    } ?>">
                                        <button type="button"
                                            class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                            <a href="dashboard.php?page=editMember&id=<?= $data['id'] ?>">Edit</a>
                                        </button>
                                        <button type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800  
                            <?php if ($hasConnections) {
                                echo "disabled aria-disabled='true' onclick='return false;'";
                            } ?>
                            ">
                                            <a href="<?php if (!$hasConnections) {
                                                echo 'dashboard.php?page=deleteMember&id=' . $data['id'];
                                            } ?>" class="<?php if ($hasConnections) {
                                                 echo "disabled aria-disabled='true' cursor-not-allowed w-full h-auto  ";
                                             } ?>">Delete</a>
                                        </button>
                                    </td>
                                </tr>

                            <?php } ?>


                        </tbody>
                    </table>
                    <?php include ("components/pagination.php") ?>
                </div>
            </div>
        </div>
    </section>
    <?php
} else {
    ?>
    <center class="w-[200px] mx-auto h-auto">

        <h1 class="text-[40px]">Sorry, no data have been found</h1>
        <a href="dashboard.php?page=member" class="no-underline text-[14px] text-blue-700 px-4 py-2">See others</a>

    </center>
<?php } ?>

<script>
    const filterForm = document.getElementById('filterGender');

    document.getElementById('jenis_kelamin').addEventListener('change', function () {
        filterForm.submit();
    });

</script>
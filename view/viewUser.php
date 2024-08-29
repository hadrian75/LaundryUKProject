<?php if ($_SESSION['role'] == "admin") { ?>

    <?php
    $id = 1;
    // Redirect to login page if user is not logged in
    if (empty($_SESSION['username'])) {
        header("Location: login.php");
        exit(); // Stop further execution
    }

    require_once "koneksi.php";

    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $filterService = isset($_GET['role']) ? $_GET['role'] : '';

    $dataQuery = "SELECT * FROM tb_user WHERE 1=1"; // Start with 1=1 to simplify conditionals
    $filterConditions = array();

    if (!empty($search)) {
        $searchTerm = mysqli_real_escape_string($koneksi, "%$search%");
        $filterConditions[] = "nama LIKE '$searchTerm'";
    }


    if (!empty($filterService)) {
        $filterConditions[] = "role = '$filterService'";
    }

    if (!empty($filterConditions)) {
        $dataQuery .= " AND " . implode(" AND ", $filterConditions);
    }

    $records_per_page = 10;
    $current_page = isset($_GET['viewPage']) ? intval($_GET['viewPage']) : 1;
    $offset = ($current_page - 1) * $records_per_page;

    $total_records_query = "SELECT COUNT(*) AS total FROM tb_user";
    if (!empty($filterConditions)) {
        $total_records_query .= " WHERE " . implode(" AND ", $filterConditions);
    }
    $total_records_result = $koneksi->query($total_records_query);
    $total_records = $total_records_result->fetch_assoc()['total'];
    $total_pages = ceil($total_records / $records_per_page);

    $dataQuery .= " LIMIT $offset, $records_per_page";
    $result = $koneksi->query($dataQuery);
    $datas = mysqli_query($koneksi, $dataQuery);

    if (mysqli_num_rows($datas) != 0) {
        ?>

        <section class="bg-gray-50  p-3 sm:p-5 h-full min-h-screen ">
            <div class="mx-auto max-w-screen-2xl px-4 lg:px-12">
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="w-full md:w-1/2">
                            <form class="flex items-center" method="GET">
                                <input type="hidden" name="page"
                                    value="<?php echo isset($_GET['page']) ? $_GET['page'] : ''; ?>">
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
                                        placeholder="Search Nama User" value="<?= $search ?>">
                                </div>
                            </form>
                        </div>
                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <a href="dashboard.php?page=register">
                                <button type="button"
                                    class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                    </svg>
                                    Tambah User
                                </button>
                            </a>
                            <form class="flex items-center space-x-3 w-full md:w-auto relative" id="filteringMenu" method="GET">
                                <input type="hidden" name="page"
                                    value="<?php echo isset($_GET['page']) ? $_GET['page'] : ''; ?>">
                                <select name="role" id="role"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" <?php echo ($filterService == '') ? 'selected' : ''; ?>>All Role</option>
                                    <option value="owner" <?php echo ($filterService == 'owner') ? 'selected' : ''; ?>>Owner
                                    </option>
                                    <option value="kasir" <?php echo ($filterService == 'kasir') ? 'selected' : ''; ?>>Kasir
                                    </option>
                                    <option value="admin" <?php echo ($filterService == 'admin') ? 'selected' : ''; ?>>Admin
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
                                    <th scope="col" class="px-4 py-3 ">Nama</th>
                                    <th scope="col" class="px-4 py-3 ">Username</th>
                                    <th scope="col" class="px-4 py-3 ">Role </th>
                                    <th scope="col" class="px-4 py-3 text-center <?php if ($_SESSION['role'] != "admin") {
                                        echo "hidden";
                                    } ?>">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                while ($data = mysqli_fetch_assoc($datas)) {

                                    $idUser = $data['id'];
                                    $checkQuery = "SELECT * 
                    FROM tb_user
                    INNER JOIN tb_transaksi ON tb_user.id = tb_transaksi.id_user
                    WHERE tb_user.id = '$idUser'";
                                    $hideDelete = mysqli_query($koneksi, $checkQuery);
                                    $hasConnections = mysqli_num_rows($hideDelete) > 0;
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
                                            <?= $data['username'] ?>
                                        </td>
                                        <td class="px-4 py-3 ">
                                            <?= strtoupper($data['role']) ?>
                                        </td>


                                        <td class="px-3 py-3 flex  gap-2 items-center justify-center <?php if ($_SESSION['role'] != "admin") {
                                            echo "hidden";
                                        } ?>">
                                            <button type="button"
                                                class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                <a href="dashboard.php?page=editUser&id=<?= $data['id'] ?>">Edit</a>
                                            </button>
                                            <button type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800  
                            <?php if ($hasConnections) {
                                echo "disabled aria-disabled='true' onclick='return false;'";
                            } ?>
                            ">
                                                <a href="<?php if (!$hasConnections) {
                                                    echo 'dashboard.php?page=deleteUser&id=' . $data['id'];
                                                } ?>" class="<?php if ($_SESSION['id_user'] == $data['id']) {
                                                     echo "disabled aria-disabled='true' cursor-not-allowed w-full h-auto";
                                                 }
                                                 if ($hasConnections) {
                                                     echo "disabled aria-disabled='true'  cursor-not-allowed w-full h-auto  ";
                                                 } ?>">Delete</a>
                                            </button>
                                        </td>
                                    </tr>

                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <?php
    } else {
        ?>
        <center class="w-[200px] mx-auto h-auto">

            <h1 class="text-[40px]">Sorry, no data have been found</h1>
            <a href="dashboard.php?page=user" class="no-underline text-[14px] text-blue-700 px-4 py-2">See others</a>

        </center>

    <?php } ?>



<?php } else { ?>
    <center>
        <h1 class="text-[40px] text-red-700">You aren't an Admin</h1>
    </center>
<?php } ?>

<script>
    const filterForm = document.getElementById('filteringMenu');
    document.getElementById('role').addEventListener('change', function () {
        filterForm.submit();
    });
</script>
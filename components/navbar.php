<?php

$user = @$_SESSION['username'];
$datas = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE username = '$user'");
$data = mysqli_fetch_assoc($datas);
if (isset($_SESSION['username'])) {
  ?>
  <nav class="bg-white border-gray-200 no-print">
    <div class="max-w-[1320px] flex flex-wrap items-center justify-between mx-auto p-4">
      <a href="dashboard.php?page=home" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="img/logo.png" class=" object-cover w-[128px]" alt="">
      </a>

      <ul
        class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white d">
        <?php
        $role = @$_SESSION['role'];
        if ($role == 'admin') {
          ?>
          <li class="relative">
            <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar"
              class="flex items-center group-hover: justify-between w-full py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-blue-700 md:dark:hover:text-blue-500  dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">Data
              Master
            </button>
            <!-- Dropdown menu -->
            <div id="dropdownNavbar"
              class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600 absolute">
              <ul class="py-2 text-sm text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">
                <li>
                  <a href="dashboard.php?page=outlet"
                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 ">Outlet</a>
                </li>
                <li>
                  <a href="dashboard.php?page=paket"
                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 ">Paket</a>
                </li>
                <li>
                  <a href="dashboard.php?page=user"
                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 ">User</a>
                </li>
                <li>
                  <a href="dashboard.php?page=member"
                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 ">Member</a>
                </li>
              </ul>

            </div>
          </li>
          <li>
            <a href="dashboard.php?page=tambahMember"
              class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-black md:dark:hover:text-blue-500 dark:hover:bg-gray-700  md:dark:hover:bg-transparent">Registrasi
              Pelanggan</a>
          </li>
          <li>
            <a href="dashboard.php?page=tambahTransaksi"
              class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-black md:dark:hover:text-blue-500 dark:hover:bg-gray-700  md:dark:hover:bg-transparent">Entri
              Transaksi</a>
          </li>
          <li>
            <a href="dashboard.php?page=laporan"
              class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-black md:dark:hover:text-blue-500 dark:hover:bg-gray-700  md:dark:hover:bg-transparent">Laporan</a>
          </li>
        <?php } else if ($role == 'kasir') {
          $role = @$_SESSION['role'];
          ?>

            <li class="relative">
              <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar"
                class="flex items-center group-hover: justify-between w-full py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-black md:dark:hover:text-blue-500  dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">Data
                Master
                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                  viewBox="0 0 10 6">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 4 4 4-4" />
                </svg></button>
              <!-- Dropdown menu -->
              <div id="dropdownNavbar"
                class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600 absolute">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">

                  <li>
                    <a href="dashboard.php?page=member"
                      class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 ">Member</a>
                  </li>
                </ul>

              </div>
            </li>
            <li>
              <a href="dashboard.php?page=tambahMember"
                class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-black md:dark:hover:text-blue-500 dark:hover:bg-gray-700  md:dark:hover:bg-transparent">Registrasi
                Pelanggan</a>
            </li>
            <li>
              <a href="dashboard.php?page=tambahTransaksi"
                class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-black md:dark:hover:text-blue-500 dark:hover:bg-gray-700  md:dark:hover:bg-transparent">Entri
                Transaksi</a>
            </li>
            <li>
              <a href="dashboard.php?page=laporan"
                class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-black md:dark:hover:text-blue-500 dark:hover:bg-gray-700  md:dark:hover:bg-transparent">Laporan</a>
            </li>
        <?php } else {
          ?>
            <li>
              <a href="dashboard.php?page=laporan"
                class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-black md:dark:hover:text-blue-500 dark:hover:bg-gray-700  md:dark:hover:bg-transparent">Laporan</a>
            </li>
        <?php } ?>
        <li class="relative max-w-[30px] pl-6 pr-4">
          <button data-dropdown-toggle="profileDropdown"
            class="text-white hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-blue-700 md:dark:hover:text-blue-500 dark:hover:bg-gray-700  md:dark:hover:bg-transparent"
            id="btnProfile">
            <?= @$_SESSION['username'] ?>
          </button>
          <div id="profileDropdown"
            class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-32 dark:bg-gray-700 dark:divide-gray-600 absolute">
            <ul class="py-2 text-sm text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">
              <li>
                <p href="dashboard.php?page=outlet" class="block px-4 py-2 leading-[2px]  text-white">
                  <?= $data['nama'] ?>
                </p>
                <p href="dashboard.php?page=User" class=" leading-[0px] block px-4 py-2  text-[12px] text-blue-300">
                  <?= strtoupper($data['role']) ?>
                </p>
              </li>
              <li>
                <a href="logout.php" class="block text-red-500 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 ">Log
                  out</a>
              </li>
            </ul>

          </div>
        </li>
      </ul>
    </div>
    </div>
  </nav>
<?php } else { ?>
  <nav class="bg-white border-gray-200 no-print">
    <div class="w-full flex flex-wrap items-center justify-between p-4">
      <a href="" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="img/logo.png" class=" object-cover w-[128px]" alt="">
      </a>
      <button class="rounded-md bg-blue-600  hover:bg-blue-700">
        <a href="login.php" class="block text-white px-4 py-2">Sign In</a>
      </button>

    </div>
    </li>
    </ul>
    </div>
    </div>
  </nav>
<?php } ?>
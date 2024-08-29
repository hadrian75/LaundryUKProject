<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/259ff13eb5.js" crossorigin="anonymous"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Galada&family=Montserrat:wght@400;800&family=Poppins:wght@400;600;700&family=Roboto:wght@300;400;700&display=swap');

        * {
            font-family: Poppins;
        }
    </style>

</head>

<body class="">

    <div class="container min-h-[100%]  flex justify-center items-center overflow-hidden m-0">
        <div class="grid grid-cols-2 gap-2 h-[100%] ">
            <div class="col-span-1 flex flex-col mt-10">
                <img src="img/logo.png" class="object-contain w-[144px]" alt="">
                <form action="prosesLoginDekripsi.php" class=" w-[524px] pl-6 pt-12" method="POST">
                    <div class="pb-6">
                        <h1 class="font-medium text-3xl pb-8">Sign In</h1>
                        <p class="font-bold text-[18px]">
                            Ga pingin takut bau saat jalan sama pacar?
                        </p>
                        <p class="font-bold text-[24px] text-blue-700">
                            Biarkan Pakaianmu D'WASH disini
                        </p>
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput"
                            class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username"
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            id="formGroupExampleInput" placeholder="Isi username disini...">
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2"
                            class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <input type="password" id="pass" name="password"
                                class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300"
                                id="formGroupExampleInput2" autocomplete="off" aria-autocomplete="false"
                                placeholder="Isi password disini...">
                            <i class="fa-regular fa-eye-slash iconEye cursor-pointer absolute top-[50%] right-2 text-gray-600 transform -translate-y-1/2"
                                onclick="passShow()"></i>
                        </div>
                    </div>
                    <div class="flex  justify-between pb-6">
                        <div class="flex items-center mb-4">
                            <input id="default-checkbox" type="checkbox" disabled aria-disabled="true" value=""
                                class="w-4 h-4  cursor-not-allowed text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 ">Ingat
                                saya</label>
                        </div>
                        <p href="dashboard.php?" class="ms-2 text-sm font-medium text-gray-900 ">Belum punya akun? <a
                                href="login.php"
                                class="text-blue-700 hover:text-blue-300 font-semibold disabled:cursor-not-allowed cursor-not-allowed"
                                href="#" disabled aria-disabled="true">Register disini</a></p>
                    </div>
                    <input type="submit"
                        class="bg-blue-500 w-full h-[32px] text-white  rounded-[50px] hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                </form>
            </div>
            <div class="col-span-1">
                <img src="img/rightSectionLogin.png" class="object-contain w-full h-[100vh]" alt="">
            </div>
        </div>
    </div>

    <script>
        function passShow() {
            var pass = document.getElementById("pass");
            var icon = document.querySelector(".iconEye");
            if (pass.type === "password") {
                pass.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                pass.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }
    </script>
</body>

</html>
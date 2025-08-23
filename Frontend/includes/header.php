<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The White Palace</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome CDN for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/style.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-weight: 400;
        }
    </style>
    <script>
        // Toggle menu based on screen size
        function toggleMenu() {
            const desktopMenu = document.getElementById('desktop-menu');
            const mobileMenu = document.getElementById('mobile-menu');
            if (window.innerWidth >= 768) {
                desktopMenu.style.display = 'flex';
                mobileMenu.style.display = 'none';
            } else {
                desktopMenu.style.display = 'none';
                mobileMenu.style.display = 'block';
            }
        }

        // Run on load and resize
        window.addEventListener('load', toggleMenu);
        window.addEventListener('resize', toggleMenu);
    </script>
</head>

<body class="bg-white">
    <div class="content bg-white">
        <header>
            <!-- Top bar -->
            <div
                class="max-h-max py-1 bg-success text-white md:flex items-center text-center md:justify-end gap-2 px-3 font-normal">
                <p><i class="fa-solid fa-phone"></i> +88 01745-654534</p>
                <p><i class="fa-solid fa-envelope"></i> +88 01745-654534</p>
                <p><i class="fa-solid fa-location-dot"></i> Paola Castillo Avenida Juan, 82</p>
            </div>

            <!-- Navbar -->
            <div class="navbar bg-stone-200 shadow-xl mb-1">
                <!-- Social icons -->
                <div class="navbar-start">
                    <a href="#" class="text-xl hidden md:flex md:text-2xl text-gray-900"><i
                            class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="text-xl hidden md:flex md:text-2xl text-gray-900"><i
                            class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="text-xl hidden md:flex md:text-2xl text-gray-900"><i
                            class="fa-brands fa-youtube"></i></a>
                </div>
                <!-- Logo -->
                <div class="navbar-center">
                    <a class="text-3xl text-black font-semibold">The White Palace</a>
                </div>

                <!-- Mobile Menu (Small Devices) -->
                <div class="navbar-end block md:hidden" id="mobile-menu">
                    <div class="dropdown dropdown-end pl-8">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </div>
                        <ul tabindex="0"
                            class="menu menu-sm dropdown-content bg-gray-900 rounded-box z-50 mt-3 w-52 p-2 shadow overflow-auto right-0">
                            <li><a class="text-green-400 hover:bg-green-600 hover:text-white transition-colors duration-300"
                                    href="#">Homepage</a></li>
                            <li><a class="text-green-400 hover:bg-green-600 hover:text-white transition-colors duration-300"
                                    href="#">Portfolio</a></li>
                            <li><a class="text-green-400 hover:bg-green-600 hover:text-white transition-colors duration-300"
                                    href="#">About</a></li>
                            <li><a class="text-green-400 hover:bg-green-600 hover:text-white transition-colors duration-300"
                                    href="#">Services</a></li>
                            <li><a class="text-green-400 hover:bg-green-600 hover:text-white transition-colors duration-300"
                                    href="#">Contact</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Desktop Menu (Medium+ Devices) -->
                <div class="navbar-end hidden md:block" id="desktop-menu">
                    <ul class="menu menu-horizontal  rounded-lg px-1">
                        <li><a class="text-gray-900 hover:bg-green-600 hover:text-white transition-colors duration-300 mx-1"
                                href="#">Homepage</a></li>
                        <li><a class="text-gray-900 hover:bg-green-600 hover:text-white transition-colors duration-300 mx-1"
                                href="#">Portfolio</a></li>
                        <li><a class="text-gray-900 hover:bg-green-600 hover:text-white transition-colors duration-300 mx-1"
                                href="#">About</a></li>
                        <li><a class="text-gray-900 hover:bg-green-600 hover:text-white transition-colors duration-300 mx-1"
                                href="#">Services</a></li>
                        <li><a class="text-gray-900 hover:bg-green-600 hover:text-white transition-colors duration-300 mx-1"
                                href="#">Contact</a></li>
                    </ul>
                </div>
            </div>
        </header>
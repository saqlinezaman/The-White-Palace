<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Set Base URL
$BASE_URL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http')
    . "://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/thewhitepalace/Frontend";

// Check login
$isLoggedIn = !empty($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The White Palace</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body class="bg-white">
<header class="shadow-md">
    <!-- 🔝 Top bar -->
    <div class="bg-green-500 text-white py-1 px-3 text-sm flex flex-col md:flex-row md:justify-end md:items-center gap-2">
        <p><i class="fa-solid fa-phone"></i> +88 01745-654534</p>
        <p><i class="fa-solid fa-envelope"></i> theWhitepalace@gmail.com</p>
        <p><i class="fa-solid fa-location-dot"></i> Paola Castillo Avenida Juan, 82</p>
    </div>

    <!-- 🔗 Navbar -->
    <nav class="bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="<?= $BASE_URL ?>/index.php" class="text-2xl font-semibold text-gray-900">The White Palace</a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-2">
                    <a href="<?= $BASE_URL ?>/index.php" class="px-3 py-2 text-gray-900 hover:bg-green-600 hover:text-white rounded transition">Home</a>
                    <a href="<?= $BASE_URL ?>/pages/rooms.php" class="px-3 py-2 text-gray-900 hover:bg-green-600 hover:text-white rounded transition">Our Rooms</a>
                    <a href="#" class="px-3 py-2 text-gray-900 hover:bg-green-600 hover:text-white rounded transition">About</a>
                    <a href="#" class="px-3 py-2 text-gray-900 hover:bg-green-600 hover:text-white rounded transition">Services</a>
                    <a href="#" class="px-3 py-2 text-gray-900 hover:bg-green-600 hover:text-white rounded transition">Contact</a>
                </div>

                <!-- Auth Buttons / User Dropdown -->
                <div class="relative">
                    <?php if ($isLoggedIn): ?>
                        <!-- User Dropdown Trigger -->
                        <button id="user-btn" class="flex items-center px-3 py-2 text-gray-900 font-semibold focus:outline-none">
                            <span><?= htmlspecialchars($userName) ?></span>
                            <i class="fa-solid fa-caret-down ml-2"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="user-menu" class="absolute right-0 mt-2 w-32 bg-white border rounded shadow-lg hidden z-50">
                            <a href="<?= $BASE_URL ?>/../auth/logout.php"
                               class="block px-4 py-2 text-gray-900 hover:bg-red-500 hover:text-white transition rounded">
                                Logout
                            </a>
                        </div>

                        <script>
                            const userBtn = document.getElementById('user-btn');
                            const userMenu = document.getElementById('user-menu');

                            userBtn.addEventListener('click', () => {
                                userMenu.classList.toggle('hidden');
                            });

                            window.addEventListener('click', (e) => {
                                if (!userBtn.contains(e.target) && !userMenu.contains(e.target)) {
                                    userMenu.classList.add('hidden');
                                }
                            });
                        </script>
                    <?php else: ?>
                        <a href="<?= $BASE_URL ?>/../auth/login.php" class="px-3 py-2 bg-green-600 text-white rounded transition">Login</a>
                        <a href="<?= $BASE_URL ?>/../auth/register.php" class="px-3 py-2 bg-gray-900 text-white rounded transition">Register</a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="menu-btn" class="text-gray-800 focus:outline-none">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Dropdown -->
        <div id="mobile-menu" class="hidden md:hidden bg-gray-900">
            <a href="<?= $BASE_URL ?>/index.php" class="block px-4 py-2 text-green-400 hover:bg-green-600 hover:text-white">Home</a>
            <a href="<?= $BASE_URL ?>/pages/rooms.php" class="block px-4 py-2 text-green-400 hover:bg-green-600 hover:text-white">Our Rooms</a>
            <a href="#" class="block px-4 py-2 text-green-400 hover:bg-green-600 hover:text-white">About</a>
            <a href="#" class="block px-4 py-2 text-green-400 hover:bg-green-600 hover:text-white">Services</a>
            <a href="#" class="block px-4 py-2 text-green-400 hover:bg-green-600 hover:text-white">Contact</a>
        </div>
    </nav>
</header>

<!-- JS for mobile menu toggle -->
<script>
    const btn = document.getElementById('menu-btn');
    const menu = document.getElementById('mobile-menu');
    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
</script>

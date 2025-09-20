<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Set Base URL
$BASE_URL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http')
    . "://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/thewhitepalace/Frontend";

// Check login
$isLoggedIn = !empty($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';

// Get current page for active menu
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The White Palace</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

   

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
       
    .slider-track {
        transition: transform 0.3s ease-in-out;
    }
    </style>
</head>

<body class="bg-white">
<header class="shadow-md">
    <!-- 🔝 Top bar -->
    <div class="bg-green-500 text-white py-2 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-2 text-sm">
                <p class="flex items-center gap-1"><i class="fa-solid fa-phone"></i> +88 01745-654534</p>
                <p class="flex items-center gap-1"><i class="fa-solid fa-envelope"></i> theWhitepalace@gmail.com</p>
                <p class="flex items-center gap-1"><i class="fa-solid fa-location-dot"></i> Paola Castillo Avenida Juan, 82</p>
            </div>
        </div>
    </div>

    <!-- 🔗 Navbar -->
    <nav class="bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="<?= $BASE_URL ?>/index.php" class="text-xl sm:text-2xl font-semibold text-gray-900">The White Palace</a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex space-x-1">
                     <a href="<?= $BASE_URL ?>/index.php" 
                       class="px-3 py-2 text-gray-900 hover:bg-green-600 hover:text-white rounded transition">
                        Home
                    </a>
                    
                    <a href="<?= $BASE_URL ?>/pages/rooms.php" 
                       class="px-3 py-2  hover:bg-green-600 hover:text-white rounded transition <?= ($currentPage == 'rooms.php') ? 'bg-green-500 text-white font-bold' : '' ?>">
                        Our Rooms
                    </a>
                    
                    <a href="../404.php" 
                       class="px-3 py-2  hover:bg-green-600 hover:text-white rounded transition <?= ($currentPage == 'packages.php') ? 'bg-green-500 text-white font-bold' : '' ?>">
                        Packages
                    </a>
                    <a href="<?= $BASE_URL ?>/pages/about.php" 
                       class="px-3 py-2  hover:bg-green-600 hover:text-white rounded transition <?= ($currentPage == 'about.php') ? 'bg-green-500 text-white font-bold' : '' ?>">
                        About
                    </a>
                    <a href="<?= $BASE_URL ?>/pages/service.php" 
                       class="px-3 py-2  hover:bg-green-600 hover:text-white rounded transition <?= ($currentPage == 'service.php') ? 'bg-green-500 text-white font-bold' : '' ?>">
                        Services
                    </a>
                    <a href="<?= $BASE_URL ?>/pages/contact_us.php" 
                       class="px-3 py-2  hover:bg-green-600 hover:text-white rounded transition <?= ($currentPage == 'contact_us.php') ? 'bg-green-500 text-white font-bold' : '' ?>">
                        Contact
                    </a>
                </div>

                <!-- Auth Buttons / User Dropdown -->
                <div class="relative flex items-center gap-2">
                    <?php if ($isLoggedIn): ?>
                        <!-- User Dropdown Trigger -->
                        <button id="user-btn" class="flex items-center px-3 py-2 text-gray-900 font-semibold focus:outline-none hover:bg-gray-200 rounded transition">
                            <span class="hidden sm:inline"><?= htmlspecialchars($userName) ?></span>
                            <span class="sm:hidden"><i class="fa-solid fa-user"></i></span>
                            <i class="fa-solid fa-caret-down ml-2"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="user-menu" class="absolute right-0 top-full mt-2 w-40 bg-white border rounded shadow-lg hidden z-50 p-1">

                        <a href="<?= $BASE_URL ?>/pages/profile.php"
                               class="block px-4 py-2 mb-1 bg-gray-800 hover:bg-gray-900 text-white transition rounded">
                                Profile
                            </a>

                            <a href="<?= $BASE_URL ?>/pages/booking_history.php"
                               class="block px-4 py-2 mb-1 bg-green-500 hover:bg-green-600 text-white transition rounded">
                                My Bookings
                            </a>
                            
                            <a href="<?= $BASE_URL ?>/../auth/logout.php"
                               class="block px-4 py-2 bg-red-500 hover:bg-red-600 text-white transition rounded">
                                Logout
                            </a>
                        </div>

                    <?php else: ?>
                        <a href="<?= $BASE_URL ?>/../auth/login.php" class="px-3 py-2 bg-green-600 text-white rounded transition hover:bg-green-700 text-sm">Login</a>
                        <a href="<?= $BASE_URL ?>/../auth/register.php" class="px-3 py-2 bg-gray-900 text-white rounded transition hover:bg-gray-800 text-sm">Register</a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden">
                    <button id="menu-btn" class="text-gray-800 focus:outline-none p-2">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Dropdown -->
        <div id="mobile-menu" class="hidden lg:hidden bg-gray-900 border-t">
            <div class="px-4 py-2 space-y-1">
                <a href="<?= $BASE_URL ?>/index.php" 
                   class="block px-4 py-3 hover:bg-green-600 hover:text-white rounded transition <?= ($currentPage == 'index.php') ? 'bg-green-500 text-white font-bold' : 'text-green-400' ?>">
                    <i class="fa-solid fa-home mr-2"></i>Home
                </a>
                <a href="<?= $BASE_URL ?>/pages/rooms.php" 
                   class="block px-4 py-3 hover:bg-green-600 hover:text-white rounded transition <?= ($currentPage == 'rooms.php') ? 'bg-green-500 text-white font-bold' : 'text-green-400' ?>">
                    <i class="fa-solid fa-bed mr-2"></i>Our Rooms
                </a>
                <a href="<?= $BASE_URL ?>/pages/about.php" 
                   class="block px-4 py-3 hover:bg-green-600 hover:text-white rounded transition <?= ($currentPage == 'about.php') ? 'bg-green-500 text-white font-bold' : 'text-green-400' ?>">
                    <i class="fa-solid fa-info-circle mr-2"></i>About
                </a>
                <a href="<?= $BASE_URL ?>/pages/service.php" 
                   class="block px-4 py-3 text-green-400 hover:bg-green-600 hover:text-white rounded transition">
                    <i class="fa-solid fa-concierge-bell mr-2"></i>Services
                </a>
                <a href="<?= $BASE_URL ?>/pages/contact_us.php" 
                   class="block px-4 py-3 hover:bg-green-600 hover:text-white rounded transition <?= ($currentPage == 'contact_us.php') ? 'bg-green-500 text-white font-bold' : 'text-green-400' ?>">
                    <i class="fa-solid fa-envelope mr-2"></i>Contact
                </a>
                
                <!-- Mobile Auth Links -->
                <?php if (!$isLoggedIn): ?>
                    <div class="border-t border-gray-600 pt-2 mt-2">
                        <a href="<?= $BASE_URL ?>/../auth/login.php" class="block px-4 py-3 bg-green-600 text-white rounded transition hover:bg-green-700 mb-2">
                            <i class="fa-solid fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="<?= $BASE_URL ?>/../auth/register.php" class="block px-4 py-3 bg-gray-800 text-white rounded transition hover:bg-gray-700">
                            <i class="fa-solid fa-user-plus mr-2"></i>Register
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>

<!-- JS for mobile menu toggle -->
<script>
    // button
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

    const btn = document.getElementById('menu-btn');
    const menu = document.getElementById('mobile-menu');
    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
</script>

                            
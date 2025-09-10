<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The White Palace</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-white">
    <header class="shadow-md">
        <!-- Top bar -->
        <div
            class="bg-green-500 text-white py-1 px-3 text-sm flex flex-col md:flex-row md:justify-end md:items-center gap-2">
            <p><i class="fa-solid fa-phone"></i> +88 01745-654534</p>
            <p><i class="fa-solid fa-envelope"></i> theWhitepalace@gmail.com</p>
            <p><i class="fa-solid fa-location-dot"></i> Paola Castillo Avenida Juan, 82</p>
        </div>

        <!-- Navbar -->
        <nav class="bg-stone-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <a class="text-2xl font-semibold text-black">The White Palace</a>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex space-x-2">
                        <a href="../index.php"
                            class="px-3 py-2 text-gray-900 hover:bg-green-600 hover:text-white rounded transition">Home</a>
                        <a href="pages/rooms.php"
                            class="px-3 py-2 text-gray-900 hover:bg-green-600 hover:text-white rounded transition">Our
                            Rooms</a>
                        <a href="#"
                            class="px-3 py-2 text-gray-900 hover:bg-green-600 hover:text-white rounded transition">About</a>
                        <a href="#"
                            class="px-3 py-2 text-gray-900 hover:bg-green-600 hover:text-white rounded transition">Services</a>
                        <a href="#"
                            class="px-3 py-2 text-gray-900 hover:bg-green-600 hover:text-white rounded transition">Contact</a>
                    </div>
                    <div class="flex space-x-4 justify-center items-center md:flex">
                        <button class="px-3 py-2  bg-green-600 text-white rounded transition">Login</button>
                        <button class="px-3 py-2  bg-gray-900 text-white rounded transition">Register</button>
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
                <a href="../index.php"
                    class="block px-4 py-2 text-green-400 hover:bg-green-600 hover:text-white">Homepage</a>
                <a href="pages/rooms.php" class="block px-4 py-2 text-green-400 hover:bg-green-600 hover:text-white">Our
                    Rooms</a>
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
</body>

</html>
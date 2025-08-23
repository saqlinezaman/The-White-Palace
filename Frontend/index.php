
<?php
include '../Frontend/includes/header.php';
?>


<!-- hero section -->
<div class="hero bg-base-200 min-h-screen relative" style="background-image: url(assets/images/hero-image-1.jpg);">
    <!-- Lighter overlay for better image visibility -->
    <div class="absolute inset-0 bg-black" style="opacity: 0.6;"></div>
    <div class="hero-content flex-col lg:flex-row gap-8 relative z-10 w-full max-w-7xl mx-auto px-4">
        <!-- Left Content -->
        <div class="text-center lg:text-left w-full lg:w-1/2 text-white">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4">
                Welcome to <br> The White Palace
            </h1>
            <p class="py-6 text-base md:text-lg text-gray-100 leading-relaxed max-w-lg">
                Experience luxury and comfort at its finest. Book your perfect stay with us and create unforgettable
                memories in our world-class hospitality.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 items-center lg:items-start">
                <button
                    class="btn btn-success btn-lg text-white font-semibold px-8 hover:scale-105 transition-all duration-300">
                    Read More
                </button>
                <button
                    class="btn btn-outline btn-lg font-semibold px-8 text-white border-white hover:bg-white hover:text-gray-800 transition-all duration-300">
                    Learn More
                </button>
            </div>
        </div>

        <!-- Transparent Form -->
        <div class="w-full max-w-md mx-auto">
            <div class="rounded-2xl shadow-2xl p-6 border border-white backdrop-blur-sm" style="background-color: rgba(255, 255, 255, 0.10); border-color: rgba(255, 255, 255, 0.3);">
                <h2 class="text-2xl font-bold text-center text-white mb-6 drop-shadow-lg">Book Your Stay</h2>

                <form class="space-y-2">
                    <!-- Name Field -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-1 drop-shadow">Full Name</label>
                        <input type="text" name="name"
                            class="w-full px-3 py-2.5 rounded-lg border-2 focus:outline-none focus:ring-2 transition-all duration-300"
                            style="background-color: rgba(255, 255, 255, 0.2); border-color: rgba(255, 255, 255, 0.4); color: white;"
                            placeholder="Enter your name" required />
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-1 drop-shadow">Email Address</label>
                        <input type="email" name="email"
                            class="w-full px-3 py-2.5 rounded-lg border-2 focus:outline-none focus:ring-2 transition-all duration-300"
                            style="background-color: rgba(255, 255, 255, 0.2); border-color: rgba(255, 255, 255, 0.4); color: white;"
                            placeholder="Enter your email address" required />
                    </div>

                    <!-- Phone Field -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-1 drop-shadow">Phone Number</label>
                        <input type="tel" name="number"
                            class="w-full px-3 py-2.5 rounded-lg border-2 focus:outline-none focus:ring-2 transition-all duration-300"
                            style="background-color: rgba(255, 255, 255, 0.2); border-color: rgba(255, 255, 255, 0.4); color: white;"
                            placeholder="Enter your phone number" required />
                    </div>

                    <!-- Room Selection -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-1 drop-shadow">Room Type</label>
                        <select
                            class="w-full px-3 py-2.5 rounded-lg border-2 focus:outline-none focus:ring-2 transition-all duration-300"
                            style="background-color: rgba(255, 255, 255, 0.2); border-color: rgba(255, 255, 255, 0.4); color: white;"
                            name="room_type" required>
                            <option disabled selected style="color: #666;">Select room type</option>
                            <option value="single" style="color: #333; background-color: white;">Single Room (1 Person)</option>
                            <option value="double" style="color: #333; background-color: white;">Double Room (2 Persons)</option>
                            <option value="family" style="color: #333; background-color: white;">Family Suite (3-4 Persons)</option>
                            <option value="deluxe" style="color: #333; background-color: white;">Deluxe Suite (2 Persons)</option>
                        </select>
                    </div>

                    <!-- Date Fields -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-white mb-1 drop-shadow">Check In</label>
                            <input type="date" name="checkin"
                                class="w-full px-3 py-2.5 rounded-lg border-2 focus:outline-none focus:ring-2 transition-all duration-300"
                                style="background-color: rgba(255, 255, 255, 0.2); border-color: rgba(255, 255, 255, 0.4); color: white; color-scheme: dark;"
                                required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-white mb-1 drop-shadow">Check Out</label>
                            <input type="date" name="checkout"
                                class="w-full px-3 py-2.5 rounded-lg border-2 focus:outline-none focus:ring-2 transition-all duration-300"
                                style="background-color: rgba(255, 255, 255, 0.2); border-color: rgba(255, 255, 255, 0.4); color: white; color-scheme: dark;"
                                required />
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg text-base transition-all duration-300 hover:-translate-y-1 hover:shadow-lg backdrop-blur-sm"
                            style="background-color: rgba(34, 197, 94, 0.8); backdrop-filter: blur(4px);">
                            Book Your Stay
                        </button>
                    </div>

                    <!-- Additional Info -->
                    <div class="text-center mt-4 p-3 rounded-lg" style="background-color: rgba(255, 255, 255, 0.1);">
                        <p class="text-sm text-white drop-shadow">
                            Need help? <a href="#" class="text-cyan-300 font-medium hover:underline hover:text-cyan-200 transition-colors duration-300">Contact us</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Room Options Section -->
<section class="my-10 mx-3 md:mx-16">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Our Room Options</h1>
        <p class="text-lg text-gray-600">Choose from our carefully curated selection of rooms</p>
        <div class="w-24 h-1 bg-gradient-to-r from-green-400 to-blue-500 mx-auto mt-4 rounded"></div>
    </div>

    <!-- Room Carousel -->
    <div class="carousel w-full rounded-xl overflow-hidden shadow-lg">
        <!-- Slide 1 - Single Room -->
        <div id="slide1" class="carousel-item relative w-full">
            <div class="w-full bg-white">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 h-full">
                    <!-- Image Section -->
                    <div class="relative overflow-hidden">
                        <img src="https://img.daisyui.com/images/stock/photo-1625726411847-8cbb60cc71e6.webp"
                            alt="Single Room"
                            class="w-full h-64 lg:h-full object-cover transition-transform duration-500 hover:scale-110" />
                        <!-- Price Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="bg-black text-white px-4 py-2 rounded-full font-bold text-lg shadow-lg">
                                ৳2,500/night
                            </span>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="p-6 lg:p-8 flex flex-col justify-between">
                        <div>
                            <!-- Room Title & Capacity -->
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 gap-2">
                                <h3 class="text-2xl lg:text-3xl font-bold text-gray-800">
                                    Single Room
                                </h3>
                                <span
                                    class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold w-fit">
                                    1 Person
                                </span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 mb-6 text-base lg:text-lg leading-relaxed">
                                Perfect for solo travelers. Comfortable bed with modern amenities and a peaceful
                                atmosphere for relaxation.
                            </p>

                            <!-- Amenities -->
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-800 mb-3">Amenities:</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span class="bg-blue-700 text-white px-3 py-1 rounded-full text-sm font-medium">Free
                                        WiFi</span>
                                    <span
                                        class="bg-blue-700 text-white px-3 py-1 rounded-full text-sm font-medium">AC</span>
                                    <span
                                        class="bg-blue-700 text-white px-3 py-1 rounded-full text-sm font-medium">TV</span>
                                    <span class="bg-blue-700 text-white px-3 py-1 rounded-full text-sm font-medium">Room
                                        Service</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 mt-auto">
                            <button
                                class="btn btn-success flex-1 text-white font-semibold hover:scale-105 transition-all duration-300">
                                Book Now
                            </button>
                            <button
                                class="btn btn-outline btn-primary font-semibold hover:scale-105 transition-all duration-300">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div
                class="absolute left-2 right-2 md:left-5 md:right-5 top-1/2 flex -translate-y-1/2 transform justify-between z-10">
                <a href="#slide4"
                    class="btn btn-circle btn-sm md:btn-md bg-black bg-opacity-50 text-white border-none hover:bg-opacity-75 transition-all duration-300">❮</a>
                <a href="#slide2"
                    class="btn btn-circle btn-sm md:btn-md bg-black bg-opacity-50 text-white border-none hover:bg-opacity-75 transition-all duration-300">❯</a>
            </div>
        </div>

        <!-- Slide 2 - Double Room -->
        <div id="slide2" class="carousel-item relative w-full">
            <div class="w-full bg-white">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 h-full">
                    <!-- Image Section -->
                    <div class="relative overflow-hidden">
                        <img src="https://img.daisyui.com/images/stock/photo-1609621838510-5ad474b7d25d.webp"
                            alt="Double Room"
                            class="w-full h-64 lg:h-full object-cover transition-transform duration-500 hover:scale-110" />
                        <!-- Price Badge -->
                        <div class="absolute top-4 right-4">
                            <span
                                class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white px-4 py-2 rounded-full font-bold text-lg shadow-lg">
                                ৳4,000/night
                            </span>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="p-6 lg:p-8 flex flex-col justify-between">
                        <div>
                            <!-- Room Title & Capacity -->
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 gap-2">
                                <h3 class="text-2xl lg:text-3xl font-bold text-gray-800">
                                    Double Room
                                </h3>
                                <span
                                    class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold w-fit">
                                    2 Persons
                                </span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 mb-6 text-base lg:text-lg leading-relaxed">
                                Spacious room for couples with king-size bed and beautiful city view. Perfect for
                                romantic getaways.
                            </p>

                            <!-- Amenities -->
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-800 mb-3">Amenities:</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Free
                                        WiFi</span>
                                    <span
                                        class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">AC</span>
                                    <span
                                        class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">TV</span>
                                    <span
                                        class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Mini
                                        Bar</span>
                                    <span
                                        class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Balcony</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 mt-auto">
                            <button
                                class="btn btn-success flex-1 text-white font-semibold hover:scale-105 transition-all duration-300">
                                Book Now
                            </button>
                            <button
                                class="btn btn-outline btn-primary font-semibold hover:scale-105 transition-all duration-300">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div
                class="absolute left-2 right-2 md:left-5 md:right-5 top-1/2 flex -translate-y-1/2 transform justify-between z-10">
                <a href="#slide1"
                    class="btn btn-circle btn-sm md:btn-md bg-black bg-opacity-50 text-white border-none hover:bg-opacity-75 transition-all duration-300">❮</a>
                <a href="#slide3"
                    class="btn btn-circle btn-sm md:btn-md bg-black bg-opacity-50 text-white border-none hover:bg-opacity-75 transition-all duration-300">❯</a>
            </div>
        </div>

        <!-- Slide 3 - Family Suite -->
        <div id="slide3" class="carousel-item relative w-full">
            <div class="w-full bg-white">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 h-full">
                    <!-- Image Section -->
                    <div class="relative overflow-hidden">
                        <img src="https://img.daisyui.com/images/stock/photo-1414694762283-acccc27bca85.webp"
                            alt="Family Suite"
                            class="w-full h-64 lg:h-full object-cover transition-transform duration-500 hover:scale-110" />
                        <!-- Price Badge -->
                        <div class="absolute top-4 right-4">
                            <span
                                class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white px-4 py-2 rounded-full font-bold text-lg shadow-lg">
                                ৳6,500/night
                            </span>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="p-6 lg:p-8 flex flex-col justify-between">
                        <div>
                            <!-- Room Title & Capacity -->
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 gap-2">
                                <h3 class="text-2xl lg:text-3xl font-bold text-gray-800">
                                    Family Suite
                                </h3>
                                <span
                                    class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold w-fit">
                                    3-4 Persons
                                </span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 mb-6 text-base lg:text-lg leading-relaxed">
                                Large suite perfect for families with separate living area. Spacious and comfortable for
                                memorable family stays.
                            </p>

                            <!-- Amenities -->
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-800 mb-3">Amenities:</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Free
                                        WiFi</span>
                                    <span
                                        class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">AC</span>
                                    <span
                                        class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">TV</span>
                                    <span
                                        class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Kitchen</span>
                                    <span
                                        class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Living
                                        Area</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 mt-auto">
                            <button
                                class="btn btn-success flex-1 text-white font-semibold hover:scale-105 transition-all duration-300">
                                Book Now
                            </button>
                            <button
                                class="btn btn-outline btn-primary font-semibold hover:scale-105 transition-all duration-300">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div
                class="absolute left-2 right-2 md:left-5 md:right-5 top-1/2 flex -translate-y-1/2 transform justify-between z-10">
                <a href="#slide2"
                    class="btn btn-circle btn-sm md:btn-md bg-black bg-opacity-50 text-white border-none hover:bg-opacity-75 transition-all duration-300">❮</a>
                <a href="#slide4"
                    class="btn btn-circle btn-sm md:btn-md bg-black bg-opacity-50 text-white border-none hover:bg-opacity-75 transition-all duration-300">❯</a>
            </div>
        </div>

        <!-- Slide 4 - Deluxe Suite -->
        <div id="slide4" class="carousel-item relative w-full">
            <div class="w-full bg-white">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 h-full">
                    <!-- Image Section -->
                    <div class="relative overflow-hidden">
                        <img src="https://img.daisyui.com/images/stock/photo-1665553365602-b2fb8e5d1707.webp"
                            alt="Deluxe Suite"
                            class="w-full h-64 lg:h-full object-cover transition-transform duration-500 hover:scale-110" />
                        <!-- Price Badge -->
                        <div class="absolute top-4 right-4">
                            <span
                                class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white px-4 py-2 rounded-full font-bold text-lg shadow-lg">
                                ৳8,000/night
                            </span>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="p-6 lg:p-8 flex flex-col justify-between">
                        <div>
                            <!-- Room Title & Capacity -->
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 gap-2">
                                <h3 class="text-2xl lg:text-3xl font-bold text-gray-800">
                                    Deluxe Suite
                                </h3>
                                <span
                                    class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold w-fit">
                                    2 Persons
                                </span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 mb-6 text-base lg:text-lg leading-relaxed">
                                Luxury suite with premium amenities and panoramic view. Experience the ultimate comfort
                                and elegance.
                            </p>

                            <!-- Amenities -->
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-800 mb-3">Amenities:</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Free
                                        WiFi</span>
                                    <span
                                        class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">AC</span>
                                    <span
                                        class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">TV</span>
                                    <span
                                        class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Jacuzzi</span>
                                    <span
                                        class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Premium
                                        Service</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 mt-auto">
                            <button
                                class="btn btn-success flex-1 text-white font-semibold hover:scale-105 transition-all duration-300">
                                Book Now
                            </button>
                            <button
                                class="btn btn-outline btn-primary font-semibold hover:scale-105 transition-all duration-300">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div
                class="absolute left-2 right-2 md:left-5 md:right-5 top-1/2 flex -translate-y-1/2 transform justify-between z-10">
                <a href="#slide3"
                    class="btn btn-circle btn-sm md:btn-md bg-black bg-opacity-50 text-white border-none hover:bg-opacity-75 transition-all duration-300">❮</a>
                <a href="#slide1"
                    class="btn btn-circle btn-sm md:btn-md bg-black bg-opacity-50 text-white border-none hover:bg-opacity-75 transition-all duration-300">❯</a>
            </div>
        </div>
    </div>

    <!-- Carousel Indicators -->
    <div class="flex justify-center mt-6 space-x-2">
        <a href="#slide1"
            class="w-3 h-3 bg-gray-300 rounded-full hover:bg-gray-500 transition-all duration-300 cursor-pointer"></a>
        <a href="#slide2"
            class="w-3 h-3 bg-gray-300 rounded-full hover:bg-gray-500 transition-all duration-300 cursor-pointer"></a>
        <a href="#slide3"
            class="w-3 h-3 bg-gray-300 rounded-full hover:bg-gray-500 transition-all duration-300 cursor-pointer"></a>
        <a href="#slide4"
            class="w-3 h-3 bg-gray-300 rounded-full hover:bg-gray-500 transition-all duration-300 cursor-pointer"></a>
    </div>
</section>

<!-- why use our room blog  -->

<!-- Why use  blog -->
<section class="my-10 mx-3 md:mx-16">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Why You Should Use <br> our rooms</h1>
        <p class="text-lg text-gray-600">Choose from our carefully curated selection of rooms</p>
        <div class="w-24 h-1 bg-gradient-to-r from-green-400 to-blue-500 mx-auto mt-4 rounded"></div>
    </div>
    <!-- Blog Cards Row -->
    <div class="flex flex-wrap justify-center gap-6">
        <!-- card 1 -->
        <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer group hover:bg-success border border-base-300"
            style=" width: 350px; ">
            <figure class="overflow-hidden">
                <img src="assets/images/blog1.jpg" alt="Best Hotels in Paris"
                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105" />
            </figure>

            <div class="card-body p-6 pb-0">
                <span>2025/8/23</span>
                <h3
                    class=" text-2xl font-bold mb-4 text-base-content group-hover:text-white transition-colors duration-300">
                    Best Hotels in Paris
                </h3>

                <p
                    class="text-base-content/70 text-base leading-relaxed group-hover:text-white/90 transition-colors duration-300">
                    Discover luxury accommodations in the City of Light with our curated selection of top-rated hotels.
                </p>
            </div>

             <div class="bg-gray-900 py-6 px-6">
                <button class="bg-white font-medium text-gray-900 py-2 px-5 rounded">
                    Read More
                </button>
            </div>
        </div>
        <!-- card 2 -->
        <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer group hover:bg-success border border-base-300"
            style=" width: 350px; ">
            <figure class="overflow-hidden">
                <img src="assets/images/blog2.jpg"
                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105" />
            </figure>

            <div class="card-body p-6 pb-0">
                <span>2025/8/23</span>
                <h3
                    class="text-2xl font-bold mb-4 text-base-content group-hover:text-white transition-colors duration-300">
                    Holiday discount
                </h3>

                <p
                    class="text-base-content/70 text-base leading-relaxed group-hover:text-white/90 transition-colors duration-300">
                    Discover luxury accommodations in the City of Light with our curated selection of top-rated hotels.
                </p>
            </div>

            <div class="bg-gray-900 py-6 px-6">
                <button class="bg-white font-medium text-gray-900 py-2 px-5 rounded">
                    Read More
                </button>
            </div>
        </div>
        <!-- card-3 -->
        <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer group hover:bg-success border border-base-300"
            style=" width: 350px; ">
            <figure class="overflow-hidden">
                <img src="assets/images/blog3.jpg"
                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105" />
            </figure>

            <div class="card-body p-6 pb-0">
                <span>2025/8/23</span>
                <h3
                    class=" text-2xl font-bold text-base-content group-hover:text-white transition-colors duration-300">
                    Best view in Hawai
                </h3>

                <p
                    class="text-base-content/70 text-base leading-relaxed group-hover:text-white/90 transition-colors duration-300">
                    Discover luxury accommodations in the City of Light with our curated selection of top-rated hotels.
                </p>
            </div>

            <div class="bg-gray-900 py-6 px-6">
                <button class="bg-white font-medium text-gray-900 py-2 px-5 rounded">
                    Read More
                </button>
            </div>
        </div>

    </div>
</section>

<!-- testimonials -->
<section class="py-8 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">What Our Clients Say</h1>
            <p class="text-lg text-gray-600">Real feedback from our valued customers</p>
            <div class="w-24 h-1 bg-gradient-to-r from-green-400 to-blue-500 mx-auto mt-4 rounded"></div>
        </div>

        <div class="carousel w-full relative">
            <!-- Feedback 3 -->
            <div id="feedback3" class="carousel-item w-full flex justify-center">
                <div
                    class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 w-full max-w-2xl">
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <figure class="flex-shrink-0">
                            <img class="w-24 h-24 rounded-full object-cover border-4 border-purple-100"
                                src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150&h=150&fit=crop&crop=face"
                                alt="Sarah Johnson">
                        </figure>
                        <div class="text-center md:text-left flex-1">
                            <div class="flex justify-center md:justify-start mb-2 text-yellow-400 text-xl">★★★★★</div>
                            <h2 class="text-xl font-semibold text-gray-800 mb-2">Sarah Johnson</h2>
                            <p class="text-gray-600 mb-3 leading-relaxed">"Incredible results! They understood our
                                vision perfectly and brought it to life. The whole process was smooth and professional."
                            </p>
                            <p class="text-sm text-purple-600 font-medium">Delighted Client</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feedback 4 -->
            <div id="feedback4" class="carousel-item w-full flex justify-center">
                <div
                    class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 w-full max-w-2xl">
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <figure class="flex-shrink-0">
                            <img class="w-24 h-24 rounded-full object-cover border-4 border-red-100"
                                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face"
                                alt="David Wilson">
                        </figure>
                        <div class="text-center md:text-left flex-1">
                            <div class="flex justify-center md:justify-start mb-2 text-yellow-400 text-xl">★★★★★</div>
                            <h2 class="text-xl font-semibold text-gray-800 mb-2">David Wilson</h2>
                            <p class="text-gray-600 mb-3 leading-relaxed">"Top-notch quality and service! They exceeded
                                all our expectations and delivered on time. Couldn't be happier with the results."</p>
                            <p class="text-sm text-red-600 font-medium">Loyal Customer</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feedback 5 -->
            <div id="feedback5" class="carousel-item w-full flex justify-center">
                <div
                    class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 w-full max-w-2xl">
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <figure class="flex-shrink-0">
                            <img class="w-24 h-24 rounded-full object-cover border-4 border-indigo-100"
                                src="https://images.unsplash.com/photo-1489424731084-a5d8b219a5bb?w=150&h=150&fit=crop&crop=face"
                                alt="Emma Davis">
                        </figure>
                        <div class="text-center md:text-left flex-1">
                            <div class="flex justify-center md:justify-start mb-2 text-yellow-400 text-xl">★★★★★</div>
                            <h2 class="text-xl font-semibold text-gray-800 mb-2">Emma Davis</h2>
                            <p class="text-gray-600 mb-3 leading-relaxed">"Amazing experience! Their creativity and
                                professionalism made our project a huge success. I highly recommend their services to
                                everyone."</p>
                            <p class="text-sm text-indigo-600 font-medium">Happy Customer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Indicator dots -->
        <div class="flex justify-center mt-6 space-x-2">
            <a href="#feedback3" class="w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-400"></a>
            <a href="#feedback4" class="w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-400"></a>
            <a href="#feedback5" class="w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-400"></a>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<div class="bg-success">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="text-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Subscribe to Our Newsletter</h3>
            <p class="text-white">Get updates about our latest offers and news</p>
        </div>

        <div class="flex flex-col sm:flex-row justify-center items-center gap-4 max-w-md mx-auto">
            <input type="email" placeholder="Enter your email address"
                class="flex-1 px-4 py-3 rounded-lg bg-white border border-gray-700 text-white placeholder-gray-400 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition-all duration-300" />
            <button
                class="px-6 py-3 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-lg transition-all duration-300 hover:scale-105 whitespace-nowrap">
                Subscribe Now
            </button>
        </div>
    </div>
</div>


<?php include '../Frontend/includes/footer.php'; ?>

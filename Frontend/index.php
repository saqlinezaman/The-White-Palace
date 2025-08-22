<?php
include '../Frontend/includes/header.php';
?>


<!-- hero section -->
<div class="hero bg-base-200 min-h-screen relative" style="background-image: url(assets/images/hero-image-2.jpg);">
    <!-- Simple overlay -->
    <div class="absolute inset-0 bg-black opacity-50"></div>
    
    <div class="hero-content flex-col lg:flex-row gap-8 relative z-10 w-full max-w-7xl mx-auto px-4">
        <!-- Left Content -->
        <div class="text-center lg:text-left w-full lg:w-1/2 text-white">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4">
                Welcome to <br> The White Palace
            </h1>
            <p class="py-6 text-base md:text-lg text-gray-100 leading-relaxed max-w-lg">
                Experience luxury and comfort at its finest. Book your perfect stay with us and create unforgettable memories in our world-class hospitality.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 items-center lg:items-start">
                <button class="btn btn-success btn-lg text-white font-semibold px-8 hover:scale-105 transition-all duration-300">
                    Read More
                </button>
                <button class="btn btn-outline btn-lg font-semibold px-8 text-white border-white hover:bg-white hover:text-gray-800 transition-all duration-300">
                    Learn More
                </button>
            </div>
        </div>

        <!-- form -->
         <div class="w-full max-w-md mx-auto">
    <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-2xl shadow-2xl p-6 border border-white border-opacity-20">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Book Your Stay</h2>
        
        <form class="space-y-4">
            <!-- Name Field -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input 
                    type="text" 
                    name="name" 
                    class="w-full px-3 py-2.5 rounded-lg border-2 border-gray-200 bg-white bg-opacity-80 focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-200 transition-all duration-300" 
                    placeholder="Enter your full name" 
                    required
                />
            </div>

            <!-- Email Field -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    class="w-full px-3 py-2.5 rounded-lg border-2 border-gray-200 bg-white bg-opacity-80 focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-200 transition-all duration-300" 
                    placeholder="Enter your email address" 
                    required
                />
            </div>
            
            <!-- Phone Field -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <input 
                    type="tel" 
                    name="number" 
                    class="w-full px-3 py-2.5 rounded-lg border-2 border-gray-200 bg-white bg-opacity-80 focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-200 transition-all duration-300" 
                    placeholder="Enter your phone number" 
                    required
                />
            </div>

            <!-- Room Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                <select class="w-full px-3 py-2.5 rounded-lg border-2 border-gray-200 bg-white bg-opacity-80 focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-200 transition-all duration-300" name="room_type" required>
                    <option disabled selected>Select room type</option>
                    <option value="single">Single Room (1 Person)</option>
                    <option value="double">Double Room (2 Persons)</option>
                    <option value="family">Family Suite (3-4 Persons)</option>
                    <option value="deluxe">Deluxe Suite (2 Persons)</option>
                </select>
            </div>

            <!-- Date Fields -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check In</label>
                    <input 
                        type="date" 
                        name="checkin" 
                        class="w-full px-3 py-2.5 rounded-lg border-2 border-gray-200 bg-white bg-opacity-80 focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-200 transition-all duration-300" 
                        required
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check Out</label>
                    <input 
                        type="date" 
                        name="checkout" 
                        class="w-full px-3 py-2.5 rounded-lg border-2 border-gray-200 bg-white bg-opacity-80 focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-200 transition-all duration-300" 
                        required
                    />
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg text-base transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    Book Your Stay
                </button>
            </div>

            <!-- Additional Info -->
            <div class="text-center mt-4 p-3 bg-gray-50 bg-opacity-50 rounded-lg">
                <p class="text-sm text-gray-600">
                    Need help? <a href="#" class="text-cyan-600 font-medium hover:underline">Contact us</a>
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
                        <img 
                            src="https://img.daisyui.com/images/stock/photo-1625726411847-8cbb60cc71e6.webp" 
                            alt="Single Room"
                            class="w-full h-64 lg:h-full object-cover transition-transform duration-500 hover:scale-110"
                        />
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
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold w-fit">
                                    1 Person
                                </span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 mb-6 text-base lg:text-lg leading-relaxed">
                                Perfect for solo travelers. Comfortable bed with modern amenities and a peaceful atmosphere for relaxation.
                            </p>

                            <!-- Amenities -->
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-800 mb-3">Amenities:</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span class="bg-blue-700 text-white px-3 py-1 rounded-full text-sm font-medium">Free WiFi</span>
                                    <span class="bg-blue-700 text-white px-3 py-1 rounded-full text-sm font-medium">AC</span>
                                    <span class="bg-blue-700 text-white px-3 py-1 rounded-full text-sm font-medium">TV</span>
                                    <span class="bg-blue-700 text-white px-3 py-1 rounded-full text-sm font-medium">Room Service</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 mt-auto">
                            <button class="btn btn-success flex-1 text-white font-semibold hover:scale-105 transition-all duration-300">
                                Book Now
                            </button>
                            <button class="btn btn-outline btn-primary font-semibold hover:scale-105 transition-all duration-300">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="absolute left-2 right-2 md:left-5 md:right-5 top-1/2 flex -translate-y-1/2 transform justify-between z-10">
                <a href="#slide4" class="btn btn-circle btn-sm md:btn-md bg-black bg-opacity-50 text-white border-none hover:bg-opacity-75 transition-all duration-300">❮</a>
                <a href="#slide2" class="btn btn-circle btn-sm md:btn-md bg-black bg-opacity-50 text-white border-none hover:bg-opacity-75 transition-all duration-300">❯</a>
            </div>
        </div>

        <!-- Slide 2 - Double Room -->
        <div id="slide2" class="carousel-item relative w-full">
            <div class="w-full bg-white">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 h-full">
                    <!-- Image Section -->
                    <div class="relative overflow-hidden">
                        <img 
                            src="https://img.daisyui.com/images/stock/photo-1609621838510-5ad474b7d25d.webp" 
                            alt="Double Room"
                        class="w-full h-64 lg:h-full object-cover transition-transform duration-500 hover:scale-110"
                        />
                        <!-- Price Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white px-4 py-2 rounded-full font-bold text-lg shadow-lg">
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
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold w-fit">
                                    2 Persons
                                </span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 mb-6 text-base lg:text-lg leading-relaxed">
                                Spacious room for couples with king-size bed and beautiful city view. Perfect for romantic getaways.
                            </p>

                            <!-- Amenities -->
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-800 mb-3">Amenities:</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Free WiFi</span>
                                    <span class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">AC</span>
                                    <span class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">TV</span>
                                    <span class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Mini Bar</span>
                                    <span class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Balcony</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 mt-auto">
                            <button class="btn btn-success flex-1 text-white font-semibold hover:scale-105 transition-all duration-300">
                                Book Now
                            </button>
                            <button class="btn btn-outline btn-primary font-semibold hover:scale-105 transition-all duration-300">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="absolute left-2 right-2 md:left-5 md:right-5 top-1/2 flex -translate-y-1/2 transform justify-between z-10">
                <a href="#slide1" class="btn btn-circle btn-sm md:btn-md bg-black bg-opacity-50 text-white border-none hover:bg-opacity-75 transition-all duration-300">❮</a>
                <a href="#slide3" class="btn btn-circle btn-sm md:btn-md bg-black bg-opacity-50 text-white border-none hover:bg-opacity-75 transition-all duration-300">❯</a>
            </div>
        </div>

        <!-- Slide 3 - Family Suite -->
        <div id="slide3" class="carousel-item relative w-full">
            <div class="w-full bg-white">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 h-full">
                    <!-- Image Section -->
                    <div class="relative overflow-hidden">
                        <img 
                            src="https://img.daisyui.com/images/stock/photo-1414694762283-acccc27bca85.webp" 
                            alt="Family Suite"
                            class="w-full h-64 lg:h-full object-cover transition-transform duration-500 hover:scale-110"
                        />
                        <!-- Price Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white px-4 py-2 rounded-full font-bold text-lg shadow-lg">
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
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold w-fit">
                                    3-4 Persons
                                </span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 mb-6 text-base lg:text-lg leading-relaxed">
                                Large suite perfect for families with separate living area. Spacious and comfortable for memorable family stays.
                            </p>

                            <!-- Amenities -->
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-800 mb-3">Amenities:</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Free WiFi</span>
                                    <span class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">AC</span>
                                    <span class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">TV</span>
                                    <span class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Kitchen</span>
                                    <span class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Living Area</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 mt-auto">
                            <button class="btn btn-success flex-1 text-white font-semibold hover:scale-105 transition-all duration-300">
                                Book Now
                            </button>
                            <button class="btn btn-outline btn-primary font-semibold hover:scale-105 transition-all duration-300">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="absolute left-2 right-2 md:left-5 md:right-5 top-1/2 flex -translate-y-1/2 transform justify-between z-10">
                <a href="#slide2" class="btn btn-circle btn-sm md:btn-md bg-black bg-opacity-50 text-white border-none hover:bg-opacity-75 transition-all duration-300">❮</a>
                <a href="#slide4" class="btn btn-circle btn-sm md:btn-md bg-black bg-opacity-50 text-white border-none hover:bg-opacity-75 transition-all duration-300">❯</a>
            </div>
        </div>

        <!-- Slide 4 - Deluxe Suite -->
        <div id="slide4" class="carousel-item relative w-full">
            <div class="w-full bg-white">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 h-full">
                    <!-- Image Section -->
                    <div class="relative overflow-hidden">
                        <img 
                            src="https://img.daisyui.com/images/stock/photo-1665553365602-b2fb8e5d1707.webp" 
                            alt="Deluxe Suite"
                            class="w-full h-64 lg:h-full object-cover transition-transform duration-500 hover:scale-110"
                        />
                        <!-- Price Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white px-4 py-2 rounded-full font-bold text-lg shadow-lg">
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
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold w-fit">
                                    2 Persons
                                </span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 mb-6 text-base lg:text-lg leading-relaxed">
                                Luxury suite with premium amenities and panoramic view. Experience the ultimate comfort and elegance.
                            </p>

                            <!-- Amenities -->
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-800 mb-3">Amenities:</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Free WiFi</span>
                                    <span class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">AC</span>
                                    <span class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">TV</span>
                                    <span class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Jacuzzi</span>
                                    <span class="bg-gradient-to-r from-green-400 to-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Premium Service</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 mt-auto">
                            <button class="btn btn-success flex-1 text-white font-semibold hover:scale-105 transition-all duration-300">
                                Book Now
                            </button>
                            <button class="btn btn-outline btn-primary font-semibold hover:scale-105 transition-all duration-300">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="absolute left-2 right-2 md:left-5 md:right-5 top-1/2 flex -translate-y-1/2 transform justify-between z-10">
                <a href="#slide3" class="btn btn-circle btn-sm md:btn-md bg-black bg-opacity-50 text-white border-none hover:bg-opacity-75 transition-all duration-300">❮</a>
                <a href="#slide1" class="btn btn-circle btn-sm md:btn-md bg-black bg-opacity-50 text-white border-none hover:bg-opacity-75 transition-all duration-300">❯</a>
            </div>
        </div>
    </div>

    <!-- Carousel Indicators -->
    <div class="flex justify-center mt-6 space-x-2">
        <a href="#slide1" class="w-3 h-3 bg-gray-300 rounded-full hover:bg-gray-500 transition-all duration-300 cursor-pointer"></a>
        <a href="#slide2" class="w-3 h-3 bg-gray-300 rounded-full hover:bg-gray-500 transition-all duration-300 cursor-pointer"></a>
        <a href="#slide3" class="w-3 h-3 bg-gray-300 rounded-full hover:bg-gray-500 transition-all duration-300 cursor-pointer"></a>
        <a href="#slide4" class="w-3 h-3 bg-gray-300 rounded-full hover:bg-gray-500 transition-all duration-300 cursor-pointer"></a>
    </div>
</section>

<?php include '../Frontend/includes/footer.php';?>
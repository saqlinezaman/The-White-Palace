<?php 
require_once __DIR__ . '/../../admin/config/db_config.php';

$database = new Database();
$db = $database->db_connection();

// সব services আনো
$stmt = $db->prepare("SELECT * FROM services ORDER BY id ASC");
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php'; 
?>

<div class="bg-gray-50">
    <!-- Services Hero Section -->
    <section class="bg-gray-900 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">Our Exclusive Services</h1>
                <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto">
                    Experience world-class amenities and personalized services designed to make your stay unforgettable
                </p>
            </div>
        </div>
    </section>
<!-- Main Services Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Premium Services</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                From luxury accommodations to personalized concierge services, we provide everything you need for a perfect stay
            </p>
            <div class="w-40 h-1 bg-gradient-to-r from-green-400 to-blue-500 mx-auto mt-4 rounded"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($services as $service): ?>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 flex flex-col h-[450px]">
                    
                    <!-- Icon Section -->
                   <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-6 p-5"
     style="background-color: <?= htmlspecialchars($service['color']) ?>;">
    <i class="fa-solid fa-<?= htmlspecialchars($service['icon']) ?> text-white text-4xl"></i>
</div>
 <!-- Icon Section -->
                  

                    <!-- Title -->
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">
                        <?= htmlspecialchars($service['title']) ?>
                    </h3>

                    <!-- Description -->
                    <p class="text-gray-600 leading-relaxed  flex-grow">
                        <?= htmlspecialchars($service['description']) ?>
                    </p>

                    <!-- Features -->
                    <?php 
                        $features = json_decode($service['features'], true);
                        if ($features && is_array($features)): 
                    ?>
                        <ul class="text-gray-600 space-y-2 mt-auto">
                            <?php foreach ($features as $feature): ?>
                                <li class="flex items-center">
                                    <span class=" "><i class="fa fa-circle text-green-500 mr-2"></i></span>
                                    <?= htmlspecialchars($feature) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>



    <!-- Special Amenities Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Special Amenities</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Additional luxury amenities to enhance your stay experience
                </p>
                <div class="w-40 h-1 bg-gradient-to-r from-green-400 to-blue-500 mx-auto mt-4 rounded"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Free WiFi</h3>
                    <p class="text-gray-600 text-sm">High-speed internet throughout the property</p>
                </div>

                <div class="text-center bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Swimming Pool</h3>
                    <p class="text-gray-600 text-sm">Outdoor pool with city views and pool bar</p>
                </div>

                <div class="text-center bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Valet Parking</h3>
                    <p class="text-gray-600 text-sm">Secure parking with valet service available</p>
                </div>

                <div class="text-center bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">24/7 Security</h3>
                    <p class="text-gray-600 text-sm">Round-the-clock security for your peace of mind</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Hours Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Service Hours & Contact</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-green-400 to-blue-500  mb-8 rounded"></div>
                <div class="space-y-6">
                    <div class="bg-gray-50 p-6 rounded-xl hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h4M9 7h6m-6 4h6m-6 4h6"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Front Desk</h3>
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-gray-600"><span class="font-semibold">Hours:</span> 24/7 Available</p>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <p class="text-gray-600"><span class="font-semibold">Phone:</span> +880 1234-567890</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-xl hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-gray-900 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Concierge Service</h3>
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-gray-600"><span class="font-semibold">Hours:</span> 6:00 AM - 11:00 PM</p>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <p class="text-gray-600"><span class="font-semibold">Phone:</span> +880 1234-567891</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-xl hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l-2.5-5M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Room Service</h3>
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-gray-600"><span class="font-semibold">Hours:</span> 24/7 Available</p>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <p class="text-gray-600"><span class="font-semibold">Phone:</span> +880 1234-567892</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                     alt="Hotel Services" 
                     class="w-full h-[500px] object-cover rounded-2xl shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/50 to-transparent rounded-2xl"></div>
                <div class="absolute bottom-8 left-8 right-8 text-white">
                    <h3 class="text-2xl font-bold mb-2">Premium Service Standards</h3>
                    <p class="text-gray-200">Experience hospitality excellence with our dedicated team</p>
                </div>
            </div>
        </div>
    </div>
</section>

   <!-- Call to Action Section -->
<section class="md:h-[60vh] bg-gradient-to-br from-green-600 via-green-500 to-gray-800 relative overflow-hidden flex items-center justify-center md:p-0 p-5">
    <div class="absolute inset-0 bg-gradient-to-r from-green-700/40 to-black/20"></div>
    <div class="relative z-10 text-center text-white px-4 max-w-4xl mx-auto">
        <h2 class="text-3xl md:text-5xl font-bold mb-6 text-white">
            Ready to Start Your Journey?
        </h2>
        <p class="text-lg md:text-xl text-green-50 mb-8 max-w-2xl mx-auto leading-relaxed">
            Experience luxury, comfort, and exceptional service with The White Palace
        </p>
        <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mt-8">
            <a href="<?= $BASE_URL ?>/pages/rooms.php" class="inline-flex items-center bg-white hover:bg-gray-100 text-gray-900 px-10 py-4 rounded-full font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl min-w-[200px] justify-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h4M9 7h6m-6 4h6m-6 4h6"></path>
                </svg>
                Book Your Room
            </a>
            <a href="<?= $BASE_URL ?>/pages/contact_us.php" class="inline-flex items-center bg-transparent border-2 border-white text-white hover:bg-white hover:text-gray-900 px-10 py-4 rounded-full font-bold text-lg transition-all duration-300 transform hover:scale-105 min-w-[200px] justify-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                Contact Us
            </a>
        </div>
    </div>
    <!-- Decorative Elements -->
    <div class="absolute top-20 left-20 w-24 h-24 bg-green-400/20 rounded-full blur-2xl"></div>
    <div class="absolute bottom-20 right-20 w-32 h-32 bg-green-500/15 rounded-full blur-3xl"></div>
    <div class="absolute top-1/3 right-1/3 w-16 h-16 bg-green-300/25 rounded-full blur-xl"></div>
</section>
</div>
 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
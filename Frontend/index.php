<?php
include '../Frontend/includes/header.php';
require_once __DIR__ . '/../admin/config/db_config.php';

$database = new Database();
$db_connection = $database->db_connection();

$user_id = $_SESSION['user_id'] ?? 0;

// 1. Latest completed booking without review
$bookingStmt = $db_connection->prepare("
    SELECT b.id as booking_id 
    FROM bookings b
    LEFT JOIN testimonials t ON b.id = t.booking_id
    WHERE b.user_id = ? AND b.status = 'complete' AND t.id IS NULL
    ORDER BY b.id DESC
    LIMIT 1
");
$bookingStmt->execute([$user_id]);
$booking_to_review = $bookingStmt->fetch(PDO::FETCH_ASSOC);

// 2. Approved testimonials with user names
$testimonialStmt = $db_connection->prepare("
    SELECT t.*, u.username as user_name
    FROM testimonials t
    JOIN users u ON t.user_id = u.id
    ORDER BY t.created_at DESC
");
$testimonialStmt->execute();
$testimonials = $testimonialStmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Fetch categories
$categoryStmt = $db_connection->prepare("SELECT * FROM categories ORDER BY id DESC");
$categoryStmt->execute();
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

// 4. Fetch rooms
$roomsStmt = $db_connection->prepare("
    SELECT r.*, c.room_type as category_name 
    FROM rooms r 
    JOIN categories c ON r.category_id = c.id
    ORDER BY r.id DESC
");
$roomsStmt->execute();
$rooms = $roomsStmt->fetchAll(PDO::FETCH_ASSOC);

// 5. Fetch blogs
$blogsStmt = $db_connection->prepare("SELECT * FROM blogs ORDER BY id DESC");
$blogsStmt->execute();
$blogs = $blogsStmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!-- hero section -->
<div class="hero bg-base-200  relative" style="background-image: url(assets/images/hero4.jpg);">
    <!-- Dark overlay -->
    <div class="absolute inset-0 bg-black" style="opacity: 0.6;"></div>

    <!-- Hero Content -->
    <div class="hero-content py-10 relative w-full flex flex-col items-center text-center text-white space-y-5 ">

        <!-- Text Content -->
        <div class="w-full lg:w-2/3">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">
                Welcome to <br> The White Palace
            </h1>
            <p class="py-6 text-base md:text-lg text-gray-100 leading-relaxed max-w-2xl mx-auto">
                Experience luxury and comfort at its finest. Book your perfect stay with us and create unforgettable
                memories in our world-class hospitality.
            </p>
            <div class="flex gap-4 justify-center">
                <a href="<?= $BASE_URL ?>/pages/about.php"  class="btn bg-green-500 btn-lg text-white font-semibold px-8 hover:scale-105 transition-all duration-300">
                    Read More
                </a>
                <a href="<?= $BASE_URL ?>/pages/contact_us.php" class="btn btn-outline btn-lg font-semibold px-8 text-white border-white hover:bg-white hover:text-gray-800 transition-all duration-300">
                    Contact Us
                </a>
            </div>
        </div>

        <!-- Booking Search Form -->
        <form action="pages/rooms.php" method="GET" id="bookingForm"
            class="w-full md:w-4/5 lg:w-5/6 flex flex-wrap items-center gap-4 bg-white shadow-lg rounded-xl px-8 py-4 text-gray-800 mx-auto">

            <!-- Room Select -->
            <div class="flex-1 min-w-[180px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                <select name="room"
                    class="w-full border rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Select Room</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['room_type']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Check In -->
            <div class="flex-1 min-w-[180px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Check-In</label>
                <input type="date" name="check_in"
                    class="w-full border rounded-lg px-3 py-2 text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="Select date">
            </div>

            <!-- Check Out -->
            <div class="flex-1 min-w-[180px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Check-Out</label>
                <input type="date" name="check_out"
                    class="w-full border rounded-lg px-3 py-2 text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="Select date">
            </div>

            <!-- Search Button -->
            <div class="pt-5 w-full md:w-auto flex justify-center">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-medium px-8 py-3 rounded-lg transition">
                    Search
                </button>
            </div>
        </form>
    </div>
</div>





<!-- category -->
<section class="my-10 mx-3 md:mx-16">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Our Room Options</h1>
        <div class="w-24 h-1 bg-gradient-to-r from-green-400 to-blue-500 mx-auto mt-4 rounded mb-5"></div>
        <div class="">
            <div class="flex flex-wrap justify-center gap-4">
                <?php foreach ($categories as $category): ?>
                    <div class="card bg-base-100 w-80 shadow-lg p-5 object-fit">
                        <figure>
                            <img class=" h-72 w-full "
                                src="<?php echo '../admin/uploads/categories/' . $category['image']; ?>" />
                        </figure>
                        <div class="card-body hover:bg-green-500 transition-colors duration-300">
                            <h2 class="text-center text-lg font-semibold"><?= $category['room_type'] ?></h2>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Room Options Section -->

<section class="my-10 mx-3 md:mx-16">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Book Your Stay</h1>
        <p class="text-lg text-gray-600">Choose from our carefully curated selection of rooms</p>
        <div class="w-24 h-1 bg-gradient-to-r from-green-400 to-blue-500 mx-auto mt-4 rounded">

        </div>
    </div>

    <!-- Room Carousel -->
    <div class="carousel w-full h-[400px] rounded-xl overflow-hidden shadow-lg">
        <?php $slideIndex = 1; ?>
        <?php foreach ($rooms as $room): ?>
            <div id="slide<?= $slideIndex ?>" class="carousel-item relative w-full h-[400px]">
                <div class="w-full h-full bg-white">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 h-full">

                        <!-- Image Section -->
                        <div class="relative h-[400px] overflow-hidden">
                            <img src="<?= '../' . trim($room['image_url']) ?>" alt="<?= htmlspecialchars($room['name']) ?>"
                                class="w-full h-full object-cover transition-transform duration-500 hover:scale-110" />

                            <!-- Price Badge -->
                            <div class="absolute top-4 right-4">
                                <span class="bg-black text-white px-4 py-2 rounded-full font-bold text-lg shadow-lg">
                                    ৳<?= $room['price'] ?>/night
                                </span>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="p-6 lg:p-8 flex flex-col justify-between h-[400px] overflow-y-auto">
                            <div>
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 gap-2">
                                    <h3 class="text-2xl lg:text-3xl font-bold text-gray-800">
                                        <?= htmlspecialchars($room['name']) ?>
                                    </h3>
                                    <span
                                        class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold w-fit">
                                        <?= htmlspecialchars($room['capacity']) ?>
                                        Person<?= $room['capacity'] > 1 ? 's' : '' ?>
                                    </span>
                                </div>

                                <p class="text-green-500 mb-6 text-4xl font-semibold leading-relaxed">
                                    ৳<?= htmlspecialchars($room['price']) ?>
                                </p>

                                <?php if (!empty($room['amenities'])): ?>
                                    <div class="mb-6">
                                        <h4 class="font-semibold text-gray-800 mb-3">Amenities:</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <?php foreach (json_decode($room['amenities'], true) as $amenity): ?>
                                                <span class="bg-blue-700 text-white px-3 py-1 rounded-full text-sm font-medium">
                                                    <?= htmlspecialchars($amenity) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="flex gap-3 mt-auto">
                                <a href="pages/rooms.php?id=<?= $room['id'] ?>"
                                    class="bg-green-500 text-white py-2 rounded font-semibold hover:scale-105 transition-all duration-300 flex-1 text-center">
                                    See availability
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div
                    class="absolute left-2 right-2 md:left-5 md:right-5 top-1/2 flex -translate-y-1/2 justify-between z-10">
                    <a href="#slide<?= $slideIndex == 1 ? count($rooms) : $slideIndex - 1 ?>"
                        class="btn btn-circle btn-sm md:btn-md bg-black bg-opacity-50 text-white border-none hover:bg-opacity-75 transition-all duration-300">❮</a>
                    <a href="#slide<?= $slideIndex == count($rooms) ? 1 : $slideIndex + 1 ?>"
                        class="btn btn-circle btn-sm md:btn-md bg-black bg-opacity-50 text-white border-none hover:bg-opacity-75 transition-all duration-300">❯</a>
                </div>
            </div>
            <?php $slideIndex++; ?>
        <?php endforeach; ?>
    </div>


    <!-- Carousel Indicators -->
    <div class="flex justify-center mt-6 space-x-2">
        <?php for ($i = 1; $i <= $slideIndex - 1; $i++): ?>
            <a href="#slide<?= $i ?>"
                class="w-3 h-3 bg-gray-300 rounded-full hover:bg-gray-500 transition-all duration-300 cursor-pointer"></a>
        <?php endfor; ?>
    </div>
</section>

<!-- blog -->

<section class="my-10 mx-3 md:mx-16">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Why You Should Use <br> our rooms</h1>
        <p class="text-lg text-gray-600">Choose from our carefully curated selection of rooms</p>
        <div class="w-24 h-1 bg-gradient-to-r from-green-400 to-blue-500 mx-auto mt-4 rounded"></div>
    </div>

    <!-- Slider Container -->
    <div class="relative">
        <!-- Previous Button -->
        <button onclick="previousSlide()"
            class="absolute left-0 top-1/2 transform -translate-y-1/2 z-10 bg-white hover:bg-gray-100 rounded-full p-3 shadow-lg transition-all duration-300 -ml-4">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <!-- Next Button -->
        <button onclick="nextSlide()"
            class="absolute right-0 top-1/2 transform -translate-y-1/2 z-10 bg-white hover:bg-gray-100 rounded-full p-3 shadow-lg transition-all duration-300 -mr-4">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <!-- Slider -->
        <div class="overflow-hidden">
            <div id="sliderTrack" class="slider-track flex transition-transform duration-500">
                <?php foreach ($blogs as $blog): ?>
                    <?php
                    // Description Limit (15 words)
                    $words = explode(" ", strip_tags($blog['description']));
                    $shortDesc = implode(" ", array_slice($words, 0, 15)) . (count($words) > 15 ? "..." : "");
                    ?>
                    <div class="w-full md:w-1/3 flex-shrink-0 px-3">
                        <div
                            class="card bg-base-100 shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer group hover:bg-success border border-base-300 w-full max-w-sm mx-auto h-[480px] flex flex-col">
                            <figure class="overflow-hidden h-48">
                                <img src="<?php echo '../admin/uploads/blogs/' . htmlspecialchars($blog['image']) ?>"
                                    alt="<?= htmlspecialchars($blog['title']) ?>"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105" />
                            </figure>

                            <div class="card-body p-6 flex-1">
                                <span class="text-sm text-gray-500"><?= htmlspecialchars($blog['created_at']) ?></span>
                                <h3
                                    class="text-2xl font-bold mb-2 text-base-content group-hover:text-white transition-colors duration-300 line-clamp-2">
                                    <?= htmlspecialchars($blog['title']) ?>
                                </h3>

                                <p
                                    class="text-base-content/70 text-base leading-relaxed group-hover:text-white/90 transition-colors duration-300">
                                    <?= htmlspecialchars($shortDesc) ?>
                                </p>
                            </div>

                            <div class="bg-gray-900 py-4 px-6">
                                <a href="pages/view_blog.php?id=<?= $blog['id'] ?>"
                                    class="bg-white font-medium text-gray-900 py-2 px-5 rounded inline-block text-center">
                                    Read More
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Dots Indicator -->
        <div class="flex justify-center mt-8 space-x-2">
            <?php
            // Show one dot per group of 3 blogs for desktop, no limit
            $dotCount = ceil(count($blogs) / 3);
            for ($i = 0; $i < $dotCount; $i++):
                ?>
                <button onclick="goToSlide(<?= $i ?>)" id="dot-<?= $i ?>"
                    class="w-3 h-3 rounded-full <?= $i === 0 ? 'bg-green-500' : 'bg-gray-300 hover:bg-gray-400' ?> transition-all duration-300"></button>
            <?php endfor; ?>
        </div>
    </div>
</section>

<!-- testimonial -->

<section class="py-8">
    <div class="max-w-6xl mx-auto px-4 relative z-10">
        <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">What Our Clients Say</h1>
        <p class="text-lg text-gray-600">Real feedback from our valued customers who trust us with their success</p>
        <div class="w-24 h-1 bg-gradient-to-r from-green-400 to-blue-500 mx-auto mt-4 rounded"></div>
    </div>
        <div class="relative overflow-hidden <?php echo empty($testimonials) ? 'hidden' : ''; ?>">
            <?php if (empty($testimonials)): ?>
                <div class="text-center text-gray-600 text-lg py-6">No testimonials available at this time.</div>
            <?php else: ?>
                <div id="testimonialTrack" class="flex transition-transform duration-700 ease-in-out gap-6">
                    <?php foreach ($testimonials as $t): ?>
                    <div class="flex-shrink-0 w-full max-w-[350px] mx-auto px-2">
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 p-6 h-full flex flex-col border border-white/20 hover:-translate-y-2 group">
                            <div class="flex flex-col items-center gap-4">
                                <figure class="flex-shrink-0 relative">
                                    <div class="absolute inset-0 bg-gradient-to-r from-green-300 to-green-600 rounded-full animate-pulse opacity-20 scale-110"></div>
                                    <img class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-2xl relative z-10 group-hover:scale-105 transition-transform duration-300"
                                         src="https://ui-avatars.com/api/?name=<?= urlencode($t['user_name']) ?>&background=random&size=150"
                                         alt="<?= htmlspecialchars($t['user_name']) ?>">
                                </figure>
                                <div class="text-center flex-1">
                                    <div class="flex justify-center mb-2 text-xl">
                                        <?php for($i=0; $i<$t['rating']; $i++): ?>
                                            <span class="text-yellow-400 drop-shadow-sm">★</span>
                                        <?php endfor; ?>
                                    </div>
                                    <h2 class="text-xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-2">
                                        <?= htmlspecialchars($t['user_name']) ?>
                                    </h2>
                                    <p class="text-gray-700 mb-3 leading-relaxed text-base italic line-clamp-3 overflow-hidden">
                                        "<?= htmlspecialchars($t['review_text']) ?>"
                                    </p>
                                    <div class="flex items-center justify-center">
                                        <div class="w-2 h-2 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mr-2"></div>
                                        <p class="text-xs font-semibold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                            Verified Customer
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Navigation buttons -->
                <button id="prevTestimonial" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/90 backdrop-blur-sm rounded-full p-3 shadow-xl hover:shadow-2xl hover:bg-white transition-all duration-300 z-20 border border-white/30 hover:scale-110 group">
                    <span class="text-xl font-bold text-gray-600 group-hover:text-blue-600 transition-colors">❮</span>
                </button>
                <button id="nextTestimonial" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/90 backdrop-blur-sm rounded-full p-3 shadow-xl hover:shadow-2xl hover:bg-white transition-all duration-300 z-20 border border-white/30 hover:scale-110 group">
                    <span class="text-xl font-bold text-gray-600 group-hover:text-blue-600 transition-colors">❯</span>
                </button>
            <?php endif; ?>
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

<!-- review pop up -->
<?php if ($booking_to_review): ?>
    <div id="reviewModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden items-center justify-center p-4">
        <div class=" bg-white rounded-3xl shadow-2xl w-full max-w-md relative overflow-hidden max-h-[500px]">

            <!-- Header Section -->
            <div class="bg-green-500 px-8 py-4 text-white relative">
                <button id="closeModal"
                    class="absolute top-2 right-2 text-white hover:text-gray-200 transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                <div class="text-center">
                    <h2 class="text-xl font-bold">Rate Your Stay</h2>
                </div>
            </div>

            <!-- Content Section -->
            <div class="px-8 py-4">
                <!-- Success Message -->
                <div id="reviewMessage" class="hidden text-center mb-4">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Thank You!</h3>
                    <p class="text-gray-900 text-sm">Your review helps us serve you better</p>
                </div>

                <!-- Review Form -->
                <form id="reviewForm" class="space-y-4">
                    <input type="hidden" name="booking_id" value="<?= $booking_to_review['booking_id'] ?>">

                    <!-- Rating Section -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">How was your experience?</label>
                        <div class="bg-gray-100 p-3 rounded">
                            <select name="rating"
                                class="w-full bg-transparent border-0 text-base font-medium text-gray-500 focus:ring-0 focus:outline-none"
                                required>
                                <option value="">Choose your rating</option>
                                <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                                <option value="4">⭐⭐⭐⭐ Very Good</option>
                                <option value="3">⭐⭐⭐ Good</option>
                                <option value="2">⭐⭐ Fair</option>
                                <option value="1">⭐ Poor</option>
                            </select>
                        </div>
                    </div>

                    <!-- Review Text Section -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Share your thoughts</label>
                        <div class="relative">
                            <textarea name="review_text" rows="3"
                                class="w-full px-3 py-2 border border-gray-700 rounded focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none transition-all duration-300 bg-gray-100 text-gray-900"
                                placeholder="Tell us about your experience..." required></textarea>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full  bg-green-500 to hover::bg-green-600 text-white font-bold py-3 px-4 rounded transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        Submit Review
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- review JS -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById('reviewModal');
        const closeBtn = document.getElementById('closeModal');
        const reviewForm = document.getElementById("reviewForm");
        const reviewMessage = document.getElementById("reviewMessage");

        const bookingId = document.querySelector('#reviewForm input[name="booking_id"]')?.value;

        // Show modal only if not dismissed for this booking
        if (bookingId && localStorage.getItem('dismissed_review_' + bookingId) !== 'true') {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Close modal button
        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            if (bookingId) localStorage.setItem('dismissed_review_' + bookingId, 'true');
        });

        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                if (bookingId) localStorage.setItem('dismissed_review_' + bookingId, 'true');
            }
        });

        // Submit review
        reviewForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch("pages/save_review.php", {
                method: "POST",
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Hide form, show message
                        reviewForm.style.display = 'none';
                        reviewMessage.classList.remove('hidden');
                        if (bookingId) localStorage.setItem('dismissed_review_' + bookingId, 'true');

                        // Auto close after 3 seconds
                        setTimeout(() => {
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        }, 3000);
                    } else {
                        reviewMessage.innerHTML = `
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-1">Oops!</h3>
                    <p class="text-gray-300 text-sm">Failed to save review. Please try again.</p>
                `;
                        reviewMessage.classList.remove('hidden');
                    }
                })
                .catch(() => {
                    reviewMessage.innerHTML = `
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">Connection Error</h3>
                <p class="text-gray-300 text-sm">Something went wrong. Please check your connection.</p>
            `;
                    reviewMessage.classList.remove('hidden');
                });
        });
    });
</script>

<!-- blog carousel js -->
<script>
    let currentSlide = 0;
    const totalSlides = <?= count($blogs) ?>;
    const track = document.getElementById('sliderTrack');

    function updateSlider() {
        const isMobile = window.innerWidth < 768;
        let translateX = -currentSlide * 100;

        track.style.transform = `translateX(${translateX}%)`;

        // Update dots (dots are primarily for desktop, but we cap the active dot to available dots)
        let dotCount;
        if (isMobile) {
            dotCount = totalSlides;  // In mobile, conceptually more dots, but since HTML has fewer, we use what's available
        } else {
            dotCount = Math.ceil(totalSlides / 3);
        }
        for (let i = 0; i < dotCount; i++) {
            const dot = document.getElementById(`dot-${i}`);
            if (dot) {
                if (i === currentSlide) {
                    dot.classList.remove('bg-gray-300', 'hover:bg-gray-400');
                    dot.classList.add('bg-green-500');
                } else {
                    dot.classList.remove('bg-green-500');
                    dot.classList.add('bg-gray-300', 'hover:bg-gray-400');
                }
            }
        }
    }

    function nextSlide() {
        const isMobile = window.innerWidth < 768;
        let maxSlides;
        if (isMobile) {
            maxSlides = totalSlides;
        } else {
            maxSlides = Math.ceil(totalSlides / 3);
        }
        currentSlide = (currentSlide + 1) % maxSlides;
        updateSlider();
    }

    function previousSlide() {
        const isMobile = window.innerWidth < 768;
        let maxSlides;
        if (isMobile) {
            maxSlides = totalSlides;
        } else {
            maxSlides = Math.ceil(totalSlides / 3);
        }
        currentSlide = (currentSlide - 1 + maxSlides) % maxSlides;
        updateSlider();
    }

    function goToSlide(index) {
        const isMobile = window.innerWidth < 768;
        let maxSlides = isMobile ? totalSlides : Math.ceil(totalSlides / 3);
        if (index >= 0 && index < maxSlides) {
            currentSlide = index;
            updateSlider();
        }
    }

    // Update slider on window resize
    window.addEventListener('resize', updateSlider);

    // Initialize slider
    updateSlider();
</script>

<!-- testimonial carousel -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('testimonialTrack');
    const prevBtn = document.getElementById('prevTestimonial');
    const nextBtn = document.getElementById('nextTestimonial');

    if (!track) return;

    const items = track.children;
    const totalItems = items.length;
    const itemsPerSlide = 3; // প্রতি slide এ 3 টা card
    const totalSlides = Math.ceil(totalItems / itemsPerSlide);

    let currentIndex = 0;

    function updateTrack() {
        if (totalSlides === 0) return;

        // সীমার মধ্যে currentIndex রাখি
        currentIndex = Math.max(0, Math.min(currentIndex, totalSlides - 1));

        const translateX = -(currentIndex * 100);
        track.style.transform = `translateX(${translateX}%)`;

        updateButtons();
    }

    function updateButtons() {
        if (totalSlides <= 1) {
            prevBtn.disabled = true;
            nextBtn.disabled = true;
            prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
            nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
            return;
        }
        // Prev
        if (currentIndex === 0) {
            prevBtn.disabled = true;
            prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            prevBtn.disabled = false;
            prevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
        // Next
        if (currentIndex === totalSlides - 1) {
            nextBtn.disabled = true;
            nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            nextBtn.disabled = false;
            nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    nextBtn.addEventListener('click', () => {
        if (currentIndex < totalSlides - 1) {
            currentIndex++;
            updateTrack();
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateTrack();
        }
    });

    window.addEventListener('resize', updateTrack);
    updateTrack();
});
</script>



<?php include '../Frontend/includes/footer.php'; ?>
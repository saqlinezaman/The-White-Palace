<?php
include '../Frontend/includes/header.php';
require_once __DIR__ . '/../admin/config/db_config.php';

// Database connection
$database = new Database();
$db_connection = $database->db_connection();


$statement = $db_connection->prepare("SELECT * FROM categories ORDER BY id DESC");
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);

// Fetch rooms
$roomsStmt = $db_connection->prepare("
    SELECT r.*, c.room_type as category_name 
    FROM rooms r 
    JOIN categories c ON r.category_id = c.id
    ORDER BY r.id DESC
");
$roomsStmt->execute();
$rooms = $roomsStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch blogs
$stmt = $db_connection->prepare("SELECT * FROM blogs ORDER BY id DESC");
$stmt->execute();
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <button
                    class="btn bg-green-500 btn-lg text-white font-semibold px-8 hover:scale-105 transition-all duration-300">
                    Read More
                </button>
                <button
                    class="btn btn-outline btn-lg font-semibold px-8 text-white border-white hover:bg-white hover:text-gray-800 transition-all duration-300">
                    Learn More
                </button>
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
                            <img class=" h-72 w-full " src="<?php echo '../admin/uploads/categories/' . $category['image']; ?>" />
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
                        <img src="<?= '../' . trim($room['image_url']) ?>" 
                             alt="<?= htmlspecialchars($room['name']) ?>"
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
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold w-fit">
                                    <?= htmlspecialchars($room['capacity']) ?> 
                                    Person<?= $room['capacity'] > 1 ? 's' : '' ?>
                                </span>
                            </div>

                            <p class="text-green-500 mb-6 text-4xl font-semibold leading-relaxed"> ৳<?= htmlspecialchars($room['price']) ?>
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
            <div class="absolute left-2 right-2 md:left-5 md:right-5 top-1/2 flex -translate-y-1/2 justify-between z-10">
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
        <button onclick="previousSlide()" class="absolute left-0 top-1/2 transform -translate-y-1/2 z-10 bg-white hover:bg-gray-100 rounded-full p-3 shadow-lg transition-all duration-300 -ml-4">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <!-- Next Button -->
        <button onclick="nextSlide()" class="absolute right-0 top-1/2 transform -translate-y-1/2 z-10 bg-white hover:bg-gray-100 rounded-full p-3 shadow-lg transition-all duration-300 -mr-4">
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
                    <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer group hover:bg-success border border-base-300 w-full max-w-sm mx-auto h-[480px] flex flex-col">
                        <figure class="overflow-hidden h-48">
                            <img src="<?php echo '../admin/uploads/blogs/'.htmlspecialchars($blog['image']) ?>" alt="<?= htmlspecialchars($blog['title']) ?>"
                                class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105" />
                        </figure>

                        <div class="card-body p-6 flex-1">
                            <span class="text-sm text-gray-500"><?= htmlspecialchars($blog['created_at']) ?></span>
                            <h3 class="text-2xl font-bold mb-2 text-base-content group-hover:text-white transition-colors duration-300 line-clamp-2">
                                <?= htmlspecialchars($blog['title']) ?>
                            </h3>

                            <p class="text-base-content/70 text-base leading-relaxed group-hover:text-white/90 transition-colors duration-300">
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
<?php include '../Frontend/includes/footer.php'; ?>
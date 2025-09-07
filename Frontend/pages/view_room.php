<?php
include '../includes/header.php';
require_once __DIR__ . '/../../admin/config/db_config.php';

// Database connection
$database = new Database();
$db_connection = $database->db_connection();

// Check if room id is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p>Invalid Room ID.</p>";
    exit;
}

$roomId = $_GET['id'];

// Fetch room details
$roomStmt = $db_connection->prepare("
    SELECT r.*, c.room_type 
    FROM rooms r 
    JOIN categories c ON r.category_id = c.id 
    WHERE r.id = :id
");
$roomStmt->bindParam(':id', $roomId, PDO::PARAM_INT);
$roomStmt->execute();
$room = $roomStmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    echo "<p>Room not found.</p>";
    exit;
}

// Decode gallery images if stored as JSON
$galleryImages = [];
if (!empty($room['gallery_images'])) {
    $galleryImages = json_decode($room['gallery_images'], true);
}

// Main image path
$mainImage = !empty($room['image_url']) ? '/thewhitepalace/admin/' . trim($room['image_url']) : '/thewhitepalace/Frontend/assets/images/default-room.jpg';
?>

<div class="mx-5 md:mx-20 my-10 flex mt-10">
    <div class="w-full md:w-7/12">
        <div class="h-[450px]">
            <img src="<?= $mainImage ?>" 
                 alt="<?= htmlspecialchars($room['name']) ?>" 
                 class="w-full h-full object-cover rounded-lg shadow-md">
        </div>
         <!-- Gallery Images -->
    <?php if (!empty($galleryImages)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 my-6">
            <?php foreach ($galleryImages as $image): ?>
                <?php $galleryPath = '/thewhitepalace/admin/' . trim($image); ?>
                <div class="overflow-hidden rounded-lg shadow-md">
                    <img src="<?= $galleryPath ?>" 
                         alt="<?= htmlspecialchars($room['name']) ?>" 
                         class="w-full h-36 object-cover transition-transform duration-300 hover:scale-105">
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-500 mb-6">No gallery images available for this room.</p>
    <?php endif; ?>
    </div>
    <div class="w-full md:w-5/12 pl-6">
         <h1 class="text-4xl font-semibold mb-4"><?= htmlspecialchars($room['name']) ?></h1>
         <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-medium">
            <?= htmlspecialchars($room['room_type']) ?>
        </span>
        <p class="text-gray-600 mt-6"><?= nl2br(htmlspecialchars($room['description'])) ?></p>
        <!-- Amenities -->
    <?php if (!empty($room['amenities'])): ?>
        <div class="my-5">
            <div class="flex flex-wrap gap-2">
                <?php foreach (json_decode($room['amenities'], true) as $amenity): ?>
                    <span class="bg-blue-700 text-white px-3 py-1 rounded-full text-sm font-medium">
                        <?= htmlspecialchars($amenity) ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
        <h1 class="text-3xl font-semibold mt-5 text-green-700">৳ <?= $room['price'] ?>/night  </h1>
        <div class="flex flex-wrap gap-4 mt-5">
        <a href="rooms.php" 
           class="btn bg-gray-300 text-gray-800 font-semibold px-6 py-2 rounded hover:bg-gray-400 transition-all duration-300">
            Back to Rooms
        </a>
        <a href="book_room.php?id=<?= $room['id'] ?>" 
           class="btn bg-green-500 text-white font-semibold px-6 py-2 rounded hover:bg-green-600 transition-all duration-300">
            Book Now
        </a>
    </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

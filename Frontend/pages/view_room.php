<?php
include '../includes/header.php';
require_once __DIR__ . '/../../admin/config/db_config.php';

$database = new Database();
$db_connection = $database->db_connection();

// Room ID check
$roomId = $_GET['id'] ?? 0;
$roomId = intval($roomId);

if ($roomId <= 0) {
    echo "<p>Invalid Room ID.</p>";
    exit;
}

// Check-in & Check-out, default to today & tomorrow
$checkIn = $_GET['check_in'] ?? date('Y-m-d'); 
$checkOut = $_GET['check_out'] ?? date('Y-m-d', strtotime($checkIn . ' +1 day')); 

// Fetch room info with availability
$stmt = $db_connection->prepare("
    SELECT r.*, c.room_type as category_name,
    (
        r.total_rooms - (
            SELECT COUNT(*) FROM bookings b
            WHERE b.room_id = r.id
              AND b.status IN ('pending','approved')
              AND NOT (b.check_out <= ? OR b.check_in >= ?)
        )
    ) AS available_rooms
    FROM rooms r
    JOIN categories c ON r.category_id = c.id
    WHERE r.id = ?
");
$stmt->execute([$checkIn, $checkOut, $roomId]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    echo "<p>Room not found.</p>";
    exit;
}

// Gallery images decode
$galleryImages = [];
if (!empty($room['gallery_images'])) {
    $galleryImages = json_decode($room['gallery_images'], true);
}
?>

<div class="max-w-7xl mx-auto my-10 px-5 md:px-12">
    <!-- Main section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left side: Main Image + Gallery -->
        <div class="space-y-4">
            <div class="rounded-lg overflow-hidden shadow-md">
                <img src="../../<?= $room['image_url']; ?>" 
                     alt="<?= htmlspecialchars($room['name']); ?>" 
                     class="w-full h-96 object-cover">
            </div>

            <!-- Gallery -->
            <?php if (!empty($galleryImages)): ?>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    <?php foreach ($galleryImages as $image): ?>
                        <?php $galleryPath = '/thewhitepalace/' . trim($image); ?>
                        <div class="overflow-hidden rounded-lg shadow-md">
                            <img src="<?= $galleryPath ?>" 
                                 alt="<?= htmlspecialchars($room['name']); ?>" 
                                 class="w-full h-24 object-cover transition-transform duration-300 hover:scale-105">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500">No gallery images available.</p>
            <?php endif; ?>
        </div>

        <!-- Right side: Room info & Booking -->
        <div class="space-y-6">
            <h1 class="text-4xl font-bold text-gray-800"><?= htmlspecialchars($room['name']); ?></h1>

            <!-- Category & Availability -->
            <div class="flex flex-wrap gap-3 items-center">
                <span class="bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded">
                    <?= htmlspecialchars($room['category_name']); ?>
                </span>
                <span class="bg-blue-100 text-blue-700 text-sm px-3 py-1 rounded">
                    Capacity: <?= $room['total_rooms']; ?>
                </span>
                <?php if ($room['available_rooms'] > 0): ?>
                    <span class="bg-green-100 text-green-700 text-sm px-3 py-1 rounded">
                        <?= $room['available_rooms']; ?> Available
                    </span>
                <?php else: ?>
                    <span class="bg-red-100 text-red-700 text-sm px-3 py-1 rounded">
                        No room available
                    </span>
                <?php endif; ?>
            </div>

            <p class="text-2xl font-bold text-green-600">৳<?= $room['price']; ?>/night</p>
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
            <!-- Booking Form -->
            <form action="book_room.php" method="GET" class="space-y-4 bg-gray-50 p-5 rounded-lg shadow-md">
                <input type="hidden" name="room_id" value="<?= $room['id']; ?>">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Check In</label>
                    <input type="date" name="check_in" value="<?= htmlspecialchars($checkIn); ?>" class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Check Out</label>
                    <input type="date" name="check_out" value="<?= htmlspecialchars($checkOut); ?>" class="w-full border rounded p-2">
                </div>

                <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition"
                        <?= ($room['available_rooms'] <= 0) ? 'disabled' : ''; ?>>
                    <?= ($room['available_rooms'] > 0) ? 'Book Now' : 'Unavailable'; ?>
                </button>
            </form>
        </div>
    </div>

    <!-- Full width Description -->
    <div class="mt-12 bg-gray-50 p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-3">Room Details</h2>
        <p class="text-gray-700"><?= $room['description']; ?></p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

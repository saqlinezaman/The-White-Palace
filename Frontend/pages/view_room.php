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
$checkIn = $_GET['check_in'] ?? date('Y-m-d'); // default today
$checkOut = $_GET['check_out'] ?? date('Y-m-d', strtotime($checkIn . ' +1 day')); // default tomorrow

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
?>

<div class="max-w-6xl mx-auto my-10 px-5 md:px-0">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <img src="../../<?= $room['image_url']; ?>" 
                 alt="<?= htmlspecialchars($room['name']); ?>" 
                 class="w-full h-96 object-cover rounded-lg shadow-md">
        </div>
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <?= htmlspecialchars($room['name']); ?>
            </h1>
            <div class="flex gap-2 items-center mb-4">
                <span class="bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded">
                    <?= htmlspecialchars($room['category_name']); ?>
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

            <p class="text-green-600 font-bold text-xl mt-2">৳<?= $room['price']; ?>/night</p>

            <!-- Booking Form -->
            <form action="book_room.php" method="GET" class="mt-6 space-y-4">
                <input type="hidden" name="room_id" value="<?= $room['id']; ?>">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Check In</label>
                    <input type="date" name="check_in" value="<?= htmlspecialchars($checkIn); ?>" class="w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Check Out</label>
                    <input type="date" name="check_out" value="<?= htmlspecialchars($checkOut); ?>" class="w-full border rounded p-2">
                </div>
                <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition"
                        <?= ($room['available_rooms'] <= 0) ? 'disabled' : ''; ?>>
                    <?= ($room['available_rooms'] > 0) ? 'Book Now' : 'Unavailable'; ?>
                </button>
            </form>
        </div>
    </div>

    <div class="mt-10">
        <h2 class="text-2xl font-semibold mb-2">Room Details</h2>
        <p class="text-gray-700"><?= $room['description']; ?></p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<?php
require_once __DIR__ . '/../../admin/config/db_config.php';
include '../includes/header.php';

$database = new Database();
$db_connection = $database->db_connection();

// Category গুলো নেব
$statement = $db_connection->prepare("SELECT * FROM categories ORDER BY id DESC");
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);

$checkIn = $_GET['check_in'] ?? '';
$checkOut = $_GET['check_out'] ?? '';
$errorMsg = '';

// PHP server-side validation
if (!empty($checkIn) && !empty($checkOut)) {
    if ($checkOut <= $checkIn) {
        $errorMsg = 'Error: Check-out date must be after Check-in date.';
        $checkOut = ''; // reset invalid date
    }
}

// Build Query
if (!empty($checkIn) && !empty($checkOut) && empty($errorMsg)) {
    // Availability check
    $query = "
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
        WHERE 1
    ";
    $params = [$checkIn, $checkOut];
} else {
    // Default: show total rooms
    $query = "
        SELECT r.*, c.room_type as category_name,
               r.total_rooms AS available_rooms
        FROM rooms r
        JOIN categories c ON r.category_id = c.id
        WHERE 1
    ";
    $params = [];
}

// Room type filter
if (!empty($_GET['room'])) {
    $query .= " AND r.category_id = ?";
    $params[] = $_GET['room'];
}

$query .= " ORDER BY r.id DESC";

$stmt = $db_connection->prepare($query);
$stmt->execute($params);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="text-center my-10">
    <h1 class="text-4xl font-bold text-gray-800 mb-2">Available Rooms</h1>
    <p class="text-lg text-gray-600">Find the perfect room for your stay</p>
    <div class="w-24 h-1 bg-gradient-to-r from-green-400 to-blue-500 mx-auto mt-4 rounded"></div>
</div>

<!-- Filter Form -->
<div class="mx-5 md:mx-20 mb-10 bg-white shadow p-5 rounded-lg">
    <?php if($errorMsg): ?>
        <p class="text-red-600 mb-4 text-center"><?= $errorMsg; ?></p>
    <?php endif; ?>
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4" id="searchForm">
        <div>
            <label class="block text-sm font-medium text-gray-700">Room Type</label>
            <select name="room" class="w-full border rounded p-2">
                <option value="">All</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id']; ?>" <?= (($_GET['room'] ?? '') == $cat['id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($cat['room_type']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Check In</label>
            <input type="date" name="check_in" value="<?= htmlspecialchars($checkIn); ?>" class="w-full border rounded p-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Check Out</label>
            <input type="date" name="check_out" value="<?= htmlspecialchars($checkOut); ?>" class="w-full border rounded p-2">
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
                Search
            </button>
        </div>
    </form>
</div>

<!-- JS client-side validation -->
<script>
document.getElementById('searchForm').addEventListener('submit', function(e) {
    const checkIn = document.querySelector('input[name="check_in"]').value;
    const checkOut = document.querySelector('input[name="check_out"]').value;

    if (checkIn && checkOut) {
        if (checkOut <= checkIn) {
            e.preventDefault();
            alert('Check-out date must be after Check-in date.');
        }
    }
});
</script>

<!-- Room Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mx-5 md:mx-20 mb-10">
    <?php if(count($rooms) > 0): ?>
        <?php foreach($rooms as $room): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="../../<?= $room['image_url']; ?>" 
                     alt="<?= htmlspecialchars($room['name']); ?>" 
                     class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center justify-between">
                        <?= htmlspecialchars($room['name']); ?>
                        <?php if (!empty($checkIn) && !empty($checkOut) && !$errorMsg): ?>
                            <?php if ($room['available_rooms'] > 0): ?>
                                <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded">
                                    <?= $room['available_rooms']; ?> Available
                                </span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded">
                                    No room available
                                </span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">
                                <?= $room['available_rooms']; ?> Total
                            </span>
                        <?php endif; ?>
                    </h3>
                    <p class="text-green-600 font-bold mt-2">৳<?= $room['price']; ?>/night</p>
                    <a href="view_room.php?id=<?= $room['id'] ?>&check_in=<?= $checkIn; ?>&check_out=<?= $checkOut; ?>" 
                       class="mt-3 inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        View Details
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-center col-span-3 text-gray-600">No rooms found for your search.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>

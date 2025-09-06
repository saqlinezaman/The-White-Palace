<?php
require_once __DIR__ . '/../../admin/config/db_config.php';
include '../includes/header.php';
$database = new Database();
$db_connection = $database->db_connection();

$statement = $db_connection->prepare("SELECT * FROM categories ORDER BY id DESC");
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);


// Default Query (সব রুম দেখাবে যদি search না করে)
$query = "
    SELECT r.*, c.room_type as category_name 
    FROM rooms r
    JOIN categories c ON r.category_id = c.id
    WHERE 1
";
$params = [];

// যদি Room Type select করে
if (!empty($_GET['room'])) {
    $query .= " AND r.category_id = ?";
    $params[] = $_GET['room'];
}

// যদি Check-In আর Check-Out আসে (এখন শুধু filter হিসেবে ব্যবহার করলাম)
if (!empty($_GET['check_in']) && !empty($_GET['check_out'])) {
    // এখানে future enhancement হিসেবে availability check করা যাবে
    // এখন শুধু তারিখ নিচ্ছি
    $checkIn = $_GET['check_in'];
    $checkOut = $_GET['check_out'];
    // ভবিষ্যতে বুকিং টেবিল থাকলে এখানে filter হবে
}

$query .= " ORDER BY r.id DESC";

$stmt = $db_connection->prepare($query);
$stmt->execute($params);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="text-center my-10">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Available Rooms</h1>
            <p class="text-lg text-gray-600">Find the perfect room for your stay`</p>
            <div class="w-24 h-1 bg-gradient-to-r from-green-400 to-blue-500 mx-auto mt-4 rounded"></div>
        </div>
        <form action="rooms.php" method="GET"
            class="w-full md:w-5/6 flex flex-wrap items-center gap-4 bg-white shadow-2xl rounded-xl px-8 py-4 text-gray-800 mx-auto mb-10">

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

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mx-5 md:mx-20 mb-10">
    <?php if(count($rooms) > 0): ?>
        <?php foreach($rooms as $room): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="../../admin/<?= $room['image_url']; ?>" 
                     alt="<?= htmlspecialchars($room['name']); ?>" 
                     class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <?= htmlspecialchars($room['name']); ?>
                    </h3>
                    <p class="text-sm text-gray-500 mb-2"><?= htmlspecialchars($room['category_name']); ?></p>
                    <p class="text-green-600 font-bold">৳<?= $room['price']; ?>/night</p>
                    <a href="room_details.php?id=<?= $room['id']; ?>" 
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

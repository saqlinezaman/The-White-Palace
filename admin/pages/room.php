<?php
require_once __DIR__ . '/../config/db_config.php';
$database = new Database();
$db_connection = $database->db_connection();

// Fetch categories
$catStmt = $db_connection->prepare("SELECT * FROM categories ORDER BY id DESC");
$catStmt->execute();
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch rooms
$roomsStmt = $db_connection->prepare("
    SELECT r.*, c.room_type as category_name 
    FROM rooms r 
    JOIN categories c ON r.category_id = c.id
    ORDER BY r.id DESC
");
$roomsStmt->execute();
$rooms = $roomsStmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="my-10 mx-3 md:mx-16">

    <!-- Rooms Table -->
    <div class="overflow-x-auto">
       <div class="d-flex justify-content-between align-items-center mb-4">
         <h4 class="text-lg font-semibold">Room List</h4>
         <a href="index.php?page=add_rooms" class="btn btn-info rounded-0 text-white">Add Room</a>
       </div>
        <table class="table table-bordered table-striped">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Capacity</th>
                    <th>Amenities</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rooms as $room): ?>
                    <tr>
                        <td><?= $room['id'] ?></td>
                        <td><?= htmlspecialchars($room['name']) ?></td>
                        <td><?= htmlspecialchars($room['category_name']) ?></td>
                        <td>৳<?= $room['price'] ?>/night</td>
                        <td><?= $room['capacity'] ?></td>
                        <td><?= implode(", ", json_decode($room['amenities'],true)) ?></td>
                        <td>
                            <?php if($room['image_url']): ?>
                                <img src="../<?= $room['image_url'] ?>" width="80" alt="Room Image">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td class="flex gap-2">
                            <a href="index.php?page=edit_room&id=<?= $room['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="index.php?page=delete_room&id=<?= $room['id'] ?>" onclick="return confirm('Are you sure?');" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

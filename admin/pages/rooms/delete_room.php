<?php
require_once __DIR__ . '/../../config/db_config.php';

$database = new Database();
$db_connection = $database->db_connection();

// Delete Room
if(isset($_GET['id'])){
    $roomId = $_GET['id'];

    // Fetch room to delete image
    $stmt = $db_connection->prepare("SELECT image_url FROM rooms WHERE id=?");
    $stmt->execute([$roomId]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    // Delete image if exists
    if($room && !empty($room['image_url']) && file_exists(__DIR__ . '/../../' . $room['image_url'])){
        unlink(__DIR__ . '/../../' . $room['image_url']);
    }

    // Delete room from DB
    $stmt = $db_connection->prepare("DELETE FROM rooms WHERE id=?");
    $stmt->execute([$roomId]);

    header("Location: room.php");
    exit;
} else {
    header("Location: room.php");
    exit;
}
?>

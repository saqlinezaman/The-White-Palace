<?php
require_once __DIR__ . '/../config/db_config.php';

// Database connection
$database = new Database();
$db_connection = $database->db_connection();

// Get Room ID
$roomId = $_GET['id'] ?? null;
if (!$roomId) {
    die("Invalid room ID.");
}

// Fetch room info
$stmt = $db_connection->prepare("SELECT * FROM rooms WHERE id = :id");
$stmt->bindParam(':id', $roomId, PDO::PARAM_INT);
$stmt->execute();
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    die("Room not found.");
}

// Delete main image
$uploadDir = __DIR__ . '/../uploads/rooms/';
if (!empty($room['image_url']) && file_exists($uploadDir . $room['image_url'])) {
    unlink($uploadDir . $room['image_url']);
}

// Delete room record
$deleteStmt = $db_connection->prepare("DELETE FROM rooms WHERE id = :id");
$deleteStmt->bindParam(':id', $roomId, PDO::PARAM_INT);

if ($deleteStmt->execute()) {
    echo "<script>window.location.href = 'index.php?page=room&deleted=1';</script>";
    exit;
} else {
    die("Failed to delete room.");
}

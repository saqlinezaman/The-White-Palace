<?php
session_start();
require_once __DIR__ . '/../../admin/config/db_config.php';
header('Content-Type: application/json');

$database = new Database();
$db = $database->db_connection();

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_id) {
    $booking_id = intval($_POST['booking_id'] ?? 0);
    $rating = intval($_POST['rating'] ?? 0);
    $review_text = trim($_POST['review_text'] ?? '');

    if($booking_id && $rating && $review_text) {
        $stmt = $db->prepare("
            INSERT INTO testimonials (booking_id, user_id, rating, review_text, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$booking_id, $user_id, $rating, $review_text]);
        echo json_encode(['success' => true]);
        exit;
    }
}

echo json_encode(['success' => false]);

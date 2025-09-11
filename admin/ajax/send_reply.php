<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config/db_config.php";

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->db_connection();
    if (!$db) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
        exit;
    }

    // Log POST data for debugging
    file_put_contents('debug.log', print_r($_POST, true), FILE_APPEND);

    // Get POST data
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $reply = isset($_POST['reply']) ? trim($_POST['reply']) : '';

    if ($id <= 0 || empty($reply)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input: ID or reply missing']);
        exit;
    }

    // Update the contact table
    $sql = "UPDATE contact SET is_replied = 1, replied_text = :reply, replied_at = NOW() WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':reply', $reply, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if (!$stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'SQL Error: ' . implode(', ', $stmt->errorInfo())]);
        exit;
    }

    echo json_encode(['status' => 'success', 'message' => 'Reply sent successfully']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
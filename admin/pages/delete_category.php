<?php
require_once __DIR__ . '/../config/db_config.php';

// Database connection
$database = new Database();
$db_connection = $database->db_connection();

// get ID
$id = $_GET['id'] ?? null;
if (!$id) {
    die("Invalid category ID.");
}

// fetch category for image
$stmt = $db_connection->prepare("SELECT * FROM categories WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    die("Category not found.");
}

// delete image
$uploadDir = __DIR__ . '/../uploads/categories/';
if (file_exists($uploadDir . $category['image'])) {
    unlink($uploadDir . $category['image']);
}

// delete category
$deleteStmt = $db_connection->prepare("DELETE FROM categories WHERE id = :id");
$deleteStmt->bindParam(':id', $id);
if ($deleteStmt->execute()) {
   echo "<script>window.location.href = 'index.php?page=add_categories&deleted=1';</script>";
    exit;
} else {
    die("Failed to delete category.");
}
?>

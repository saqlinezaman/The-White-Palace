<?php
ob_start();
error_reporting(0);
require_once __DIR__ . '/../config/db_config.php';

// Database connection
$database = new Database();
$db_connection = $database->db_connection();

$errorMessage = '';
$successMessage = '';

// Get category ID from URL
$id = $_GET['id'] ?? null;
if (!$id) {
    ob_end_clean();
    die("Invalid category ID.");
}

// Fetch category
$stmt = $db_connection->prepare("SELECT * FROM categories WHERE id = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    ob_end_clean();
    die("Category not found.");
}

// Update category
if (isset($_POST['updateCategory'])) {
    $room_type = trim($_POST['room_type']);
    if (empty($room_type)) {
        $errorMessage = "Category name is required.";
    } else {
        $image = $category['image'];

        // Handle new image upload
        if (!empty($_FILES['category_image']['name'])) {
            $uploadDir = __DIR__ . '/../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newImage = basename($_FILES['category_image']['name']);
            $targetFile = $uploadDir . $newImage;

            if (move_uploaded_file($_FILES['category_image']['tmp_name'], $targetFile)) {
                if (!empty($category['image']) && file_exists($uploadDir . $category['image'])) {
                    unlink($uploadDir . $category['image']);
                }
                $image = $newImage;
            } else {
                $errorMessage = "Failed to upload new image.";
            }
        }

        if (empty($errorMessage)) {
            $updateStmt = $db_connection->prepare(
                "UPDATE categories SET room_type = :room_type, image = :image WHERE id = :id"
            );
            $updateStmt->bindValue(':room_type', $room_type);
            $updateStmt->bindValue(':image', $image);
            $updateStmt->bindValue(':id', $id, PDO::PARAM_INT);

            if ($updateStmt->execute()) {
                ob_end_clean();
               
                echo "<script>window.location.href = 'index.php?page=add_categories&success=1';</script>";  
                
                exit;
            } else {
                $errorInfo = $updateStmt->errorInfo();
                $errorMessage = "Failed to update category. Error: " . $errorInfo[2];
            }
        }
    }
}

// Include HTML templates AFTER processing
require_once __DIR__ . '/../includes/header.php'; // Now includes HTML
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="ml-64 p-6">
    <div class="card bg-white shadow p-4 rounded">
        <h4 class="mb-4">Edit Category</h4>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Category updated successfully!</div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Category Name</label>
                <input type="text" class="form-control" name="room_type"
                    value="<?= htmlspecialchars($category['room_type']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Category Image</label>
                <input type="file" class="form-control" name="category_image">
                <?php if (!empty($category['image'])): ?>
                    <p class="mt-2">Current Image:</p>
                    <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($category['image']) ?>" width="100"
                        alt="Category Image">
                <?php endif; ?>
            </div>

            <button type="submit" name="updateCategory" class="btn btn-primary">Update Category</button>
            <a href="add_categories.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
ob_end_flush();
?>
<?php
ob_start();
require_once __DIR__ . '/../../config/db_config.php';

// Database connection
$database = new Database();
$db_connection = $database->db_connection();

$errorMessage = '';

// Get category ID from URL
$id = $_GET['id'] ?? null;
if (!$id) {
    die("Invalid category ID.");
}

// Fetch category
$stmt = $db_connection->prepare("SELECT * FROM categories WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$category = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$category) {
    die("Category not found.");
}

// Update category
if (isset($_POST['updateCategory'])) {
    $room_type = trim($_POST['room_type']);
    if (empty($room_type)) {
        $errorMessage = "Category name is required.";
    } else {
        $image = $category['image']; // old image by default

        if (!empty($_FILES['category_image']['name'])) {
            $uploadDir = __DIR__ . '/../../uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $newImage = $_FILES['category_image']['name'];
            $targetFile = $uploadDir . basename($newImage);

            if (move_uploaded_file($_FILES['category_image']['tmp_name'], $targetFile)) {
                if (file_exists($uploadDir . $category['image'])) {
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
            $updateStmt->bindParam(':room_type', $room_type);
            $updateStmt->bindParam(':image', $image);
            $updateStmt->bindParam(':id', $id);

            if ($updateStmt->execute()) {
                header("Location: add_categories.php");
                exit;
            } else {
                $errorMessage = "Failed to update category.";
            }
        }
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="card">
    <div class="card-body p-3">
        <div class=" d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-4">Edit Category</h4>
            <a href="add_categories.php" class="btn btn-info text-white rounded-0">Back to Categories</a>

        </div>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <label for="room_type" class="col-sm-3 col-form-label">Category Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="room_type" name="room_type" value="<?php echo $category['room_type']; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label for="category_image" class="col-sm-3 col-form-label">Category Image</label>
                <div class="col-sm-9">
                    <input type="file" class="form-control" id="category_image" name="category_image">
                    <small>Current Image:</small><br>
                    <img src="<?php echo '../../uploads/' . $category['image']; ?>" width="100" alt="">
                </div>
            </div>

            <div class="row">
                <label class="col-sm-3 col-form-label"></label>
                <div class="col-sm-9">
                    <button type="submit" name="updateCategory" class="btn btn-primary px-5 rounded-0">Update Category</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php 
include __DIR__ . '/../../includes/footer.php';
ob_end_flush();
?>

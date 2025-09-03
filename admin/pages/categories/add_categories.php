<?php
ob_start(); // start output buffering
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
require_once __DIR__ . '/../../config/db_config.php';

// Database connection
$database = new Database();
$db_connection = $database->db_connection();

// messages
$errorMessage = '';
$successMessage = '';

// Insert category
if (isset($_POST['addCategory'])) {
    $room_type = trim($_POST['room_type']);
    $image = $_FILES['category_image']['name'];

    if (empty($room_type)) {
        $errorMessage = "Category name is required.";
    } elseif (empty($image)) {
        $errorMessage = "Category image is required.";
    } else {
        $targetDir = __DIR__ . "/../../uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $targetFile = $targetDir . basename($image);
        if (move_uploaded_file($_FILES["category_image"]["tmp_name"], $targetFile)) {
            $insert_statement = $db_connection->prepare(
                "INSERT INTO categories (room_type, image) VALUES (:room_type, :image)"
            );
            $insert_statement->bindParam(':room_type', $room_type);
            $insert_statement->bindParam(':image', $image);

            if ($insert_statement->execute()) {
                // redirect to prevent duplicate insert
                header("Location: add_categories.php");
                exit;
            } else {
                $errorMessage = "Failed to add category. Please try again.";
            }
        } else {
            $errorMessage = "Failed to upload image.";
        }
    }
}

// fetch categories list
$statement = $db_connection->prepare("SELECT * FROM categories ORDER BY id DESC");
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
    <div class="card-body p-4">
        <h4 class="mb-4">Insert Category</h4>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <label for="room_type" class="col-sm-3 col-form-label">Category Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="room_type" name="room_type" placeholder="Enter Category Name">
                </div>
            </div>

            <div class="row mb-3">
                <label for="category_image" class="col-sm-3 col-form-label">Category Image</label>
                <div class="col-sm-9">
                    <input type="file" class="form-control" id="category_image" name="category_image">
                </div>
            </div>

            <div class="row">
                <label class="col-sm-3 col-form-label"></label>
                <div class="col-sm-9">
                    <button type="submit" name="addCategory" class="btn btn-success px-5 rounded-0">Add Category</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Category list with Action -->
<div class="card mt-4">
    <div class="card-body">
        <h4 class="mb-4">Category List</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Room Type</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?php echo $cat['id']; ?></td>
                        <td><?php echo $cat['room_type']; ?></td>
                        <td>
                            <img class="rounded-circle" src="<?php echo '../../uploads/' . $cat['image']; ?>" alt="" width="50" height="55px">
                        </td>
                        <td>
                            <a href="edit_category.php?id=<?php echo $cat['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="delete_category.php?id=<?php echo $cat['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete this category?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; 
ob_end_flush(); ?>

<?php
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../includes/header.php';

$database = new Database();
$db = $database->db_connection();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = $_FILES['image'] ?? null;

    // Validation
    if (empty($title)) {
        $errors[] = 'Title is required.';
    }
    if (empty($description)) {
        $errors[] = 'Description is required.';
    }

    // Image upload
    $image_name = '';
    if ($image && $image['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        if (!in_array($image['type'], $allowed_types)) {
            $errors[] = 'Only JPEG, PNG, or GIF images are allowed.';
        } elseif ($image['size'] > $max_size) {
            $errors[] = 'Image size must not exceed 5MB.';
        } else {
            $image_name = time() . '_' . basename($image['name']);
            $upload_path = __DIR__ . '/../uploads/blogs/' . $image_name;
            if (!move_uploaded_file($image['tmp_name'], $upload_path)) {
                $errors[] = 'Failed to upload image.';
            }
        }
    }

    // Insert into DB if no errors
    if (empty($errors)) {
        try {
            $stmt = $db->prepare("INSERT INTO blogs (title, description, image) VALUES (:title, :description, :image)");
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':image' => $image_name
            ]);
            $success = 'Blog added successfully!';
        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<section class="my-10 mx-3 md:mx-16">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-4"><?= $success ? 'Add Another Blog' : 'Add New Blog' ?></h4>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <!-- Blog Title -->
                        <div class="row mb-3">
                            <label for="title" class="col-sm-3 col-form-label">Blog Title</label>
                            <div class="col-sm-9">
                                <div class="position-relative input-icon">
                                    <input type="text" class="form-control" id="title" name="title" 
                                           placeholder="Enter Blog Title"
                                           value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
                                    <span class="position-absolute top-50 translate-middle-y">
                                        <i class="bi bi-pencil-square"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="row mb-3">
                            <label for="description" class="col-sm-3 col-form-label">Description</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="description" name="description" rows="5" 
                                          placeholder="Write blog description..." required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="row mb-3">
                            <label for="image" class="col-sm-3 col-form-label">Upload Image</label>
                            <div class="col-sm-9">
                                <div class="position-relative input-icon">
                                    <input type="file" class="form-control" id="image" name="image"
                                           accept="image/jpeg,image/png,image/gif">
                                    <span class="position-absolute top-50 translate-middle-y">
                                        <i class="bi bi-image"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit/Reset -->
                        <div class="row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4">Add Blog</button>
                                    <a href="blogs_table.php" class="btn btn-light px-4">Back to Blogs List</a>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

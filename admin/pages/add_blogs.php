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

    if (empty($title)) {
        $errors[] = "Title is required.";
    }
    if (empty($description)) {
        $errors[] = "Description is required.";
    }
    if (!$image || $image['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Image is required.";
    }

    if (empty($errors)) {
        // Image upload
        $uploadDir = __DIR__ . '/../uploads/blogs/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageName = time() . '_' . basename($image['name']);
        $targetPath = $uploadDir . $imageName;

        if (move_uploaded_file($image['tmp_name'], $targetPath)) {
            $stmt = $db->prepare("INSERT INTO blogs (title, image, description) VALUES (?, ?, ?)");
            $stmt->execute([$title, $imageName, $description]);
            $success = "Blog added successfully!";
        } else {
            $errors[] = "Failed to upload image.";
        }
    }
}
?>
<script>
  tinymce.init({
    selector: '#description',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    menubar: false
  });
</script>

<div class="container mt-2">
        <div class="card">
            <div class="card-body">
            <h4 class="mb-5">Add New Blog</h4>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger mt-2">
                    <?php foreach ($errors as $error) echo "<p>$error</p>"; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success mt-2"><?= $success; ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="form-horizontal">
                <!-- Title -->
                <div class="row mb-3">
                    <label for="title" class="col-sm-2 col-form-label">Title</label>
                    <div class="col-sm-10">
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                </div>

                <!-- Image -->
                <div class="row mb-3">
                    <label for="image" class="col-sm-2 col-form-label">Thumbnail</label>
                    <div class="col-sm-10">
                        <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                    </div>
                </div>

                <!-- Description -->
                <div class="row mb-3">
                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        <textarea name="description" id="description" rows="10" class="form-control"></textarea>
                    </div>
                </div>

                <!-- Submit -->
                <div class="row mb-3">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-success">Add Blog</button>
                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>


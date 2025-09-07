<?php
require_once __DIR__ . '/../../config/db_config.php';

$database = new Database();
$db_connection = $database->db_connection();

// Fetch categories
$catStmt = $db_connection->prepare("SELECT * FROM categories ORDER BY id DESC");
$catStmt->execute();
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

$errorMessage = '';
$successMessage = '';
$editRoom = null;

// Fetch room data for edit
if (isset($_GET['id'])) {
    $stmt = $db_connection->prepare("SELECT * FROM rooms WHERE id=?");
    $stmt->execute([$_GET['id']]);
    $editRoom = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$editRoom) {
        header("Location: rooms.php");
        exit;
    }

    // Fetch gallery images
    $galleryStmt = $db_connection->prepare("SELECT * FROM room_galleries WHERE room_id=?");
    $galleryStmt->execute([$editRoom['id']]);
    $galleryImages = $galleryStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update Room
if (isset($_POST['save_room'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $capacity = $_POST['capacity'];
    $description = $_POST['description'];
    $amenities = json_encode(array_map('trim', explode(',', $_POST['amenities'][0] ?? '')));

    // Main Image Upload
    $image_url = $_POST['existing_image'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_url = 'uploads/rooms/' . time() . '.' . $ext;
        if (!is_dir(__DIR__ . '/../../uploads/rooms')) {
            mkdir(__DIR__ . '/../../uploads/rooms', 0777, true);
        }
        move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../' . $image_url);
    }

    try {
        $stmt = $db_connection->prepare("UPDATE rooms SET name=?, category_id=?, price=?, capacity=?, description=?, amenities=?, image_url=? WHERE id=?");
        $stmt->execute([$name, $category_id, $price, $capacity, $description, $amenities, $image_url, $id]);

        // Gallery Image Upload
        if (!empty($_FILES['gallery_images']['name'][0])) {
            foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['gallery_images']['error'][$key] == 0) {
                    $ext = pathinfo($_FILES['gallery_images']['name'][$key], PATHINFO_EXTENSION);
                    $filePath = 'uploads/rooms/gallery_' . time() . '_' . $key . '.' . $ext;

                    move_uploaded_file($tmp_name, __DIR__ . '/../../' . $filePath);

                    $stmt = $db_connection->prepare("INSERT INTO room_galleries (room_id, image_url) VALUES (?, ?)");
                    $stmt->execute([$id, $filePath]);
                }
            }
        }

        $successMessage = "Room updated successfully!";
        header("Location: rooms.php");
        exit;
    } catch (PDOException $e) {
        $errorMessage = $e->getMessage();
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<section class="my-10 mx-3 md:mx-16">

    <!-- Edit Room Form -->
    <div class="card mb-8">
        <div class="card-body p-4">
            <h4 class="mb-4 font-bold text-lg">Edit Room</h4>

            <?php if ($errorMessage): ?>
                <div class="alert alert-danger"><?= $errorMessage ?></div>
            <?php endif; ?>
            <?php if ($successMessage): ?>
                <div class="alert alert-success"><?= $successMessage ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $editRoom['id'] ?>">
                <input type="hidden" name="existing_image" value="<?= $editRoom['image_url'] ?>">

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Room Name</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" class="form-control" value="<?= $editRoom['name'] ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Price</label>
                    <div class="col-sm-9">
                        <input type="number" name="price" class="form-control" value="<?= $editRoom['price'] ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Capacity</label>
                    <div class="col-sm-9">
                        <input type="number" name="capacity" class="form-control" value="<?= $editRoom['capacity'] ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Room Type</label>
                    <div class="col-sm-9">
                        <select name="category_id" class="form-control">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($editRoom['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                    <?= $cat['room_type'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Amenities</label>
                    <div class="col-sm-9">
                        <input type="text" name="amenities[]" class="form-control"
                               value="<?= implode(',', json_decode($editRoom['amenities'], true)) ?>">
                    </div>
                </div>

                <!-- Main Image -->
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Main Image</label>
                    <div class="col-sm-9">
                        <input type="file" name="image" class="form-control">
                        <?php if ($editRoom['image_url']): ?>
                            <img src="../../<?= $editRoom['image_url'] ?>" width="120" class="mt-2" alt="Room Image">
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Gallery Images -->
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Gallery Images</label>
                    <div class="col-sm-9">
                        <input type="file" name="gallery_images[]" class="form-control" multiple>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <?php foreach ($galleryImages as $img): ?>
                                <div class="position-relative d-inline-block">
                                    <img src="../../<?= $img['image_url'] ?>" width="100" class="rounded shadow">
                                    <a href="delete_gallery.php?id=<?= $img['id'] ?>&room_id=<?= $editRoom['id'] ?>"
                                       class="btn btn-sm btn-danger position-absolute top-0 end-0">X</a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Description</label>
                    <div class="col-sm-9">
                        <textarea name="description" class="form-control" rows="3"><?= $editRoom['description'] ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" name="save_room" class="btn btn-success px-5">Update Room</button>
                        <a href="rooms.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

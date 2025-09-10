<?php
// admin/pages/add_rooms.php

require_once __DIR__ . '/../config/db_config.php';

$database = new Database();
$db_connection = $database->db_connection();

// Fetch categories
$catStmt = $db_connection->prepare("SELECT * FROM categories ORDER BY id DESC");
$catStmt->execute();
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

$errorMessage = '';
$successMessage = '';
$room = null;

// ---------- Helper: upload dirs (inside admin/uploads) ----------
$ROOM_UPLOAD_DIR_FS = __DIR__ . '/../uploads/rooms/'; // filesystem path => admin/uploads/rooms/
$ROOM_UPLOAD_DIR_URL = 'admin/uploads/rooms/';        // url path (for DB)

// make sure directory exists
if (!is_dir($ROOM_UPLOAD_DIR_FS)) {
    @mkdir($ROOM_UPLOAD_DIR_FS, 0777, true);
}

// ---------- Fetch room data if editing ----------
if (isset($_GET['id'])) {
    $roomId = $_GET['id'];
    $stmt = $db_connection->prepare("SELECT * FROM rooms WHERE id=?");
    $stmt->execute([$roomId]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($room) {
        $room['amenities'] = json_decode($room['amenities'], true) ?? [];
        $room['gallery_images'] = json_decode($room['gallery_images'], true) ?? [];
    }
}

// ---------- Save Room ----------
if (isset($_POST['save_room'])) {
    $id          = $_POST['id'] ?? null;
    $name        = trim($_POST['name'] ?? '');
    $category_id = $_POST['category_id'] ?? null;
    $price       = $_POST['price'] ?? 0;
    $capacity    = $_POST['capacity'] ?? 0;
    $total_rooms = $_POST['total_rooms'] ?? 0;   // ✅ নতুন ফিল্ড
    $description = trim($_POST['description'] ?? '');

    // amenities input name="amenities[]" -> first item ধরেছি
    $amenityRaw  = $_POST['amenities'][0] ?? '';
    $amenities   = json_encode(
        array_values(array_filter(array_map('trim', explode(',', $amenityRaw)), fn($v) => $v !== ''))
    );

    // ---------- Main image ----------
    $image_url = $_POST['existing_image'] ?? '';

    if (isset($_FILES['image']) && is_array($_FILES['image']) && ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if ($ext === '') $ext = 'jpg';
        $filename  = 'room_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $ROOM_UPLOAD_DIR_FS . $filename)) {
            $errorMessage = "Main image upload failed. Check folder permissions.";
        } else {
            $image_url = $ROOM_UPLOAD_DIR_URL . $filename; // DB তে save হবে
        }
    }

    // ---------- Gallery images ----------
    $gallery_urls = $_POST['gallery_images'] ?? [];

    if (!empty($_FILES['gallery_images']['name'][0])) {
        foreach ($_FILES['gallery_images']['name'] as $key => $originalName) {
            if (($_FILES['gallery_images']['error'][$key] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) continue;

            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            if ($ext === '') $ext = 'jpg';
            $gFilename = 'gallery_' . time() . '_' . $key . '_' . bin2hex(random_bytes(2)) . '.' . $ext;

            if (move_uploaded_file($_FILES['gallery_images']['tmp_name'][$key], $ROOM_UPLOAD_DIR_FS . $gFilename)) {
                $gallery_urls[] = $ROOM_UPLOAD_DIR_URL . $gFilename;
            }
        }
    }
    $gallery_json = json_encode(array_values(array_unique($gallery_urls)));

    // ---------- Insert / Update ----------
    try {
        if ($id) {
            $stmt = $db_connection->prepare("
                UPDATE rooms 
                   SET name=?, category_id=?, price=?, capacity=?, total_rooms=?, description=?, amenities=?, image_url=?, gallery_images=?
                 WHERE id=?");
            $stmt->execute([$name, $category_id, $price, $capacity, $total_rooms, $description, $amenities, $image_url, $gallery_json, $id]);
            $successMessage = "Room updated successfully!";
        } else {
            $stmt = $db_connection->prepare("
                INSERT INTO rooms (name, category_id, price, capacity, total_rooms, description, amenities, image_url, gallery_images)
                VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$name, $category_id, $price, $capacity, $total_rooms, $description, $amenities, $image_url, $gallery_json]);
            $successMessage = "Room added successfully!";
        }
    } catch (PDOException $e) {
        $errorMessage = $e->getMessage();
    }

    // Refresh room data after save (for preview)
    if ($id) {
        $stmt = $db_connection->prepare("SELECT * FROM rooms WHERE id=?");
        $stmt->execute([$id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($room) {
            $room['amenities'] = json_decode($room['amenities'], true) ?? [];
            $room['gallery_images'] = json_decode($room['gallery_images'], true) ?? [];
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<section class="my-10 mx-3 md:mx-16">
    <div class="card mb-8">
        <div class="card-body p-4">
            <h4 class="mb-4 font-bold text-lg"><?= $room ? 'Edit Room' : 'Add Room' ?></h4>

            <?php if ($errorMessage): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
            <?php endif; ?>
            <?php if ($successMessage): ?>
                <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($room['id'] ?? '') ?>">
                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($room['image_url'] ?? '') ?>">

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Room Name</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" class="form-control" placeholder="Enter Room Name" value="<?= htmlspecialchars($room['name'] ?? '') ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Price</label>
                    <div class="col-sm-9">
                        <input type="number" name="price" class="form-control" placeholder="Enter Price" value="<?= htmlspecialchars($room['price'] ?? '') ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Capacity</label>
                    <div class="col-sm-9">
                        <input type="number" name="capacity" class="form-control" placeholder="Enter Capacity" value="<?= htmlspecialchars($room['capacity'] ?? '') ?>">
                    </div>
                </div>

                <!-- ✅ New field total_rooms -->
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Total Rooms</label>
                    <div class="col-sm-9">
                        <input type="number" name="total_rooms" class="form-control" placeholder="Enter Total Rooms" value="<?= htmlspecialchars($room['total_rooms'] ?? '') ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Room Type</label>
                    <div class="col-sm-9">
                        <select name="category_id" class="form-control">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= (($room['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['room_type']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Amenities</label>
                    <div class="col-sm-9">
                        <input type="text" name="amenities[]" class="form-control" placeholder="Comma separated amenities" value="<?= htmlspecialchars(implode(',', $room['amenities'] ?? [])) ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Main Image</label>
                    <div class="col-sm-9">
                        <?php if (!empty($room['image_url'])): ?>
                            <img src="../../<?= htmlspecialchars($room['image_url']) ?>" alt="Room Image" class="mb-2" style="width:150px;">
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Gallery Images</label>
                    <div class="col-sm-9" id="gallery-wrapper">
                        <?php if (!empty($room['gallery_images'])): ?>
                            <?php foreach ($room['gallery_images'] as $img): ?>
                                <div class="mb-2">
                                    <img src="../../<?= htmlspecialchars($img) ?>" style="width:100px;">
                                    <input type="hidden" name="gallery_images[]" value="<?= htmlspecialchars($img) ?>">
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <input type="file" name="gallery_images[]" class="form-control mb-2" accept="image/*">
                    </div>
                    <div class="col-sm-9 offset-sm-3 mt-2">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="addMore()">➕ Add More</button>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Description</label>
                    <div class="col-sm-9">
                        <textarea name="description" class="form-control" rows="3" placeholder="Enter description"><?= htmlspecialchars($room['description'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" name="save_room" class="btn btn-success px-5"><?= $room ? 'Update Room' : 'Save Room' ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
function addMore() {
    const wrapper = document.getElementById("gallery-wrapper");
    const input = document.createElement("input");
    input.type = "file";
    input.name = "gallery_images[]";
    input.accept = "image/*";
    input.classList.add("form-control", "mb-2");
    wrapper.appendChild(input);
}
</script>



<?php
// admin/pages/edit_room.php
require_once __DIR__ . '/../config/db_config.php';

$database = new Database();
$db_connection = $database->db_connection();

// Fetch categories
$catStmt = $db_connection->prepare("SELECT * FROM categories ORDER BY id DESC");
$catStmt->execute();
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch room data
if (!isset($_GET['id'])) die("Room ID required");
$roomId = $_GET['id'];

$stmt = $db_connection->prepare("SELECT * FROM rooms WHERE id=?");
$stmt->execute([$roomId]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) die("Room not found");

$room['amenities'] = json_decode($room['amenities'], true) ?? [];
$room['gallery_images'] = json_decode($room['gallery_images'], true) ?? [];

// Upload directories
$ROOM_UPLOAD_DIR_FS = __DIR__ . '/../uploads/rooms/'; // filesystem path
$ROOM_UPLOAD_DIR_URL = 'admin/uploads/rooms/';        // URL path

if (!is_dir($ROOM_UPLOAD_DIR_FS)) @mkdir($ROOM_UPLOAD_DIR_FS, 0777, true);

$errorMessage = '';
$successMessage = '';

if (isset($_POST['save_room'])) {
    $name        = trim($_POST['name'] ?? '');
    $category_id = $_POST['category_id'] ?? null;
    $price       = $_POST['price'] ?? 0;
    $capacity    = $_POST['capacity'] ?? 0;
    $total_rooms = $_POST['total_rooms'] ?? 0;
    $description = trim($_POST['description'] ?? '');

    // amenities
    $amenityRaw  = $_POST['amenities'][0] ?? '';
    $amenities   = json_encode(array_values(array_filter(array_map('trim', explode(',', $amenityRaw)), fn($v) => $v !== '')));

    // main image
    $image_url = $_POST['existing_image'] ?? '';
    if (isset($_FILES['image']) && ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if ($ext === '') $ext = 'jpg';
        $filename = 'room_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $ROOM_UPLOAD_DIR_FS . $filename)) {
            $errorMessage = "Main image upload failed. Check folder permissions.";
        } else {
            // Delete old main image
            if (!empty($room['image_url']) && file_exists(__DIR__ . '/../' . $room['image_url'])) {
                @unlink(__DIR__ . '/../' . $room['image_url']);
            }
            $image_url = $ROOM_UPLOAD_DIR_URL . $filename;
        }
    }

    // gallery images
    $existing_gallery = $_POST['gallery_images'] ?? [];
    $gallery_urls = [];

    // Delete removed images from server
    foreach ($room['gallery_images'] as $oldImg) {
        if (!in_array($oldImg, $existing_gallery)) {
            $oldFile = $ROOM_UPLOAD_DIR_FS . basename($oldImg);
            if (file_exists($oldFile)) {
                @unlink($oldFile);
            }
        }
    }

    // Add existing images that were not removed
    foreach ($existing_gallery as $img) {
        $gallery_urls[] = $img;
    }

    // Add newly uploaded gallery images
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

    // Update DB
    try {
        $stmt = $db_connection->prepare("
            UPDATE rooms 
               SET name=?, category_id=?, price=?, capacity=?, total_rooms=?, description=?, amenities=?, image_url=?, gallery_images=?
             WHERE id=?");
        $stmt->execute([$name, $category_id, $price, $capacity, $total_rooms, $description, $amenities, $image_url, $gallery_json, $roomId]);
        $successMessage = "Room updated successfully!";

        // Refresh room data
        $stmt = $db_connection->prepare("SELECT * FROM rooms WHERE id=?");
        $stmt->execute([$roomId]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        $room['amenities'] = json_decode($room['amenities'], true) ?? [];
        $room['gallery_images'] = json_decode($room['gallery_images'], true) ?? [];
    } catch (PDOException $e) {
        $errorMessage = $e->getMessage();
    }
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<section class="my-10 mx-3 md:mx-16">
    <div class="card mb-8">
        <div class="card-body p-4">
            <h4 class="mb-4 font-bold text-lg">Edit Room</h4>

            <?php if ($errorMessage): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
            <?php endif; ?>
            <?php if ($successMessage): ?>
                <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($room['image_url'] ?? '') ?>">

                <!-- Room Name -->
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Room Name</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($room['name'] ?? '') ?>">
                    </div>
                </div>

                <!-- Price -->
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Price</label>
                    <div class="col-sm-9">
                        <input type="number" name="price" class="form-control" value="<?= htmlspecialchars($room['price'] ?? '') ?>">
                    </div>
                </div>

                <!-- Capacity -->
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Capacity</label>
                    <div class="col-sm-9">
                        <input type="number" name="capacity" class="form-control" value="<?= htmlspecialchars($room['capacity'] ?? '') ?>">
                    </div>
                </div>

                <!-- Total Rooms -->
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Total Rooms</label>
                    <div class="col-sm-9">
                        <input type="number" name="total_rooms" class="form-control" value="<?= htmlspecialchars($room['total_rooms'] ?? '') ?>">
                    </div>
                </div>

                <!-- Category -->
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Room Type</label>
                    <div class="col-sm-9">
                        <select name="category_id" class="form-control">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($room['category_id']==$cat['id'])?'selected':'' ?>>
                                    <?= htmlspecialchars($cat['room_type']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Amenities -->
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Amenities</label>
                    <div class="col-sm-9">
                        <input type="text" name="amenities[]" class="form-control" value="<?= htmlspecialchars(implode(',', $room['amenities'] ?? [])) ?>">
                    </div>
                </div>

                <!-- Main Image -->
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Main Image</label>
                    <div class="col-sm-9">
                        <?php if($room['image_url']): ?>
                            <img src="../<?= htmlspecialchars($room['image_url']) ?>" style="width:150px; margin-bottom:5px;">
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>

                <!-- Gallery Images -->
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Gallery Images</label>
                    <div class="col-sm-9" id="gallery-wrapper" style="display:flex; flex-wrap:wrap; gap:10px;">
                        <?php foreach ($room['gallery_images'] as $key => $img): ?>
                            <div class="gallery-item position-relative" style="width:100px; height:100px;">
                                <img src="../<?= htmlspecialchars($img) ?>" style="width:100%; height:100%; object-fit:cover; border:1px solid #ccc; border-radius:4px;">
                                <input type="hidden" name="gallery_images[]" value="<?= htmlspecialchars($img) ?>">
                                <button type="button" class="remove-gallery btn btn-sm btn-danger" style="position:absolute; top:2px; right:2px; padding:0 5px;">✕</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-sm-9 offset-sm-3 mt-2">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="addMore()">➕ Add More</button>
                    </div>
                </div>

                <!-- Description -->
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Description</label>
                    <div class="col-sm-9">
                        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($room['description'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- Submit -->
                <div class="row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" name="save_room" class="btn btn-success px-5">Update Room</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
function addMore() {
    const wrapper = document.getElementById("gallery-wrapper");
    const div = document.createElement("div");
    div.style.width = "100px";
    div.style.height = "100px";

    const input = document.createElement("input");
    input.type = "file";
    input.name = "gallery_images[]";
    input.accept = "image/*";
    input.style.width = "100%";
    input.style.height = "100%";
    input.style.padding = "0";

    div.appendChild(input);
    wrapper.appendChild(div);
}

// Remove gallery image dynamically
document.addEventListener("click", function(e){
    if(e.target && e.target.classList.contains("remove-gallery")){
        const galleryItem = e.target.closest(".gallery-item");
        galleryItem.remove();
    }
});
</script>

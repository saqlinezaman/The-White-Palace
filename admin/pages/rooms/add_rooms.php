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
$room = null;

// Fetch room data if editing
if(isset($_GET['id'])){
    $roomId = $_GET['id'];
    $stmt = $db_connection->prepare("SELECT * FROM rooms WHERE id=?");
    $stmt->execute([$roomId]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
    if($room){
        $room['amenities'] = json_decode($room['amenities'], true) ?? [];
        $room['gallery_images'] = json_decode($room['gallery_images'], true) ?? [];
    }
}

// Save Room
if(isset($_POST['save_room'])){
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $capacity = $_POST['capacity'];
    $description = $_POST['description'];
    $amenities = json_encode(array_map('trim', explode(',', $_POST['amenities'][0] ?? '')));

    // Main image
    $image_url = $_POST['existing_image'] ?? '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_url = 'uploads/rooms/' . time() . '.' . $ext;
        if(!is_dir(__DIR__ . '/../../uploads/rooms')){
            mkdir(__DIR__ . '/../../uploads/rooms', 0777, true);
        }
        move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../' . $image_url);
    }

    // Gallery images
    $gallery_urls = $_POST['gallery_images'] ?? [];
    if(!empty($_FILES['gallery_images']['name'][0])){
        foreach($_FILES['gallery_images']['name'] as $key => $filename){
            if($_FILES['gallery_images']['error'][$key] == 0){
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $file_path = 'uploads/rooms/gallery_' . time() . '_' . $key . '.' . $ext;
                move_uploaded_file($_FILES['gallery_images']['tmp_name'][$key], __DIR__ . '/../../' . $file_path);
                $gallery_urls[] = $file_path;
            }
        }
    }
    $gallery_json = json_encode($gallery_urls);

    try {
        if($id){ // Update
            $stmt = $db_connection->prepare("UPDATE rooms SET name=?, category_id=?, price=?, capacity=?, description=?, amenities=?, image_url=?, gallery_images=? WHERE id=?");
            $stmt->execute([$name,$category_id,$price,$capacity,$description,$amenities,$image_url,$gallery_json,$id]);
            $successMessage = "Room updated successfully!";
        } else { // Insert
            $stmt = $db_connection->prepare("INSERT INTO rooms (name, category_id, price, capacity, description, amenities, image_url, gallery_images) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->execute([$name,$category_id,$price,$capacity,$description,$amenities,$image_url,$gallery_json]);
            $successMessage = "Room added successfully!";
        }
    } catch(PDOException $e){
        $errorMessage = $e->getMessage();
    }

    // Refresh room data after save
    if($id){
        $stmt = $db_connection->prepare("SELECT * FROM rooms WHERE id=?");
        $stmt->execute([$id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        $room['amenities'] = json_decode($room['amenities'], true) ?? [];
        $room['gallery_images'] = json_decode($room['gallery_images'], true) ?? [];
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<section class="my-10 mx-3 md:mx-16">

    <div class="card mb-8">
        <div class="card-body p-4">
            <h4 class="mb-4 font-bold text-lg"><?= $room ? 'Edit Room' : 'Add Room' ?></h4>

            <?php if ($errorMessage): ?>
                <div class="alert alert-danger"><?= $errorMessage ?></div>
            <?php endif; ?>
            <?php if ($successMessage): ?>
                <div class="alert alert-success"><?= $successMessage ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $room['id'] ?? '' ?>">
                <input type="hidden" name="existing_image" value="<?= $room['image_url'] ?? '' ?>">

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Room Name</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" class="form-control" placeholder="Enter Room Name" value="<?= $room['name'] ?? '' ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Price</label>
                    <div class="col-sm-9">
                        <input type="number" name="price" class="form-control" placeholder="Enter Price" value="<?= $room['price'] ?? '' ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Capacity</label>
                    <div class="col-sm-9">
                        <input type="number" name="capacity" class="form-control" placeholder="Enter Capacity" value="<?= $room['capacity'] ?? '' ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Room Type</label>
                    <div class="col-sm-9">
                        <select name="category_id" class="form-control">
                            <option value="">Select Category</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($room['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= $cat['room_type'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Amenities</label>
                    <div class="col-sm-9">
                        <input type="text" name="amenities[]" class="form-control" placeholder="Comma separated amenities" value="<?= implode(',', $room['amenities'] ?? []) ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Main Image</label>
                    <div class="col-sm-9">
                        <?php if(!empty($room['image_url'])): ?>
                            <img src="../../<?= $room['image_url'] ?>" alt="Room Image" class="mb-2" style="width:150px;">
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Gallery Images</label>
                    <div class="col-sm-9" id="gallery-wrapper">
                        <?php if(!empty($room['gallery_images'])): ?>
                            <?php foreach($room['gallery_images'] as $img): ?>
                                <div class="mb-2">
                                    <img src="../../<?= $img ?>" style="width:100px;">
                                    <input type="hidden" name="gallery_images[]" value="<?= $img ?>">
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
                        <textarea name="description" class="form-control" rows="3" placeholder="Enter description"><?= $room['description'] ?? '' ?></textarea>
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

<?php include __DIR__ . '/../../includes/footer.php'; ?>

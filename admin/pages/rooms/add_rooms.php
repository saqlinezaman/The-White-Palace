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

// Save Room
if(isset($_POST['save_room'])){
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $capacity = $_POST['capacity'];
    $description = $_POST['description'];
    $amenities = json_encode(array_map('trim', explode(',', $_POST['amenities'][0] ?? '')));

    // Image Upload
    $image_url = '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_url = 'uploads/rooms/' . time() . '.' . $ext;
        if(!is_dir(__DIR__ . '/../../uploads/rooms')){
            mkdir(__DIR__ . '/../../uploads/rooms', 0777, true);
        }
        move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../' . $image_url);
    } elseif(isset($_POST['existing_image'])){
        $image_url = $_POST['existing_image'];
    }

    try {
        if($id){ // Update
            $stmt = $db_connection->prepare("UPDATE rooms SET name=?, category_id=?, price=?, capacity=?, description=?, amenities=?, image_url=? WHERE id=?");
            $stmt->execute([$name,$category_id,$price,$capacity,$description,$amenities,$image_url,$id]);
            $successMessage = "Room updated successfully!";
        } else { // Insert
            $stmt = $db_connection->prepare("INSERT INTO rooms (name, category_id, price, capacity, description, amenities, image_url) VALUES (?,?,?,?,?,?,?)");
            $stmt->execute([$name,$category_id,$price,$capacity,$description,$amenities,$image_url]);
            $successMessage = "Room added successfully!";
        }
    } catch(PDOException $e){
        $errorMessage = $e->getMessage();
    }
}

// Fetch rooms
$roomsStmt = $db_connection->prepare("
    SELECT r.*, c.room_type as category_name 
    FROM rooms r 
    JOIN categories c ON r.category_id = c.id
    ORDER BY r.id DESC
");
$roomsStmt->execute();
$rooms = $roomsStmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';

?>

<section class="my-10 mx-3 md:mx-16">

    <!-- Add Room Form -->
    <div class="card mb-8">
        <div class="card-body p-4">
            <h4 class="mb-4 font-bold text-lg">Insert Room</h4>

            <?php if ($errorMessage): ?>
                <div class="alert alert-danger"><?= $errorMessage ?></div>
            <?php endif; ?>
            <?php if ($successMessage): ?>
                <div class="alert alert-success"><?= $successMessage ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="">
                <input type="hidden" name="existing_image" value="">

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Room Name</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" class="form-control" placeholder="Enter Room Name">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Price</label>
                    <div class="col-sm-9">
                        <input type="number" name="price" class="form-control" placeholder="Enter Price">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Capacity</label>
                    <div class="col-sm-9">
                        <input type="number" name="capacity" class="form-control" placeholder="Enter Capacity">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Room Type</label>
                    <div class="col-sm-9">
                        <select name="category_id" class="form-control">
                            <option value="">Select Category</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= $cat['room_type'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Amenities</label>
                    <div class="col-sm-9">
                        <input type="text" name="amenities[]" class="form-control" placeholder="Comma separated amenities">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Image</label>
                    <div class="col-sm-9">
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Description</label>
                    <div class="col-sm-9">
                        <textarea name="description" class="form-control" rows="3" placeholder="Enter description"></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" name="save_room" class="btn btn-success px-5">Save Room</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php include __DIR__ . '/../../includes/footer.php';?>
<?php
require_once __DIR__ . '/../config/db_config.php';

$database = new Database();
$db = $database->db_connection();

// Message
$success = '';
$error   = '';

// Service ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Service ID!");
}
$id = intval($_GET['id']);

// Fetch Service
$stmt = $db->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    die("Service not found!");
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $icon        = trim($_POST['icon']);
    $color       = trim($_POST['color']);
    $price       = trim($_POST['price']);
    $features    = isset($_POST['features']) ? array_filter(array_map('trim', $_POST['features'])) : [];

    if ($title && $description && $icon && $color && $price) {
        try {
            $stmt = $db->prepare("UPDATE services SET title=?, description=?, icon=?, color=?, price=?, features=? WHERE id=?");
            $stmt->execute([$title, $description, $icon, $color, $price, json_encode($features), $id]);
            $success = "Service updated successfully!";
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "All fields are required!";
    }
}

$features = json_decode($service['features'], true) ?? [];

require_once __DIR__ . '/../includes/header.php';
?>
<div class="container">
   <h4 class="mb-4">Edit Service</h4>
   <div class="card p-4">
   
       <?php if ($success): ?>
           <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
       <?php elseif ($error): ?>
           <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
       <?php endif; ?>
   
       <form action="" method="POST" enctype="multipart/form-data">
           <div class="row mb-3">
               <label for="title" class="col-sm-3 col-form-label">Service Title</label>
               <div class="col-sm-9">
                   <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($service['title']) ?>" required>
               </div>
           </div>
   
           <div class="row mb-3">
               <label for="description" class="col-sm-3 col-form-label">Description</label>
               <div class="col-sm-9">
                   <textarea class="form-control" id="description" name="description" rows="3" required><?= htmlspecialchars($service['description']) ?></textarea>
               </div>
           </div>
   
           <div class="row mb-3">
               <label for="price" class="col-sm-3 col-form-label">Price</label>
               <div class="col-sm-9">
                   <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= htmlspecialchars($service['price']) ?>" required>
               </div>
           </div>
   
           <!-- Icon Picker -->
           <div class="row mb-3">
               <label class="col-sm-3 col-form-label">Icon</label>
               <div class="col-sm-9">
                   <input type="hidden" name="icon" id="iconInput" value="<?= htmlspecialchars($service['icon']) ?>" required>
                   <div class="icon-picker d-flex flex-wrap gap-2 p-2 border rounded" id="iconPicker">
                       <i class="fas fa-hotel p-2 border rounded" data-icon="hotel"></i>
                       <i class="fas fa-bed p-2 border rounded" data-icon="bed"></i>
                       <i class="fas fa-concierge-bell p-2 border rounded" data-icon="concierge-bell"></i>
                       <i class="fas fa-utensils p-2 border rounded" data-icon="utensils"></i>
                       <i class="fas fa-wine-glass-alt p-2 border rounded" data-icon="wine-glass-alt"></i>
                       <i class="fas fa-glass-martini-alt p-2 border rounded" data-icon="glass-martini-alt"></i>
                       <i class="fas fa-swimming-pool p-2 border rounded" data-icon="swimming-pool"></i>
                       <i class="fas fa-spa p-2 border rounded" data-icon="spa"></i>
                       <i class="fas fa-dumbbell p-2 border rounded" data-icon="dumbbell"></i>
                       <i class="fas fa-bath p-2 border rounded" data-icon="bath"></i>
                       <i class="fas fa-shower p-2 border rounded" data-icon="shower"></i>
                       <i class="fas fa-car p-2 border rounded" data-icon="car"></i>
                       <i class="fas fa-shuttle-van p-2 border rounded" data-icon="shuttle-van"></i>
                       <i class="fas fa-bus p-2 border rounded" data-icon="bus"></i>
                       <i class="fas fa-map-marked-alt p-2 border rounded" data-icon="map-marked-alt"></i>
                       <i class="fas fa-plane p-2 border rounded" data-icon="plane"></i>
                       <i class="fas fa-globe p-2 border rounded" data-icon="globe"></i>
                       <i class="fas fa-gift p-2 border rounded" data-icon="gift"></i>
                       <i class="fas fa-star p-2 border rounded" data-icon="star"></i>
                       <i class="fas fa-cocktail p-2 border rounded" data-icon="cocktail"></i>
                       <i class="fas fa-mug-hot p-2 border rounded" data-icon="mug-hot"></i>
                   </div>
               </div>
           </div>
   
           <!-- Color -->
           <div class="row mb-3">
               <label class="col-sm-3 col-form-label">Color</label>
               <div class="col-sm-9">
                   <input type="color" class="form-control form-control-color" name="color" value="<?= htmlspecialchars($service['color']) ?>" required>
               </div>
           </div>
   
           <!-- Features -->
           <div id="featureWrapper">
               <label class="col-sm-3 col-form-label d-block mb-2">Features</label>
               <div class="col-sm-12">
                   <?php if (!empty($features)): ?>
                       <?php foreach ($features as $f): ?>
                           <div class="input-group mb-2">
                               <input type="text" name="features[]" class="form-control" value="<?= htmlspecialchars($f) ?>">
                               <button class="btn btn-outline-danger removeFeature" type="button">-</button>
                           </div>
                       <?php endforeach; ?>
                   <?php else: ?>
                       <div class="input-group mb-2">
                           <input type="text" name="features[]" class="form-control" placeholder="Enter a feature">
                           <button class="btn btn-outline-secondary addFeature" type="button">+</button>
                       </div>
                   <?php endif; ?>
               </div>
           </div>
   
           <button type="button" class="btn btn-sm btn-outline-primary mb-3 addFeature">+ Add Feature</button>
   
           <!-- Submit -->
           <div class="row">
               <div class="col-sm-3"></div>
               <div class="col-sm-9">
                   <button type="submit" class="btn btn-warning px-5 rounded-0">Update Feature</button>
               </div>
           </div>
       </form>
   </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Icon Picker
    const selectedIcon = "<?= htmlspecialchars($service['icon']) ?>";
    document.querySelectorAll("#iconPicker i").forEach(function(icon) {
        if(icon.dataset.icon === selectedIcon){
            icon.classList.add("icon-selected");
        }
        icon.addEventListener("click", function() {
            document.querySelectorAll("#iconPicker i").forEach(i => i.classList.remove("icon-selected"));
            this.classList.add("icon-selected");
            document.getElementById("iconInput").value = this.dataset.icon;
        });
    });

    // Add / Remove Feature
    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("addFeature")) {
            let wrapper = document.getElementById("featureWrapper");
            let div = document.createElement("div");
            div.classList.add("input-group", "mb-2");
            div.innerHTML = `
                <input type="text" name="features[]" class="form-control" placeholder="Enter a feature">
                <button class="btn btn-outline-danger removeFeature" type="button">-</button>
            `;
            wrapper.appendChild(div);
        }
        if (e.target.classList.contains("removeFeature")) {
            e.target.parentElement.remove();
        }
    });
});
</script>

<style>
.icon-picker i {
    font-size: 24px;
    cursor: pointer;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid transparent;
    transition: all 0.2s ease;
}
.icon-picker i:hover { background: #f8f9fa; transform: scale(1.1);}
.icon-selected { border: 2px solid #0d6efd !important; background: #e7f1ff !important; color: #0d6efd; }
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

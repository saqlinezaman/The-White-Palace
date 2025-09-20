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
            // redirect করলে চাইলে:
            // header("Location: services_list.php?updated=1");
            // exit;
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

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-warning text-dark text-center">
          <h4 class="mb-0">Edit Hotel Feature</h4>
        </div>
        <div class="card-body p-4">

          <!-- Success / Error -->
          <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
          <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
          <?php endif; ?>

          <!-- Edit Form -->
          <form method="POST" action="">
            <!-- Title -->
            <div class="mb-3">
              <label class="form-label">Service Title</label>
              <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($service['title']) ?>" required>
            </div>

            <!-- Description -->
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea name="description" rows="3" class="form-control" required><?= htmlspecialchars($service['description']) ?></textarea>
            </div>

            <!-- Price -->
            <div class="mb-3">
              <label class="form-label">Price</label>
              <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($service['price']) ?>" required>
            </div>

            <!-- Icon Picker -->
            <div class="mb-3">
              <label class="form-label fw-bold">Choose Icon</label>
              <input type="hidden" name="icon" id="iconInput" value="<?= htmlspecialchars($service['icon']) ?>" required>
              <div class="icon-picker" id="iconPicker">
                <!-- Same icons -->
                <i class="fas fa-hotel" data-icon="hotel"></i>
                <i class="fas fa-bed" data-icon="bed"></i>
                <i class="fas fa-concierge-bell" data-icon="concierge-bell"></i>
                <i class="fas fa-utensils" data-icon="utensils"></i>
                <i class="fas fa-wine-glass-alt" data-icon="wine-glass-alt"></i>
                <i class="fas fa-glass-martini-alt" data-icon="glass-martini-alt"></i>
                <i class="fas fa-swimming-pool" data-icon="swimming-pool"></i>
                <i class="fas fa-spa" data-icon="spa"></i>
                <i class="fas fa-dumbbell" data-icon="dumbbell"></i>
                <i class="fas fa-bath" data-icon="bath"></i>
                <i class="fas fa-shower" data-icon="shower"></i>
                <i class="fas fa-car" data-icon="car"></i>
                <i class="fas fa-shuttle-van" data-icon="shuttle-van"></i>
                <i class="fas fa-bus" data-icon="bus"></i>
                <i class="fas fa-map-marked-alt" data-icon="map-marked-alt"></i>
                <i class="fas fa-plane" data-icon="plane"></i>
                <i class="fas fa-globe" data-icon="globe"></i>
                <i class="fas fa-gift" data-icon="gift"></i>
                <i class="fas fa-star" data-icon="star"></i>
                <i class="fas fa-cocktail" data-icon="cocktail"></i>
                <i class="fas fa-mug-hot" data-icon="mug-hot"></i>
              </div>
              <small class="text-muted">Click on an icon to select</small>
            </div>

            <!-- Color Picker -->
            <div class="mb-3">
              <label class="form-label fw-bold">Pick Color</label>
              <input type="color" name="color" class="form-control form-control-color" value="<?= htmlspecialchars($service['color']) ?>" required>
            </div>

            <!-- Features -->
            <div id="featureWrapper">
              <label class="form-label">Features</label>
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

            <!-- Add Feature Button -->
            <button type="button" class="btn btn-sm btn-outline-primary mb-3 addFeature">+ Add Feature</button>

            <!-- Submit -->
            <div class="d-grid">
              <button type="submit" class="btn btn-warning btn-lg">Update Feature</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Custom CSS -->
<style>
  .icon-picker {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 12px;
    max-height: 260px;
    overflow-y: auto;
    border: 1px solid #ddd;
    padding: 12px;
    border-radius: 8px;
    background: #fff;
  }
  .icon-picker i {
    font-size: 26px;
    cursor: pointer;
    text-align: center;
    padding: 14px;
    border-radius: 8px;
    transition: all 0.2s ease-in-out;
    border: 1px solid transparent;
  }
  .icon-picker i:hover {
    background: #f8f9fa;
    transform: scale(1.1);
  }
  .icon-selected {
    border: 2px solid #0d6efd !important;
    background: #e7f1ff !important;
    color: #0d6efd;
  }
</style>

<!-- Script -->
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

<!-- FontAwesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

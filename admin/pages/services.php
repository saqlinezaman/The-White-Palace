<?php
require_once __DIR__ . '/../config/db_config.php';

$database = new Database();
$db = $database->db_connection();

// Delete Handle
if (isset($_GET['delete_id'])) {
  $delete_id = intval($_GET['delete_id']);
  if ($delete_id > 0) {
    try {
      $stmt = $db->prepare("DELETE FROM services WHERE id = ?");
      $stmt->execute([$delete_id]);
      $success = "Service deleted successfully!";
    } catch (Exception $e) {
      $error = "Error deleting: " . $e->getMessage();
    }
  }
}

// Fetch services
$stmt = $db->prepare("SELECT * FROM services ORDER BY id DESC");
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <h2 class="mb-2">Services</h2>
      <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
              <thead class="">
                <tr>
                  <th>#</th>
                  <th>Icon</th>
                  <th>Title</th>
                  <th>Description</th>
                  <th>Price</th>
                  <th>Color</th>
                  <th>Features</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($services): ?>
                  <?php foreach ($services as $index => $service): ?>
                    <tr>
                      <td><?= $index + 1 ?></td>
                      <td><i class="fas fa-<?= htmlspecialchars($service['icon']) ?> fa-2x"></i></td>
                      <td><?= htmlspecialchars($service['title']) ?></td>
                      <td><?= htmlspecialchars($service['description']) ?></td>
                      <td>$<?= number_format($service['price'], 2) ?></td>
                      <td>
                        <span class="badge" style="background: <?= htmlspecialchars($service['color']) ?>;">
                          <?= htmlspecialchars($service['color']) ?>
                        </span>
                      </td>
                      <td>
                        <?php
                        $features = json_decode($service['features'], true);
                        if (!empty($features)) {
                          echo "<ul class='mb-0'>";
                          foreach ($features as $f) {
                            echo "<li>" . htmlspecialchars($f) . "</li>";
                          }
                          echo "</ul>";
                        }
                        ?>
                      </td>
                      <td>
                        <a href="index.php?page=service_edit&id=<?= $service['id'] ?>" class="btn btn-sm btn-warning">
                          <i class="fas fa-edit"></i>
                        </a>
                        <a href="index.php?page=services&delete_id=<?= $service['id'] ?>" class="btn btn-sm btn-danger"
                          onclick="return confirm('Are you sure to delete this service?')">
                          <i class="fas fa-trash"></i>
                        </a>

                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="8" class="text-center text-muted">No services found!</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          <div class="text-end">
            <a href="index.php?page=add_services" class="btn btn-primary">
              <i class="fas fa-plus"></i> Add New Service
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- FontAwesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
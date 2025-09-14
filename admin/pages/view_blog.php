<?php
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../includes/header.php';

$database = new Database();
$db = $database->db_connection();

// Get blog ID
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die("<div class='alert alert-danger'>Invalid Blog ID.</div>");
}

// Fetch blog
$stmt = $db->prepare("SELECT * FROM blogs WHERE id = :id");
$stmt->execute([':id' => $id]);
$blog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$blog) {
    die("<div class='alert alert-danger'>Blog not found.</div>");
}
?>

<div class="container my-5">
    <div class="card shadow-lg">
        <div class="card-header  d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><?= htmlspecialchars($blog['title']); ?></h4>
            <a href="index.php?page=blogs" class="btn btn-danger">Back</a>
        </div>
        <div class="card-body">
            <!-- Blog Image -->
            <?php if (!empty($blog['image'])): ?>
                <div class="text-center mb-4">
                    <img src="../admin/uploads/blogs/<?= htmlspecialchars($blog['image']); ?>" 
                         class="img-fluid rounded" style="max-height:400px;">
                </div>
            <?php endif; ?>

            <!-- Blog Description -->
            <div class="blog-description">
                <?= $blog['description']; ?> <!-- HTML সহ রেন্ডার হবে -->
            </div>
        </div>
    </div>
</div>
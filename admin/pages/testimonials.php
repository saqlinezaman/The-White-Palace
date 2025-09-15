<?php
// session থাকলে start করো
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db_config.php';
$database = new Database();
$db = $database->db_connection();

// Delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id > 0) {
        $delStmt = $db->prepare("DELETE FROM testimonials WHERE id=?");
        $delStmt->execute([$id]);
    }
    // same page এ redirect করে success message দেখাও
   echo '<script>
        window.location.href = "index.php?page=testimonials";
      </script>';
    exit;
}

// সব testimonial আনা (username সহ)
$stmt = $db->prepare("
    SELECT t.id, t.review_text, t.created_at, u.username 
    FROM testimonials t 
    JOIN users u ON t.user_id = u.id 
    ORDER BY t.created_at DESC
");
$stmt->execute();
$testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__.'/../includes/header.php';

?>

<div class="container my-2">
    <h2 class="mb-4">Testimonials</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Testimonial deleted successfully!</div>
    <?php endif; ?>

    <?php if (empty($testimonials)): ?>
        <div class="alert alert-info">No testimonials found.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Reviewer Name</th>
                        <th>Review</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1; foreach ($testimonials as $t): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($t['username']); ?></td>
                        <td><?= nl2br(htmlspecialchars($t['review_text'])); ?></td>
                        <td><?= date("d M Y h:i A", strtotime($t['created_at'])); ?></td>
                        <td>
                            <a href="index.php?page=testimonials&delete=<?= $t['id']; ?>" 
                               onclick="return confirm('Are you sure you want to delete this review?');"
                               class="btn btn-sm btn-danger">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

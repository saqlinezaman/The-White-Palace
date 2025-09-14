<?php
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../includes/header.php';

$database = new Database();
$db = $database->db_connection();

$success = '';
$error = '';

// Handle delete via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);

    try {
        $db->beginTransaction();

        // Get blog image
        $stmt = $db->prepare("SELECT image FROM blogs WHERE id = :id");
        $stmt->execute([':id' => $delete_id]);
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($blog) {
            // Delete image
            $image_path = __DIR__ . '/../uploads/blogs/' . $blog['image'];
            if (!empty($blog['image']) && file_exists($image_path)) {
                unlink($image_path);
            }

            // Delete record
            $stmt = $db->prepare("DELETE FROM blogs WHERE id = :id");
            $stmt->execute([':id' => $delete_id]);

            $db->commit();
            $success = "Blog deleted successfully!";
        } else {
            $db->rollBack();
            $error = "Blog not found.";
        }
    } catch (Exception $e) {
        $db->rollBack();
        $error = "Error deleting blog: " . $e->getMessage();
    }
}

// Fetch all blogs
$statement = $db->prepare("SELECT * FROM blogs ORDER BY id DESC");
$statement->execute();
$blogs = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h4 class="mb-5">Blogs List</h4>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <table class="table table-striped table-bordered align-middle" style="table-layout: fixed; width: 100%;">
        <thead class="">
            <tr>
                <th style="width:5%; text-align:center; vertical-align:middle;">ID</th>
                <th style="width:45%; text-align:left; vertical-align:middle;">Title</th>
                <th style="width:25%; text-align:center; vertical-align:middle;">Image</th>
                <th style="width:25%; text-align:center; vertical-align:middle;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($blogs)): ?>
                <?php foreach ($blogs as $blog): ?>
                    <tr>
                        <td style="text-align:center; vertical-align:middle;"><?= htmlspecialchars($blog['id']); ?></td>
                        <td style="text-align:left; vertical-align:middle; word-wrap: break-word;"><?= htmlspecialchars($blog['title']); ?></td>
                        <td style="text-align:center; vertical-align:middle;">
                            <?php if (!empty($blog['image'])): ?>
                                <img src="../admin/uploads/blogs/<?= htmlspecialchars($blog['image']); ?>" 
                                     style="max-width:120px; max-height:80px; object-fit:cover;" class="img-thumbnail">
                            <?php else: ?>No Image<?php endif; ?>
                        </td>
                        <td style="text-align:center; vertical-align:middle;">
                            <a href="index.php?page=view_blog&id=<?= $blog['id']; ?>" class="btn btn-info btn-sm">View</a>
                            <a href="index.php?page=edit_blog&id=<?= $blog['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <form method="POST" style="display:inline-block;" 
                                  onsubmit="return confirm('Are you sure you want to delete this blog and its image?');">
                                <input type="hidden" name="delete_id" value="<?= $blog['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">No blogs found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


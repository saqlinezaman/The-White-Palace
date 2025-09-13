<?php
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../includes/header.php';

$database = new Database();
$db = $database->db_connection();

$success = '';
$error = '';

// Delete blog if delete_id is set
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    try {
        // Start transaction
        $db->beginTransaction();

        // Get blog image
        $stmt = $db->prepare("SELECT image FROM blogs WHERE id = :id");
        $stmt->execute([':id' => $delete_id]);
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($blog) {
            // Delete image from uploads folder if it exists
            $image_path = __DIR__ . '/../uploads/blogs/' . $blog['image'];
            if (!empty($blog['image']) && file_exists($image_path)) {
                if (!unlink($image_path)) {
                    throw new Exception("Failed to delete image file.");
                }
            }

            // Delete blog record from database
            $stmt = $db->prepare("DELETE FROM blogs WHERE id = :id");
            $stmt->execute([':id' => $delete_id]);

            // Commit transaction
            $db->commit();
            $success = "Blog and associated image deleted successfully!";
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

<h2 class="mb-4">Blogs List</h2>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">Image</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($blogs)): ?>
            <?php foreach ($blogs as $blog): ?>
                <tr>
                    <td><?php echo htmlspecialchars($blog['id']); ?></td>
                    <td><?php echo htmlspecialchars($blog['title']); ?></td>
                    <td><?php echo htmlspecialchars($blog['description']); ?></td>
                    <td>
                        <?php if (!empty($blog['image'])): ?>
                            <img src="../admin/uploads/blogs/<?php echo htmlspecialchars($blog['image']); ?>" alt="Blog Image" style="max-width: 100px; height: auto;">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="index.php?page=edit_blog&id=<?php echo $blog['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="?delete_id=<?php echo $blog['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this blog and its image?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">No blogs found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>


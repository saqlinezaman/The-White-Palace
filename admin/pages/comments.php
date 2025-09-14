<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../admin/config/db_config.php';
$database = new Database();
$db = $database->db_connection();

// Handle delete actions before any output
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        if ($_POST['action'] === 'delete_all') {
            $stmt = $db->prepare("DELETE FROM comments");
            $stmt->execute();
            $_SESSION['message'] = "All comments deleted successfully.";
        } elseif ($_POST['action'] === 'delete_selected' && !empty($_POST['comment_ids'])) {
            $ids = array_map('intval', $_POST['comment_ids']);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $db->prepare("DELETE FROM comments WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            $_SESSION['message'] = "Selected comments deleted successfully.";
        } elseif ($_POST['action'] === 'delete_single' && isset($_POST['comment_id'])) {
            $stmt = $db->prepare("DELETE FROM comments WHERE id = ?");
            $stmt->execute([$_POST['comment_id']]);
            $_SESSION['message'] = "Comment deleted successfully.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error deleting comments: " . $e->getMessage();
    }
}

// Fetch all comments with user & room/blog info
$stmt = $db->prepare("
    SELECT 
        c.id AS comment_id, 
        c.comment, 
        c.created_at, 
        u.username, 
        u.email, 
        COALESCE(r.name, b.title) AS source_name,
        CASE 
            WHEN c.room_id IS NOT NULL THEN 'Room'
            WHEN c.blog_id IS NOT NULL THEN 'Blog'
            ELSE 'Unknown'
        END AS source_type
    FROM comments c
    JOIN users u ON c.user_id = u.id
    LEFT JOIN rooms r ON c.room_id = r.id
    LEFT JOIN blogs b ON c.blog_id = b.id
    ORDER BY c.created_at DESC
");
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mt-5">
    <h2 class="mb-4">All Comments</h2>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['message']) ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <form method="POST" style="display: none;" id="deleteAllForm">
            <input type="hidden" name="action" value="delete_all">
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete all comments?');">Delete All Comments</button>
        </form>
        <form method="POST" style="display: none;" id="deleteSelectedForm">
            <input type="hidden" name="action" value="delete_selected">
            <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Are you sure you want to delete selected comments?');">Delete Selected Comments</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>#</th>
                    <th>Source</th>
                    <th>Type</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Comment</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $i => $c): ?>
                <tr>
                    <td><input type="checkbox" name="comment_ids[]" value="<?= $c['comment_id'] ?>" form="deleteSelectedForm" class="rowCheckbox"></td>
                    <td><?= $i+1 ?></td>
                    <td><?= htmlspecialchars($c['source_name']) ?></td>
                    <td><?= htmlspecialchars($c['source_type']) ?></td>
                    <td><?= htmlspecialchars($c['username']) ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td><?= nl2br(htmlspecialchars($c['comment'])) ?></td>
                    <td><?= date("M d, Y H:i", strtotime($c['created_at'])) ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="delete_single">
                            <input type="hidden" name="comment_id" value="<?= $c['comment_id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this comment?');">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
    const deleteAllForm = document.getElementById('deleteAllForm');
    const deleteSelectedForm = document.getElementById('deleteSelectedForm');

    function toggleButtons() {
        const anyChecked = Array.from(rowCheckboxes).some(checkbox => checkbox.checked);
        if (selectAllCheckbox.checked) {
            deleteAllForm.style.display = 'inline-block';
            deleteSelectedForm.style.display = 'none';
        } else {
            deleteAllForm.style.display = 'none';
            deleteSelectedForm.style.display = anyChecked ? 'inline-block' : 'none';
        }
    }

    toggleButtons();

    selectAllCheckbox.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
        toggleButtons();
    });

    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(rowCheckboxes).every(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
            toggleButtons();
        });
    });
});
</script>

<?php
ob_end_flush();
?>

<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../admin/config/db_config.php';
$database = new Database();
$db = $database->db_connection();

// Handle delete actions
$alert = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete_all') {
        $db->exec("DELETE FROM comments");
        $alert = "All comments deleted.";
    } elseif ($_POST['action'] === 'delete_selected' && isset($_POST['selected_ids'])) {
        $ids = array_filter(array_map('intval', $_POST['selected_ids']));
        if (!empty($ids)) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $db->prepare("DELETE FROM comments WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            $alert = "Selected comments deleted.";
        }
    }
}

// Handle single delete via GET
if (isset($_GET['delete_single'])) {
    $deleteId = intval($_GET['delete_single']);
    if ($deleteId) {
        $stmt = $db->prepare("DELETE FROM comments WHERE id=?");
        $stmt->execute([$deleteId]);
        $alert = "Comment deleted successfully.";
    }
}

// Fetch all comments with user & room info
$stmt = $db->prepare("
    SELECT c.id AS comment_id, c.comment, c.created_at, u.username, u.email, r.name AS room_name
    FROM comments c
    JOIN users u ON c.user_id = u.id
    JOIN rooms r ON c.room_id = r.id
    ORDER BY c.created_at DESC
");
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mt-5">
    <h2 class="mb-4">Room Comments</h2>

    <?php if($alert): ?>
        <script>
            alert("<?= htmlspecialchars($alert) ?>");
            window.location.href = "./pages/comments.php"; // Use relative path to ensure correct redirect
        </script>
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
            <thead class="table-dark">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>#</th>
                    <th>Room Name</th>
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
                    <td><input type="checkbox" name="selected_ids[]" value="<?= $c['comment_id'] ?>" form="deleteSelectedForm"></td>
                    <td><?= $i+1 ?></td>
                    <td><?= htmlspecialchars($c['room_name']) ?></td>
                    <td><?= htmlspecialchars($c['username']) ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td><?= nl2br(htmlspecialchars($c['comment'])) ?></td>
                    <td><?= date("M d, Y H:i", strtotime($c['created_at'])) ?></td>
                    <td>
                        <a href="?delete_single=<?= $c['comment_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this comment?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Select All checkbox functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
    checkboxes.forEach(c => c.checked = this.checked);
    updateDeleteButtons();
});

function updateDeleteButtons() {
    const total = document.querySelectorAll('input[name="selected_ids[]"]').length;
    const checked = document.querySelectorAll('input[name="selected_ids[]"]:checked').length;
    document.getElementById('deleteAllForm').style.display = (checked === total && total>0) ? 'inline' : 'none';
    document.getElementById('deleteSelectedForm').style.display = (checked>0 && checked<total) ? 'inline' : 'none';
}

document.querySelectorAll('input[name="selected_ids[]"]').forEach(cb => {
    cb.addEventListener('change', function() {
        const allChecked = document.querySelectorAll('input[name="selected_ids[]"]:checked').length ===
                           document.querySelectorAll('input[name="selected_ids[]"]').length;
        document.getElementById('selectAll').checked = allChecked;
        updateDeleteButtons();
    });
});
updateDeleteButtons();
</script>
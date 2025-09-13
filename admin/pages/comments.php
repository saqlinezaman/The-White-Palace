<?php
ob_start(); // Start output buffering
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

// Include header after handling deletions
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mt-5">
    <h2 class="mb-4">Room Comments</h2>
    
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
            <thead class="">
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
                    <td><input type="checkbox" name="comment_ids[]" value="<?= $c['comment_id'] ?>" form="deleteSelectedForm" class="rowCheckbox"></td>
                    <td><?= $i+1 ?></td>
                    <td><?= htmlspecialchars($c['room_name']) ?></td>
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

    // Function to toggle button visibility
    function toggleButtons() {
        const anyChecked = Array.from(rowCheckboxes).some(checkbox => checkbox.checked);
        if (selectAllCheckbox.checked) {
            // Show only "Delete All" when "Select All" is checked
            deleteAllForm.style.display = 'inline-block';
            deleteSelectedForm.style.display = 'none';
        } else {
            // Show "Delete Selected" only if at least one row is checked and "Select All" is not checked
            deleteAllForm.style.display = 'none';
            deleteSelectedForm.style.display = anyChecked ? 'inline-block' : 'none';
        }
    }

    // Initial state
    toggleButtons();

    // Handle "Select All" checkbox
    selectAllCheckbox.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
        toggleButtons();
    });

    // Handle individual row checkboxes
    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Update "Select All" state based on all rows being checked
            const allChecked = Array.from(rowCheckboxes).every(checkbox => checkbox.checked);
            selectAllCheckbox.checked = allChecked;
            toggleButtons();
        });
    });
});
</script>

<?php
ob_end_flush(); // Flush the output buffer
?>
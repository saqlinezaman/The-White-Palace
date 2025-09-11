<?php
require_once __DIR__ . "/../config/db_config.php";
require_once __DIR__ . "/../config/class.user.php";

$database = new Database();
$db = $database->db_connection();
$user = new User($db);
$user = new User();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'reply' && isset($_POST['id'], $_POST['reply'])) {
        $id = (int)$_POST['id'];
        $reply = trim($_POST['reply']);

        if ($id > 0 && !empty($reply)) {
            try {
                // Fetch the email and name for the message
                $sql = "SELECT email, name FROM contact WHERE id = :id";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$row || empty($row['email'])) {
                    throw new Exception("Email address not found for this message.");
                }

                $email = $row['email'];
                $name = $row['name'];

                // Update the contact table with the reply
                $sql = "UPDATE contact SET is_replied = 1, replied_text = :reply, replied_at = NOW() WHERE id = :id";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':reply', $reply, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                // Send email to the user
                $subject = "Reply to Your Feedback";
                $message = '
                <div style="font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #333;">
                    <h1>The White Palace</h1>
                    <h5 style="margin-bottom: 20px;">Your comfort is our priority.</h5>
                    <h2>Reply to Your Feedback</h2>
                    <p>Hi ' . htmlspecialchars($name) . ',</p>
                    <p>Thank you for your feedback. Here is our response:</p>
                    <p style="margin: 16px 0; padding: 10px; background: #f8f9fa; border-left: 4px solid #007bff;">
                        ' . nl2br(htmlspecialchars($reply)) . '
                    </p>
                    <p>We value your input and look forward to serving you!</p>
                    <hr>
                    <p style="font-size: 12px; color: #777;">This is an automated email from The White Palace. Please do not reply directly to this email.</p>
                </div>
                ';

                if (!$user->sendMail($email, $subject, $message)) {
                    throw new Exception("Failed to send email to $email: " . ($_SESSION['mailError'] ?? 'Unknown error'));
                }
            } catch (Exception $e) {
                $error = "Failed to save reply or send email: " . $e->getMessage();
            }
        } else {
            $error = "Invalid input: ID or reply missing.";
        }
    } elseif ($_POST['action'] === 'delete_all') {
        try {
            $sql = "DELETE FROM contact";
            $db->exec($sql);
        } catch (Exception $e) {
            $error = "Failed to delete all messages: " . $e->getMessage();
        }
    } elseif ($_POST['action'] === 'delete_selected' && isset($_POST['selected_ids'])) {
        $ids = array_filter(array_map('intval', $_POST['selected_ids']));
        if (!empty($ids)) {
            try {
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                $sql = "DELETE FROM contact WHERE id IN ($placeholders)";
                $stmt = $db->prepare($sql);
                $stmt->execute($ids);
            } catch (Exception $e) {
                $error = "Failed to delete selected messages: " . $e->getMessage();
            }
        } else {
            $error = "No messages selected for deletion.";
        }
    }
}

// Fetch all messages with user info
$sql = "SELECT c.*, CASE WHEN u.id IS NULL THEN 0 ELSE 1 END AS is_registered
        FROM contact c
        LEFT JOIN users u ON u.email = c.email
        ORDER BY c.created_at DESC";
$rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Count unread
$unread = $db->query("SELECT COUNT(*) FROM contact WHERE is_read = 0")->fetchColumn();
?>

<div class="content">
    <h3 class="mb-3">Users Feedback</h3>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <div class="mb-3">
        <span class="badge text-bg-info">Total: <?= count($rows) ?></span>
        <span class="badge text-bg-danger" id="unreadBadge">Unread: <?= $unread ?></span>
    </div>

    <div class="mb-3">
        <form method="POST" style="display: none;" id="deleteAllForm">
            <input type="hidden" name="action" value="delete_all">
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete all messages?');">Delete All Messages</button>
        </form>
        <form method="POST" style="display: none;" id="deleteSelectedForm">
            <input type="hidden" name="action" value="delete_selected">
            <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Are you sure you want to delete selected messages?');">Delete Selected Messages</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Reply</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $i => $row): ?>
                <tr data-id="<?= $row['id'] ?>">
                    <td><input type="checkbox" name="selected_ids[]" value="<?= $row['id'] ?>" form="deleteSelectedForm"></td>
                    <td><?= $i+1 ?></td>
                    <td><?= htmlspecialchars($row['name']) ?>
                        <?php if(!$row['is_registered']): ?>
                        <i class="fa-solid fa-user-xmark text-danger" title="Not registered"></i>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['subject']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                    <td>
                        <?php if($row['is_replied']): ?>
                            <span class="badge text-bg-success">Replied</span>
                        <?php else: ?>
                            <span class="badge text-bg-warning">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if(!$row['is_replied']): ?>
                        <button class="btn btn-sm btn-success toggleReplyBtn">Send Reply</button>
                        <form method="POST" class="replyContainer" style="display: none;">
                            <input type="hidden" name="action" value="reply">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <textarea class="form-control replyText mt-2" name="reply" placeholder="Type reply..." required></textarea>
                            <button type="submit" class="btn btn-sm btn-success mt-2">Submit</button>
                        </form>
                        <?php else: ?>
                        <small class="text-muted">Sent at <?= $row['replied_at'] ?></small>
                        <div><?= nl2br(htmlspecialchars($row['replied_text'])) ?></div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Vanilla JavaScript for toggling reply form and managing delete button visibility
document.querySelectorAll('.toggleReplyBtn').forEach(button => {
    button.addEventListener('click', function() {
        const container = this.nextElementSibling;
        container.style.display = container.style.display === 'none' ? 'block' : 'none';
        this.textContent = this.textContent === 'Send Reply' ? 'Cancel' : 'Send Reply';
    });
});

// Handle Select All checkbox
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('input[name="selected_ids[]"]').forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateDeleteButtonVisibility();
});

// Update delete button visibility based on checkbox states
function updateDeleteButtonVisibility() {
    const deleteAllForm = document.getElementById('deleteAllForm');
    const deleteSelectedForm = document.getElementById('deleteSelectedForm');
    const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
    const checkedCount = document.querySelectorAll('input[name="selected_ids[]"]:checked').length;
    const totalCount = checkboxes.length;

    // Show Delete All if all checkboxes are selected (via Select All)
    deleteAllForm.style.display = (checkedCount === totalCount && totalCount > 0) ? 'inline' : 'none';
    // Show Delete Selected if some but not all checkboxes are selected
    deleteSelectedForm.style.display = (checkedCount > 0 && checkedCount < totalCount) ? 'inline' : 'none';
}

// Update button visibility when individual checkboxes change
document.querySelectorAll('input[name="selected_ids[]"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        // Uncheck Select All if any individual checkbox is toggled
        const selectAll = document.getElementById('selectAll');
        const allChecked = document.querySelectorAll('input[name="selected_ids[]"]').length === 
                          document.querySelectorAll('input[name="selected_ids[]"]:checked').length;
        selectAll.checked = allChecked;
        updateDeleteButtonVisibility();
    });
});

// Initial check for button visibility
updateDeleteButtonVisibility();
</script>
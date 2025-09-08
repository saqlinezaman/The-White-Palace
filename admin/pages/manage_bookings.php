<?php
// Start output buffering to prevent header issues
ob_start();

require_once __DIR__ . '/../config/db_config.php';
$database = new Database();
$db = $database->db_connection();
$msg = '';

// Handle status change
if (isset($_POST['change_status'])) {
    $id = intval($_POST['booking_id']);
    $new_status = $_POST['status'];

    // If approving, check for conflicts
    if ($new_status === 'approved') {
        $stmt = $db->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->execute([$id]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($booking) {
            $check = $db->prepare("
                SELECT COUNT(*) AS cnt 
                FROM bookings 
                WHERE room_id = ? AND status = 'approved' AND id != ? 
                AND (
                    (check_in <= ? AND check_out >= ?) OR
                    (check_in <= ? AND check_out >= ?) OR
                    (check_in >= ? AND check_out <= ?)
                )
            ");
            $check->execute([
                $booking['room_id'],
                $id,
                $booking['check_in'], $booking['check_in'],
                $booking['check_out'], $booking['check_out'],
                $booking['check_in'], $booking['check_out']
            ]);
            $row = $check->fetch(PDO::FETCH_ASSOC);

            if (intval($row['cnt']) > 0) {
                $msg = "Cannot approve: time conflict with another approved booking.";
            } else {
                $db->prepare("UPDATE bookings SET status = ? WHERE id = ?")->execute([$new_status, $id]);
                $msg = "Booking status updated to Approved.";
            }
        }
    } else {
        // For reject or pending, just update
        $db->prepare("UPDATE bookings SET status = ? WHERE id = ?")->execute([$new_status, $id]);
        $msg = "Booking status updated to " . ucfirst($new_status) . ".";
    }

    // Redirect safely before any output
   echo '<script>window.location.href="manage_bookings.php?msg=' . urlencode($msg) . '";</script>';
    
}

// Fetch all bookings
$rows = $db->query("
    SELECT b.*, r.name AS room_name 
    FROM bookings b 
    JOIN rooms r ON b.room_id = r.id 
    ORDER BY b.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Get message from URL if available
if (isset($_GET['msg'])) $msg = $_GET['msg'];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="container mt-4">
    <?php if ($msg): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <h3>Booking Management</h3>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Room</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Nights</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="10" class="text-center">No bookings found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rows as $b): ?>
                        <tr>
                            <td><?= $b['id'] ?></td>
                            <td><?= htmlspecialchars($b['room_name']) ?></td>
                            <td><?= htmlspecialchars($b['user_name']) ?></td>
                            <td><?= htmlspecialchars($b['user_phone']) ?></td>
                            <td><?= date('M j, Y', strtotime($b['check_in'])) ?></td>
                            <td><?= date('M j, Y', strtotime($b['check_out'])) ?></td>
                            <td><?= $b['nights'] ?></td>
                            <td>৳<?= number_format($b['total_price'], 2) ?></td>
                            <td>
                                <?php 
                                    $statusClass = match($b['status']) {
                                        'pending' => 'bg-warning',
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                ?>
                                <span class="badge <?= $statusClass ?>"><?= ucfirst($b['status']) ?></span>
                            </td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="pending" <?= $b['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="approved" <?= $b['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                        <option value="rejected" <?= $b['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                    </select>
                                    <input type="hidden" name="change_status" value="1">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<?php
// Flush output buffer
ob_end_flush();
?>

<?php
require_once __DIR__ . '/../config/db_config.php';

$database = new Database();
$db = $database->db_connection();

$msg = '';

if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    // get booking
    $b = $db->prepare("SELECT * FROM bookings WHERE id = ?");
    $b->execute([$id]);
    $booking = $b->fetch(PDO::FETCH_ASSOC);
    if ($booking) {
        // conflict check: any other approved booking overlapping?
        $check = $db->prepare("
            SELECT COUNT(*) AS cnt FROM bookings
            WHERE room_id = ?
              AND status = 'approved'
              AND id != ?
              AND (check_in < :check_out AND check_out > :check_in)
        ");
        $check->execute([$booking['room_id'], $id, ':check_out' => $booking['check_out'], ':check_in' => $booking['check_in']]);
        $row = $check->fetch(PDO::FETCH_ASSOC);
        if (intval($row['cnt']) > 0) {
            $msg = "Cannot approve: time conflict with another approved booking.";
        } else {
            $db->prepare("UPDATE bookings SET status='approved' WHERE id = ?")->execute([$id]);
            $msg = "Booking approved.";
        }
    }
}

if (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);
    $db->prepare("UPDATE bookings SET status='rejected' WHERE id = ?")->execute([$id]);
    $msg = "Booking rejected.";
}

// fetch all bookings
$rows = $db->query("SELECT b.*, r.name AS room_name FROM bookings b JOIN rooms r ON b.room_id = r.id ORDER BY b.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php 
// header include
require_once __DIR__ . '/../includes/header.php'; 

// sidebar include
require_once __DIR__ . '/../includes/sidebar.php'; 
?>
<?php ob_start(); ?>
<div class="p-6">
    <?php if ($msg): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th><th>Room</th><th>Name</th><th>Phone</th><th>Check In</th><th>Check Out</th><th>Nights</th><th>Total</th><th>Status</th><th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $b): ?>
                <tr>
                    <td><?= $b['id'] ?></td>
                    <td><?= htmlspecialchars($b['room_name']) ?></td>
                    <td><?= htmlspecialchars($b['user_name']) ?></td>
                    <td><?= htmlspecialchars($b['user_phone']) ?></td>
                    <td><?= $b['check_in'] ?></td>
                    <td><?= $b['check_out'] ?></td>
                    <td><?= $b['nights'] ?></td>
                    <td>৳<?= number_format($b['total_price'],2) ?></td>
                    <td><?= ucfirst($b['status']) ?></td>
                    <td>
                        <?php if ($b['status'] === 'pending'): ?>
                            <a href="?approve=<?= $b['id'] ?>" class="btn btn-sm btn-success">Approve</a>
                            <a href="?reject=<?= $b['id'] ?>" class="btn btn-sm btn-danger">Reject</a>
                        <?php else: ?>
                            <?= ucfirst($b['status']) ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
// Flush the output buffer
ob_end_flush();
?>

<?php include __DIR__ . '/../includes/footer.php'; ?>

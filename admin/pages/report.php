<?php
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../includes/header.php';

$database = new Database();
$db = $database->db_connection();

// Default query (শুধু complete status দেখাবে)
$sql = "SELECT id, user_name, total_price, check_in 
        FROM bookings 
        WHERE status = 'complete'";

$start_date = '';
$end_date   = '';
$result     = [];

// Date filter check
if (isset($_POST['search'])) {
    $start_date = $_POST['start_date'];
    $end_date   = $_POST['end_date'];

    if (!empty($start_date) && !empty($end_date)) {
        $stmt = $db->prepare($sql . " AND check_in BETWEEN :start_date AND :end_date");
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $result = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    $result = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

$row_count = count($result);
?>

<div class="container mt-4">
    <h2>Booking Report</h2>
    <p>Total Completed Bookings: <strong><?php echo $row_count; ?></strong></p>

    <!-- Date Search Form -->
    <form method="POST" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label>Start Date</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
            </div>
            <div class="col-md-4">
                <label>End Date</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" name="search" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    <!-- Printable Area -->
    <div class="print-area">
        <div class="print-header d-none text-center">
            <h2>The White Palace</h2>
            <?php if (!empty($start_date) && !empty($end_date)): ?>
                <h5>Report of: <?php echo $start_date; ?> to <?php echo $end_date; ?></h5>
            <?php endif; ?>
            <p>Printed on: <?php echo date("Y-m-d"); ?></p>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Customer Name</th>
                        <th>Amount (৳)</th>
                        <th>Check-In Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($row_count > 0): ?>
                        <?php foreach($result as $row): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                                <td><?php echo number_format($row['total_price'], 2); ?></td>
                                <td><?php echo $row['check_in']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No completed bookings found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Print Button -->
    <button class="btn btn-success" onclick="window.print()">Print Report</button>
</div>

<style>
/* Default hidden */
.print-header {
    display: none;
}
/* Only visible in print */
@media print {
    body * {
        visibility: hidden;
    }
    .print-area, .print-area * {
        visibility: visible;
    }
    .print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .print-header {
        display: block !important;
        margin-bottom: 20px;
    }
    .btn, form, h2, p {
        display: none !important;
    }
}
</style>

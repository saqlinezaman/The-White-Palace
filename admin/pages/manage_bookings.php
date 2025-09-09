<?php
ob_start();
require_once __DIR__ . '/../config/db_config.php';
$database = new Database();
$db = $database->db_connection();

// Handle form submission for status updates
if(isset($_POST['update_booking'])){
    $booking_id = intval($_POST['booking_id']);
    $status = $_POST['status'];
    $payment_status = $_POST['payment_status'];
    
    // Update booking status and payment status
    $stmt = $db->prepare("UPDATE bookings SET status = ?, payment_status = ? WHERE id = ?");
    if($stmt->execute([$status, $payment_status, $booking_id])){
        $success_message = "Booking updated successfully!";
    } else {
        $error_message = "Failed to update booking!";
    }
}

// Fetch bookings
$rows = $db->query("
    SELECT b.*, r.name AS room_name, r.price AS room_price 
    FROM bookings b 
    JOIN rooms r ON b.room_id = r.id 
    ORDER BY b.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="container mt-4">
    <h3>Booking Management</h3>

    <?php if(isset($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $success_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(isset($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Search Section -->
    <div class="row mb-3">
        <div class="col-md-8">
            <div class="d-flex align-items-center">
                <span id="searchResults" class="text-muted"></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" 
                       id="searchInput" 
                       class="form-control" 
                       placeholder="Enter phone number..."
                       autocomplete="off">
                <button type="button" 
                        id="clearSearch" 
                        class="btn btn-success">
                    Clear
                </button>
            </div>
            <small class="form-text text-muted">Enter phone number to filter results</small>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle" id="bookingTable">
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
                    <th>Advance</th>
                    <th>Transaction ID</th>
                    <th>Room Status</th>
                    <th>Payment</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($rows)): ?>
                    <tr id="noBookingsRow">
                        <td colspan="13" class="text-center">No bookings found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($rows as $b): ?>
                        <tr class="booking-row" data-booking-id="<?= $b['id'] ?>">
                            <form method="POST" style="display: contents;">
                                <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                
                                <td class="booking-id"><?= $b['id'] ?></td>
                                <td><?= htmlspecialchars($b['room_name']) ?></td>
                                <td><?= htmlspecialchars($b['user_name']) ?></td>
                                <td class="user-phone"><?= htmlspecialchars($b['user_phone']) ?></td>
                                <td><?= date('M j, Y', strtotime($b['check_in'])) ?></td>
                                <td><?= date('M j, Y', strtotime($b['check_out'])) ?></td>
                                <td><?= $b['nights'] ?></td>
                                <td>৳<?= number_format($b['total_price'],2) ?></td>
                                <td>৳<?= number_format($b['advance_amount'],2) ?></td>
                                <td><?= htmlspecialchars($b['transaction_id'] ?? '-') ?></td>

                                <!-- Status -->
                                <td>
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="pending" <?= $b['status']=='pending'?'selected':'' ?>>Pending</option>
                                        <option value="approved" <?= $b['status']=='approved'?'selected':'' ?>>Approved</option>
                                        <option value="rejected" <?= $b['status']=='rejected'?'selected':'' ?>>Rejected</option>
                                    </select>
                                    <?php
                                        $statusClass = match($b['status']) {
                                            'pending' => 'bg-warning text-dark',
                                            'approved' => 'bg-success text-white',
                                            'rejected' => 'bg-danger text-white',
                                            default => 'bg-secondary text-white'
                                        };
                                    ?>
                                    <span class="badge <?= $statusClass ?> mt-1"><?= ucfirst($b['status']) ?></span>
                                </td>

                                <!-- Payment -->
                                <td>
                                    <select name="payment_status" class="form-select form-select-sm">
                                        <option value="pending" <?= $b['payment_status']=='pending'?'selected':'' ?>>Pending</option>
                                        <option value="paid" <?= $b['payment_status']=='paid'?'selected':'' ?>>Paid</option>
                                    </select>
                                    <?php
                                        $paymentClass = match($b['payment_status']) {
                                            'pending' => 'bg-warning text-dark',
                                            'paid' => 'bg-success text-white',
                                            default => 'bg-secondary text-white'
                                        };
                                    ?>
                                    <span class="badge <?= $paymentClass ?> mt-1"><?= ucfirst($b['payment_status']) ?></span>
                                </td>

                                <!-- Update Button -->
                                <td>
                                    <button type="submit" name="update_booking" class="btn btn-primary btn-sm">
                                        Update
                                    </button>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- No Results Message (hidden by default) -->
    <div id="noResultsMessage" class="alert alert-info text-center" style="display: none;">
        <i class="fas fa-search"></i>
        No bookings found matching your search criteria.
    </div>
</div>

<!-- Search JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearButton = document.getElementById('clearSearch');
    const bookingRows = document.querySelectorAll('.booking-row');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const searchResults = document.getElementById('searchResults');
    const bookingTable = document.getElementById('bookingTable');
    const noBookingsRow = document.getElementById('noBookingsRow');

    // Search functionality
    function performSearch() {
        const searchValue = searchInput.value.trim();
        let visibleCount = 0;
        let totalCount = bookingRows.length;

        if (searchValue === '') {
            // Show all rows when search is empty
            bookingRows.forEach(row => {
                row.style.display = '';
                visibleCount++;
            });
            noResultsMessage.style.display = 'none';
            if (noBookingsRow) {
                noBookingsRow.style.display = totalCount === 0 ? '' : 'none';
            }
            searchResults.textContent = '';
        } else {
            // Filter rows based on phone number
            bookingRows.forEach(row => {
                const phoneCell = row.querySelector('.user-phone');
                const phoneNumber = phoneCell ? phoneCell.textContent.trim() : '';
                if (phoneNumber && phoneNumber.includes(searchValue)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Hide the "no bookings" row when searching
            if (noBookingsRow) {
                noBookingsRow.style.display = 'none';
            }

            // Show/hide no results message
            if (visibleCount === 0) {
                noResultsMessage.style.display = 'block';
                searchResults.textContent = `No matches found for "${searchValue}"`;
            } else {
                noResultsMessage.style.display = 'none';
                searchResults.textContent = `Showing ${visibleCount} of ${totalCount} bookings`;
            }
        }
    }

    // Event listeners
    searchInput.addEventListener('input', performSearch);
    searchInput.addEventListener('keyup', performSearch);

    // Clear search
    clearButton.addEventListener('click', function() {
        searchInput.value = '';
        performSearch();
        searchInput.focus();
    });

    // Auto-focus on search input when page loads
    searchInput.focus();
});
</script>

<style>
/* Custom styles for search functionality */
#searchInput:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.booking-row {
    transition: all 0.3s ease;
}

.booking-row:hover {
    background-color: #f8f9fa;
}

#noResultsMessage {
    margin-top: 20px;
}

.input-group-text {
    background-color: #e9ecef;
    border-color: #ced4da;
}

#clearSearch {
    border-left: 0;
}

#searchResults {
    font-size: 0.9em;
    color: #6c757d;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .container {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .input-group {
        margin-bottom: 10px;
    }
}
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>
<?php ob_end_flush(); ?>
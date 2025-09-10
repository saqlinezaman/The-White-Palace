<?php
ob_start();
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/class.user.php';

$database = new Database();
$db = $database->db_connection();
$user = new User();

// --- Handle form submission for status/payment update ---
if (isset($_POST['update_booking'])) {
    $booking_id = intval($_POST['booking_id']);
    $status = $_POST['status'];
    $payment_status = $_POST['payment_status'];

    $stmt = $db->prepare("UPDATE bookings SET status = ?, payment_status = ? WHERE id = ?");
    if ($stmt->execute([$status, $payment_status, $booking_id])) {
        $success_message = "Booking updated successfully!";
    } else {
        $error_message = "Failed to update booking!";
    }
}

// --- Handle sending invoice manually ---
if (isset($_POST['send_invoice'])) {
    $booking_id = intval($_POST['booking_id']);
    $stmtBooking = $db->prepare("SELECT b.*, r.name AS room_name, r.price AS room_price 
                                 FROM bookings b 
                                 JOIN rooms r ON b.room_id = r.id 
                                 WHERE b.id = ? LIMIT 1");
    $stmtBooking->execute([$booking_id]);
    $bookingData = $stmtBooking->fetch(PDO::FETCH_ASSOC);

    if ($bookingData) {
        $room = [
            'name' => $bookingData['room_name'],
            'price' => $bookingData['room_price']
        ];
        $user->sendInvoice($bookingData['user_email'], $bookingData, $room);

        // Update invoice_sent column (make sure column exists in DB)
        $stmtUpdate = $db->prepare("UPDATE bookings SET invoice_sent=1 WHERE id=?");
        $stmtUpdate->execute([$booking_id]);

        $success_message = "Invoice sent to customer email!";
    } else {
        $error_message = "Booking not found!";
    }
}

// --- Fetch bookings ---
$rows = $db->query("
    SELECT b.*, r.name AS room_name, r.price AS room_price 
    FROM bookings b 
    JOIN rooms r ON b.room_id = r.id 
    ORDER BY b.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="row mt-4">
    <div class="col-md-12">
        <h3>Booking Management</h3>
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

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $success_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle" id="bookingTable">
        <thead class=" text-center">
          <tr>
            <th>ID</th>
            <th>Room</th>
            <th>Name</th>
            <th>Phone</th>
            <th>CheckIn & Out</th>
            <th>Status</th>
            <th>Payment</th>
            <th>Update</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if (empty($rows)): ?>
          <tr id="noBookingsRow"><td colspan="9" class="text-center">No bookings found</td></tr>
        <?php else: ?>
          <?php foreach ($rows as $b): ?>
          <tr class="booking-row">
            <form method="POST">
              <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
              <td><?= $b['id'] ?></td>
              <td><?= htmlspecialchars($b['room_name']) ?></td>
              <td><?= htmlspecialchars($b['user_name']) ?></td>
              <td class="user-phone"><?= htmlspecialchars($b['user_phone']) ?></td>
              <td><?= date('M j', strtotime($b['check_in'])) ?> <strong>To</strong> <?= date('M j', strtotime($b['check_out'])) ?></td>

              <!-- Status -->
              <td>
                <select name="status" class="form-select form-select-sm">
                  <option value="pending" <?= $b['status']=='pending'?'selected':'' ?>>Pending</option>
                  <option value="approved" <?= $b['status']=='approved'?'selected':'' ?>>Approved</option>
                  <option value="rejected" <?= $b['status']=='rejected'?'selected':'' ?>>Rejected</option>
                </select>
                <span class="badge <?= $b['status'] == 'approved' ? 'bg-success' : ($b['status'] == 'pending' ? 'bg-warning' : 'bg-danger') ?> text-white" style="display: block; margin-top: 5px;">
                  <?= htmlspecialchars(ucfirst($b['status'])) ?>
                </span>
              </td>

              <!-- Payment -->
              <td>
                <select name="payment_status" class="form-select form-select-sm">
                  <option value="pending" <?= $b['payment_status']=='pending'?'selected':'' ?>>Pending</option>
                  <option value="paid" <?= $b['payment_status']=='paid'?'selected':'' ?>>Paid</option>
                </select>
                <span class="badge <?= $b['payment_status'] == 'paid' ? 'bg-success' : 'bg-warning' ?> text-white" style="display: block; margin-top: 5px;">
                  <?= htmlspecialchars(ucfirst($b['payment_status'])) ?>
                </span>
              </td>

              <!-- Update -->
              <td class="text-center">
                <button type="submit" name="update_booking" class="btn btn-primary btn-sm w-100">Update</button>
              </td>

              <!-- Actions -->
              <td class="text-center">
                <button type="button" class="btn btn-info btn-sm mb-1 w-100 view-details"
                    data-id="<?= $b['id'] ?>"
                    data-room="<?= htmlspecialchars($b['room_name']) ?>"
                    data-user="<?= htmlspecialchars($b['user_name']) ?>"
                    data-phone="<?= htmlspecialchars($b['user_phone']) ?>"
                    data-email="<?= htmlspecialchars($b['user_email']) ?>"
                    data-checkin="<?= $b['check_in'] ?>"
                    data-checkout="<?= $b['check_out'] ?>"
                    data-total="<?= $b['total_price'] ?>"
                    data-advance="<?= $b['advance_amount'] ?>"
                    data-due="<?= $b['total_price'] - $b['advance_amount'] ?>"
                    data-payment="<?= ucfirst($b['payment_status']) ?>"
                    data-transaction="<?= $b['transaction_id'] ?>"
                >View All</button>

                <?php if($b['invoice_sent'] ?? 0): ?>
                    <span class="badge bg-success">Sended</span>
                <?php else: ?>
                    <button type="submit" name="send_invoice" class="btn btn-success btn-sm w-100">Send Invoice</button>
                <?php endif; ?>
              </td>
            </form>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
    <!-- No Results Message (hidden by default) -->
    <div id="noResultsMessage" class="alert alert-info text-center" style="display: none;">
        <i class="fas fa-search"></i>
        No bookings found matching your search criteria.
    </div>
</div>

<!-- Modal for View All -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Booking Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="bookingDetails"></div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button class="btn btn-outline-primary" id="printInvoice">Print Invoice</button>
        </div>
      </div>
    </div>
</div>

<?php ob_end_flush(); ?>

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
        const searchValue = searchInput.value.trim().toLowerCase();
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
                const phoneNumber = phoneCell ? phoneCell.textContent.trim().toLowerCase() : '';
                if (phoneNumber && phoneNumber.includes(searchValue)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

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

document.addEventListener("DOMContentLoaded", function() {
    const viewButtons = document.querySelectorAll(".view-details");
    const bookingDetails = document.getElementById("bookingDetails");
    const bookingModalEl = document.getElementById("bookingModal");
    const bookingModal = new bootstrap.Modal(bookingModalEl);
    const printBtn = document.getElementById("printInvoice");

    let currentDetails = "";

    viewButtons.forEach(btn => {
        btn.addEventListener("click", function() {
            const id = this.dataset.id;
            const room = this.dataset.room;
            const user = this.dataset.user;
            const phone = this.dataset.phone;
            const email = this.dataset.email;
            const checkin = this.dataset.checkin;
            const checkout = this.dataset.checkout;
            const total = this.dataset.total;
            const advance = this.dataset.advance;
            const due = this.dataset.due;
            const payment = this.dataset.payment;
            const trx = this.dataset.transaction;

            currentDetails = `
                <div id="invoiceContent" style="font-family: Arial; font-size: 14px; color: #333;">
                    <h4 class="mb-3">Invoice</h4>
                    <table class="table table-bordered">
                        <tr><th>Booking ID</th><td>${id}</td></tr>
                        <tr><th>Room</th><td>${room}</td></tr>
                        <tr><th>Name</th><td>${user}</td></tr>
                        <tr><th>Email</th><td>${email}</td></tr>
                        <tr><th>Phone</th><td>${phone}</td></tr>
                        <tr><th>Check-in</th><td>${checkin}</td></tr>
                        <tr><th>Check-out</th><td>${checkout}</td></tr>
                        <tr><th>Total Price</th><td>৳${parseFloat(total).toFixed(2)}</td></tr>
                        <tr><th>Advance Paid</th><td>৳${parseFloat(advance).toFixed(2)}</td></tr>
                        <tr><th>Due</th><td>৳${parseFloat(due).toFixed(2)}</td></tr>
                        <tr><th>Payment Status</th><td>${payment}</td></tr>
                        <tr><th>Transaction ID</th><td>${trx}</td></tr>
                    </table>
                    <p class="text-muted mt-2">Thank you for booking with The White Palace!</p>
                </div>
            `;
            bookingDetails.innerHTML = currentDetails;
            bookingModal.show();
        });
    });

    printBtn.addEventListener("click", function() {
        const printWindow = window.open("", "_blank", "width=800,height=600");
        printWindow.document.write("<html><head><title>Invoice</title>");
        printWindow.document.write("<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>");
        printWindow.document.write("<style>body{padding:20px; font-family: Arial;} table{width:100%;} @media print{.no-print{display:none;}}</style>");
        printWindow.document.write("</head><body>");
        printWindow.document.write(currentDetails);
        printWindow.document.write(`
            <div class="text-end my-3 no-print">
                <button onclick="window.print()" class="btn btn-primary btn-sm">Print</button>
                <button onclick="window.close()" class="btn btn-secondary btn-sm">Close</button>
            </div>
        `);
        printWindow.document.write("</body></html>");
        printWindow.document.close();
        printWindow.focus();
    });
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
<?php
include __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../../admin/config/db_config.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$database = new Database();
$db = $database->db_connection();

$room_id   = intval($_GET['room_id'] ?? $_POST['room_id'] ?? 0);
$check_in  = $_GET['check_in'] ?? $_POST['check_in'] ?? '';
$check_out = $_GET['check_out'] ?? $_POST['check_out'] ?? '';

$message = "";
$advance_amount = 0;
$room_price = 0;
$total_price = 0;

// -------- Room Price Load --------
if ($room_id > 0) {
    $stmt = $db->prepare("SELECT price, total_rooms FROM rooms WHERE id = :room_id LIMIT 1");
    $stmt->execute([':room_id' => $room_id]);
    $roomData = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($roomData) {
        $room_price  = (float)$roomData['price'];
        $total_rooms = (int)$roomData['total_rooms'];
    }
}

// Form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name      = trim($_POST['name']);
    $user_email     = trim($_POST['email']);
    $user_phone     = trim($_POST['phone']);
    $room_id        = intval($_POST['room_id']);
    $check_in       = $_POST['check_in'];
    $check_out      = $_POST['check_out'];
    $transaction_id = trim($_POST['transaction_id'] ?? '');
    $user_id        = $_SESSION['user_id'] ?? 0;

    // nights
    $date1 = new DateTime($check_in);
    $date2 = new DateTime($check_out);
    $nights = $date1->diff($date2)->days;

    try {
        $roomStmt = $db->prepare("SELECT price, total_rooms FROM rooms WHERE id = :room_id LIMIT 1");
        $roomStmt->execute([':room_id' => $room_id]);
        $roomData = $roomStmt->fetch(PDO::FETCH_ASSOC);

        if (!$roomData) throw new Exception("Room not found.");

        $room_price   = (float)$roomData['price'];
        $total_rooms  = (int)$roomData['total_rooms'];
        $total_price  = $nights * $room_price;
        $advance_amount = $total_price * 0.20;

        // Room availability check
        $checkAvailability = $db->prepare("
            SELECT COUNT(*) 
            FROM bookings 
            WHERE room_id = :room_id 
              AND status IN ('pending','approved') 
              AND (
                  (check_in <= :check_out AND check_out >= :check_in)
              )
        ");
        $checkAvailability->execute([
            ':room_id'   => $room_id,
            ':check_in'  => $check_in,
            ':check_out' => $check_out
        ]);

        $alreadyBooked = (int)$checkAvailability->fetchColumn();

        if ($alreadyBooked >= $total_rooms) {
            $message = "
                <div class='alert alert-error shadow-lg my-4'>
                    <div>
                        <svg xmlns='http://www.w3.org/2000/svg' class='stroke-current flex-shrink-0 h-6 w-6' fill='none' viewBox='0 0 24 24'>
                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' />
                        </svg>
                        <span>❌ Sorry, all {$total_rooms} rooms are already booked for the selected dates.</span>
                    </div>
                </div>
            ";
        } else {
            // Always default status = checking
            $payment_status = 'checking';

            // Insert booking
            $stmt = $db->prepare("
                INSERT INTO bookings 
                (user_id, room_id, user_name, user_email, user_phone, check_in, check_out, nights, total_price, advance_amount, transaction_id, status, payment_status) 
                VALUES (:user_id, :room_id, :user_name, :user_email, :user_phone, :check_in, :check_out, :nights, :total_price, :advance_amount, :transaction_id, 'pending', :payment_status)
            ");

            $stmt->execute([
                ':user_id'        => $user_id,
                ':room_id'        => $room_id,
                ':user_name'      => $user_name,
                ':user_email'     => $user_email,
                ':user_phone'     => $user_phone,
                ':check_in'       => $check_in,
                ':check_out'      => $check_out,
                ':nights'         => $nights,
                ':total_price'    => $total_price,
                ':advance_amount' => $advance_amount,
                ':transaction_id' => $transaction_id ?: null,
                ':payment_status' => $payment_status
            ]);

            $message = "
                <div class='alert alert-success shadow-lg my-4'>
                    <div>
                        <svg xmlns='http://www.w3.org/2000/svg' class='stroke-current flex-shrink-0 h-6 w-6' fill='none' viewBox='0 0 24 24'>
                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z' />
                        </svg>
                        <span>✅ Booking successful! Payment status: <strong>{$payment_status}</strong></span>
                    </div>
                </div>
            ";
        }

    } catch (Exception $e) {
        $message = "
            <div class='alert alert-error shadow-lg my-4'>
                <div>
                    <svg xmlns='http://www.w3.org/2000/svg' class='stroke-current flex-shrink-0 h-6 w-6' fill='none' viewBox='0 0 24 24'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' />
                    </svg>
                    <span>❌ Error: " . $e->getMessage() . "</span>
                </div>
            </div>
        ";
    }
}
?>

<div class="mx-5 md:mx-20">
  <div class="items-center min-h-screen bg-gray-50 py-10">
    <form method="POST" class="w-full max-w-lg bg-white shadow-lg rounded-xl p-8 space-y-5">
      <input type="hidden" name="room_id" value="<?= $room_id ?>">

      <h2 class="text-2xl font-bold text-center text-gray-800">Book Your Stay</h2>

      <?= $message ?>

      <!-- Name -->
      <div class="form-control">
        <label class="label"><span class="label-text font-semibold">Your Name</span></label>
        <input type="text" name="name" placeholder="Enter your name" class="input input-bordered w-full" required>
      </div>

      <!-- Email -->
      <div class="form-control">
        <label class="label"><span class="label-text font-semibold">Email</span></label>
        <input type="email" name="email" placeholder="Enter your email" class="input input-bordered w-full" required>
      </div>

      <!-- Phone -->
      <div class="form-control">
        <label class="label"><span class="label-text font-semibold">Phone</span></label>
        <input type="text" name="phone" placeholder="Enter your phone number" class="input input-bordered w-full" required>
      </div>

      <!-- Check In -->
      <div class="form-control">
        <label class="label"><span class="label-text font-semibold">Check-In</span></label>
        <input type="date" name="check_in" value="<?= htmlspecialchars($check_in) ?>" class="input input-bordered w-full" required>
      </div>

      <!-- Check Out -->
      <div class="form-control">
        <label class="label"><span class="label-text font-semibold">Check-Out</span></label>
        <input type="date" name="check_out" value="<?= htmlspecialchars($check_out) ?>" class="input input-bordered w-full" required>
      </div>

      <!-- Payment Instruction -->
      <div class="mt-4">
        <p class="text-sm text-gray-600 bg-gray-100 p-3 rounded-lg border">
          💡 Please send <strong>
            <?php 
              if (!empty($check_in) && !empty($check_out) && $room_price > 0) {
                  $nights = (new DateTime($check_in))->diff(new DateTime($check_out))->days;
                  $total_price = $nights * $room_price;
                  $advance_amount = $total_price * 0.20;
                  echo number_format($advance_amount, 0) . " Tk (20% advance)";
              } else {
                  echo "20% advance payment";
              }
            ?>
          </strong> 
          to <b>017XXXXXXXX</b> (bKash/Nagad - Send Money).
        </p>
      </div>

      <!-- Transaction ID -->
      <div class="form-control">
        <label class="label"><span class="label-text font-semibold">Transaction ID (after bKash/Nagad Send money)</span></label>
        <input type="text" name="transaction_id" placeholder="Enter your bKash/Nagad Txn ID" class="input input-bordered w-full">
      </div>

      <!-- Submit -->
      <div class="form-control mt-6">
        <button type="submit" class="btn bg-green-500 text-white w-full text-lg">Confirm Booking</button>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

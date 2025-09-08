<?php
include __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../../admin/config/db_config.php';

// Database connection
$database = new Database();
$db = $database->db_connection();

$room_id   = intval($_GET['room_id'] ?? $_POST['room_id'] ?? 0);
$check_in  = $_GET['check_in'] ?? $_POST['check_in'] ?? '';
$check_out = $_GET['check_out'] ?? $_POST['check_out'] ?? '';

$message = "";

// যদি ফর্ম সাবমিট করা হয়
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name  = trim($_POST['name']);
    $user_email = trim($_POST['email']);
    $user_phone = trim($_POST['phone']);
    $room_id    = intval($_POST['room_id']);
    $check_in   = $_POST['check_in'];
    $check_out  = $_POST['check_out'];

    // nights হিসাব করা
    $date1 = new DateTime($check_in);
    $date2 = new DateTime($check_out);
    $nights = $date1->diff($date2)->days;

    try {
        // 1. Room info বের করা (rooms টেবিল থেকে)
        $roomStmt = $db->prepare("SELECT price, total_rooms FROM rooms WHERE id = :room_id LIMIT 1");
        $roomStmt->execute([':room_id' => $room_id]);
        $roomData = $roomStmt->fetch(PDO::FETCH_ASSOC);

        if (!$roomData) {
            throw new Exception("Room not found.");
        }

        $room_price   = (float) $roomData['price'];
        $total_rooms  = (int) $roomData['total_rooms'];
        $total_price  = $nights * $room_price;

        // 2. ওই তারিখে কত বুকিং আছে চেক করা
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

        $alreadyBooked = (int) $checkAvailability->fetchColumn();

        // 3. compare with total_rooms
        if ($alreadyBooked >= $total_rooms) {
            $message = "<p class='text-red-600 text-center font-bold'>❌ Sorry, all {$total_rooms} rooms are already booked for the selected dates.</p>";
        } else {
            // 4. Insert booking
            $stmt = $db->prepare("
                INSERT INTO bookings 
                (room_id, user_name, user_email, user_phone, check_in, check_out, nights, total_price, status) 
                VALUES (:room_id, :user_name, :user_email, :user_phone, :check_in, :check_out, :nights, :total_price, 'pending')
            ");
            $stmt->execute([
                ':room_id'     => $room_id,
                ':user_name'   => $user_name,
                ':user_email'  => $user_email,
                ':user_phone'  => $user_phone,
                ':check_in'    => $check_in,
                ':check_out'   => $check_out,
                ':nights'      => $nights,
                ':total_price' => $total_price
            ]);

            $message = "<p class='text-green-600 text-center font-bold'>✅ Booking successful! Total price: <strong>{$total_price}</strong> (for {$nights} nights)</p>";
        }
    } catch (Exception $e) {
        $message = "<p class='text-red-600 text-center font-bold'>❌ Error: " . $e->getMessage() . "</p>";
    }
}
?>

<div class="flex justify-center items-center min-h-screen bg-gray-50 py-10">
  <form method="POST" class="w-full max-w-lg bg-white shadow-lg rounded-xl p-8 space-y-5">
    <input type="hidden" name="room_id" value="<?= $room_id ?>">

    <h2 class="text-2xl font-bold text-center text-gray-800">Book Your Stay</h2>

    <?= $message ?>

    <!-- Check In -->
    <div class="form-control">
      <label class="label">
        <span class="label-text font-semibold">Check-In</span>
      </label>
      <input type="date" name="check_in" value="<?= htmlspecialchars($check_in) ?>"
             class="input input-bordered w-full" required>
    </div>

    <!-- Check Out -->
    <div class="form-control">
      <label class="label">
        <span class="label-text font-semibold">Check-Out</span>
      </label>
      <input type="date" name="check_out" value="<?= htmlspecialchars($check_out) ?>"
             class="input input-bordered w-full" required>
    </div>

    <!-- Name -->
    <div class="form-control">
      <label class="label">
        <span class="label-text font-semibold">Your Name</span>
      </label>
      <input type="text" name="name" placeholder="Enter your name"
             class="input input-bordered w-full" required>
    </div>

    <!-- Email -->
    <div class="form-control">
      <label class="label">
        <span class="label-text font-semibold">Email</span>
      </label>
      <input type="email" name="email" placeholder="Enter your email"
             class="input input-bordered w-full" required>
    </div>

    <!-- Phone -->
    <div class="form-control">
      <label class="label">
        <span class="label-text font-semibold">Phone</span>
      </label>
      <input type="text" name="phone" placeholder="Enter your phone number"
             class="input input-bordered w-full" required>
    </div>

    <!-- Submit -->
    <div class="form-control mt-6">
      <button type="submit" class="btn btn-success w-full text-lg">Confirm Booking</button>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<?php
session_start();

require_once __DIR__ . '/../../admin/config/db_config.php';

$database = new Database();
$db = $database->db_connection();

// --------- LOGIN CHECK ---------
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../404.php");
    exit();
}

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

// -------- Fetch Services --------
$servicesStmt = $db->prepare("SELECT id, title, price FROM services ORDER BY id ASC");
$servicesStmt->execute();
$services = $servicesStmt->fetchAll(PDO::FETCH_ASSOC);

// -------- Form submit --------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name      = trim($_POST['name']);
    $user_email     = trim($_POST['email']);
    $user_phone     = trim($_POST['phone']);
    $room_id        = intval($_POST['room_id']);
    $check_in       = $_POST['check_in'];
    $check_out      = $_POST['check_out'];
    $transaction_id = trim($_POST['transaction_id'] ?? '');
    $user_id        = $_SESSION['user_id'];
    $selected_services = $_POST['services'] ?? [];
    $today          = date('Y-m-d');

    try {
        // -------- Date Validations --------
        if (empty($check_in) || empty($check_out)) {
            throw new Exception("Please select both check-in and check-out dates.");
        }
        if ($check_in < $today) {
            throw new Exception("Check-in date cannot be in the past.");
        }
        if ($check_out <= $check_in) {
            throw new Exception("Check-out date must be later than check-in date.");
        }

        // Nights calculation
        $date1 = new DateTime($check_in);
        $date2 = new DateTime($check_out);
        $nights = $date1->diff($date2)->days;

        // Room price load
        $roomStmt = $db->prepare("SELECT price, total_rooms FROM rooms WHERE id = :room_id LIMIT 1");
        $roomStmt->execute([':room_id' => $room_id]);
        $roomData = $roomStmt->fetch(PDO::FETCH_ASSOC);
        if (!$roomData) throw new Exception("Room not found.");

        $room_price     = (float)$roomData['price'];
        $total_rooms    = (int)$roomData['total_rooms'];
        $total_price    = $nights * $room_price;

        // -------- Extra Services Calculation --------
        $extra_price = 0;
        if (!empty($selected_services)) {
            $in  = str_repeat('?,', count($selected_services) - 1) . '?';
            $serviceStmt = $db->prepare("SELECT SUM(price) FROM services WHERE id IN ($in)");
            $serviceStmt->execute($selected_services);
            $extra_price = (float)$serviceStmt->fetchColumn();
        }
        $total_price += $extra_price;

        $advance_amount = $total_price * 0.20;

        // -------- Availability Check --------
        $checkAvailability = $db->prepare("
            SELECT COUNT(*) 
            FROM bookings 
            WHERE room_id = :room_id 
              AND status IN ('pending','approved') 
              AND (check_in < :check_out AND check_out > :check_in)
        ");
        $checkAvailability->execute([
            ':room_id'   => $room_id,
            ':check_in'  => $check_in,
            ':check_out' => $check_out
        ]);
        $alreadyBooked = (int)$checkAvailability->fetchColumn();

        if ($alreadyBooked >= $total_rooms) {
            throw new Exception("❌ Sorry, all {$total_rooms} rooms are already booked for the selected dates.");
        }

        // -------- Insert Booking --------
        $payment_status = 'checking';
        $stmt = $db->prepare("
            INSERT INTO bookings 
            (user_id, room_id, user_name, user_email, user_phone, check_in, check_out, nights, total_price, advance_amount, transaction_id, status, payment_status, extra_services) 
            VALUES (:user_id, :room_id, :user_name, :user_email, :user_phone, :check_in, :check_out, :nights, :total_price, :advance_amount, :transaction_id, 'pending', :payment_status, :extra_services)
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
            ':payment_status' => $payment_status,
            ':extra_services' => json_encode($selected_services)
        ]);

        $message = "<div class='alert alert-success shadow-lg my-4'>Booking successful! Payment status: <strong>{$payment_status}</strong></div>";
    } catch (Exception $e) {
        $message = "<div class='alert alert-error shadow-lg my-4'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Booking Form Section -->
<section class="py-10 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <!-- Form -->
                <div class="p-8 lg:p-12">
                    <form method="POST" class="space-y-2" id="bookingForm">
                        <input type="hidden" name="room_id" value="<?= $room_id ?>">

                        <?= $message ?>

                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Booking Information</h3>

                        <div class="form-control">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Your Name</label>
                            <input type="text" name="name" required class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-green-500">
                        </div>
                        <div class="form-control">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                            <input type="email" name="email" required class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-green-500">
                        </div>
                        <div class="form-control">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                            <input type="text" name="phone" required class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-green-500">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Check-In Date</label>
                                <input type="date" name="check_in" value="<?= htmlspecialchars($check_in) ?>" required class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-green-500" id="checkIn">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Check-Out Date</label>
                                <input type="date" name="check_out" value="<?= htmlspecialchars($check_out) ?>" required class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-green-500" id="checkOut">
                            </div>
                        </div>

                        <!-- Services Checkbox -->
                        <div class="mb-4">
                            <h4 class="text-lg font-semibold mb-2">Extra Services</h4>
                            <?php foreach ($services as $srv): ?>
                                <label class="flex items-center mb-2 space-x-2">
                                    <input type="checkbox" name="services[]" value="<?= $srv['id'] ?>" data-price="<?= $srv['price'] ?>" class="service-check">
                                    <span><?= htmlspecialchars($srv['title']) ?> (+<?= number_format($srv['price'], 0) ?> Tk)</span>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <!-- Payment Instruction -->
                        <div class="bg-gradient-to-br from-green-50 to-blue-50 p-4 rounded-xl border-l-4 border-green-500">
                            <h4 class="font-bold text-gray-900 mb-2">Payment Instructions</h4>
                            <p class="text-gray-700 mb-2 text-sm">
                                Please send <strong class="text-green-600" id="advanceAmount"><?= $room_price > 0 && !empty($check_in) && !empty($check_out) ? number_format(($room_price * (new DateTime($check_in))->diff(new DateTime($check_out))->days) * 0.20, 0) . ' Tk (20% advance)' : '20% advance payment' ?></strong> 
                                to <strong class="text-gray-900">017XXXXXXXX</strong>
                            </p>
                        </div>

                        <div class="form-control">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Transaction ID</label>
                            <input type="text" name="transaction_id" placeholder="Enter your bKash/Nagad Transaction ID" class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-green-500" required >
                        </div>

                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-lg text-lg transition-all duration-300">Confirm Booking</button>
                    </form>
                </div>

                <!-- Info Panel -->
                <div class="bg-gray-900 p-8 lg:p-12 text-white">
                    <h3 class="text-3xl font-bold mb-8">Why Choose The White Palace?</h3>
                    <p>Luxury service and comfort with 24/7 support.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkInInput = document.getElementById('checkIn');
    const checkOutInput = document.getElementById('checkOut');
    const advanceAmountElement = document.getElementById('advanceAmount');
    const roomPrice = <?= json_encode($room_price) ?>;
    const serviceCheckboxes = document.querySelectorAll('.service-check');

    function calculateAdvance() {
        const checkIn = checkInInput.value;
        const checkOut = checkOutInput.value;
        let extraServicePrice = 0;

        serviceCheckboxes.forEach(chk => {
            if (chk.checked) {
                extraServicePrice += parseFloat(chk.getAttribute('data-price')) || 0;
            }
        });

        if (checkIn && checkOut && roomPrice > 0) {
            const date1 = new Date(checkIn);
            const date2 = new Date(checkOut);
            const nights = (date2 - date1) / (1000 * 60 * 60 * 24);
            if (nights > 0) {
                const totalPrice = (nights * roomPrice) + extraServicePrice;
                const advanceAmount = totalPrice * 0.20;
                advanceAmountElement.textContent = `${Math.round(advanceAmount)} Tk (20% advance)`;
            } else {
                advanceAmountElement.textContent = '20% advance payment';
            }
        } else {
            advanceAmountElement.textContent = '20% advance payment';
        }
    }

    checkInInput.addEventListener('change', calculateAdvance);
    checkOutInput.addEventListener('change', calculateAdvance);
    serviceCheckboxes.forEach(chk => chk.addEventListener('change', calculateAdvance));
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
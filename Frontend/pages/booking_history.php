<?php
include '../includes/header.php';

// Start session if not already
if (session_status() === PHP_SESSION_NONE) session_start();

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../../admin/config/db_config.php';
$database = new Database();
$db = $database->db_connection();

// Enable PDO errors
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$userId = $_SESSION['user_id'];

try {
    $stmt = $db->prepare("SELECT * FROM bookings WHERE user_id = ? ORDER BY id DESC");
    $stmt->execute([$userId]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>

<div class="max-w-7xl mx-auto my-12 px-5 md:px-12">
    <h1 class="text-3xl font-bold mb-4 text-gray-800">My Bookings</h1>
    <div class="w-24 h-1 bg-gradient-to-r from-green-400 to-blue-500  mb-6 rounded"></div>

    <?php if (empty($bookings)): ?>
        <p class="text-gray-600">You haven't made any bookings yet.</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg shadow-md  lg:text-md text-sm">
                <thead>
                    <tr class="bg-green-500 text-white">
                        <th class="py-3 px-4 text-left">Date</th>
                        <th class="py-3 px-4 text-left">Name</th>
                        <th class="py-3 px-4 text-left">Phone</th>
                        <th class="py-3 px-4 text-left">Email</th>
                        <th class="py-3 px-4 text-left">Check-in</th>
                        <th class="py-3 px-4 text-left">Check-out</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-left">Payment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $b): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2 px-4"><?= htmlspecialchars($b['created_at'] ?? ''); ?></td>
                            <td class="py-2 px-4"><?= htmlspecialchars($b['user_name'] ?? ''); ?></td>
                            <td class="py-2 px-4"><?= htmlspecialchars($b['user_phone'] ?? ''); ?></td>
                            <td class="py-2 px-4"><?= htmlspecialchars($b['user_email'] ?? ''); ?></td>
                            <td class="py-2 px-4"><?= htmlspecialchars($b['check_in'] ?? ''); ?></td>
                            <td class="py-2 px-4"><?= htmlspecialchars($b['check_out'] ?? ''); ?></td>
                            
                            <!-- Status -->
                            <td class="py-2 px-4 text-xs">
                                <?php
                                $status = $b['status'] ?? 'pending';
                                switch ($status) {
                                    case 'approved':
                                        $statusClass = 'bg-green-100 text-green-700';
                                        $statusText = 'Approved';
                                        break;
                                    case 'rejected':
                                        $statusClass = 'bg-red-100 text-red-700';
                                        $statusText = 'Rejected';
                                        break;
                                    case 'complete':
                                        $statusClass = 'bg-blue-100 text-blue-700';
                                        $statusText = 'Complete';
                                        break;
                                    default:
                                        $statusClass = 'bg-yellow-100 text-yellow-700';
                                        $statusText = 'Pending';
                                }
                                echo "<span class='px-3 py-1 rounded-full text-sm $statusClass'>$statusText</span>";
                                ?>
                            </td>
                            
                            <!-- Payment Status -->
                            <td class="py-2 px-4 text-xs">
                                <?php
                                $paymentStatus = $b['payment_status'] ?? 'checking';
                                switch ($paymentStatus) {
                                    case 'paid':
                                        $payClass = 'bg-green-100 text-green-700';
                                        $payText = 'Paid';
                                        break;
                                    case '20%':
                                        $payClass = 'bg-blue-100 text-blue-700';
                                        $payText = '20% Paid';
                                        break;
                                    case 'checking':
                                    default:
                                        $payClass = 'bg-yellow-100 text-yellow-700';
                                        $payText = 'Checking';
                                }
                                echo "<span class='px-3 py-1 rounded-full text-sm $payClass'>$payText</span>";
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
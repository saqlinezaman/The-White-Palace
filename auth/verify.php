<?php
require_once __DIR__ . '/../admin/config/class.user.php';
$user = new User();

$message = '';

if (isset($_GET['token']) && isset($_GET['email'])) {
    $token = trim($_GET['token']);
    $email = trim($_GET['email']);

    try {
        if ($user->verify($email, $token)) {
            $message = '<div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                Your account has been verified successfully! You can now 
                <a href="login.php" class="text-green-500 font-semibold underline">login</a>.
            </div>';
        } else {
            $message = '<div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                Invalid or expired verification link.
            </div>';
        }
    } catch (Exception $e) {
        $message = '<div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">' 
                 . htmlspecialchars($e->getMessage()) . '</div>';
    }
} else {
    $message = '<div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
        No verification token provided.
    </div>';
}
?>

<?php include __DIR__ . '/../Frontend/includes/header.php'; ?>
<main class="bg-gray-100 min-h-screen flex items-center justify-center py-12 px-4">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md text-center">
        <h2 class="text-3xl font-bold mb-6 text-gray-900">Account Verification</h2>
        <?= $message ?>
    </div>
</main>
<?php include __DIR__ . '/../Frontend/includes/footer.php'; ?>

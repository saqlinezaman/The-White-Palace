<?php
require_once __DIR__ . '/../admin/config/class.user.php';
$user = new User();

$message = '';
$error = '';

// Get token and email from URL
$token = $_GET['token'] ?? '';
$email = $_GET['email'] ?? '';

if (!$token || !$email) {
    $error = "Invalid or expired reset link.";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $new_password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        if ($new_password !== $confirm_password) {
            throw new Exception("Passwords do not match.");
        }

        $user->resetPassword($email, $token, $new_password);
        $message = '<div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                        Password reset successful! You can now <a href="login.php" class="text-green-500 font-semibold underline">login</a>.
                    </div>';
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include __DIR__ . '/../Frontend/includes/header.php';
?>

<main class="bg-gray-100 min-h-screen flex items-center justify-center py-12 px-4">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-3xl font-bold mb-6 text-center text-gray-900">Reset Password</h2>

        <?php if ($message): ?>
            <?= $message ?>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!$message): ?>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-900 font-medium mb-1">New Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-900 font-medium mb-1">Confirm Password</label>
                <input type="password" name="confirm_password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <button type="submit"
                    class="w-full bg-green-500 text-white py-2 rounded font-semibold hover:bg-green-600 transition">
                Reset Password
            </button>
        </form>
        <?php endif; ?>

        <p class="text-center text-sm mt-4 text-gray-900">
            Remembered your password? 
            <a href="login.php" class="text-green-500 hover:underline">Login</a>
        </p>
    </div>
</main>

<?php include __DIR__ . '/../Frontend/includes/footer.php'; ?>

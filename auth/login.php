<?php
require_once __DIR__ . '/../admin/config/class.user.php';
$user = new User();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email    = trim($_POST['email']);
        $password = trim($_POST['password']);
        $user->login($email, $password);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<?php include __DIR__ . '/../Frontend/includes/header.php'; ?>

<main class="bg-gray-100 min-h-screen flex items-center justify-center py-12 px-4">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-3xl font-bold mb-6 text-center text-gray-900">Login</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-900 font-medium mb-1">Email</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-gray-900 font-medium mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <button type="submit"
                    class="w-full bg-green-500 text-white py-2 rounded font-semibold hover:bg-green-600 transition">
                Login
            </button>
        </form>

        <div class="mt-4 text-center text-sm text-gray-900">
            <a href="forgot.php" class="text-green-500 hover:underline">Forgot password?</a>
        </div>

        <p class="text-center text-sm mt-4 text-gray-900">
            Don’t have an account?
            <a href="register.php" class="text-green-500 hover:underline">Register</a>
        </p>
    </div>
</main>
<?php include __DIR__ . '/../Frontend/includes/footer.php'; ?>


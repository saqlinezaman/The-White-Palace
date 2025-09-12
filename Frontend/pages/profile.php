<?php
include '../includes/header.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../../admin/config/db_config.php';
$database = new Database();
$db = $database->db_connection();

$userId = $_SESSION['user_id'];

// Get current user info
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$msg = "";
$error = "";

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name            = trim($_POST['name']);
    $email           = trim($_POST['email']);
    $oldPassword     = trim($_POST['old_password']);
    $newPassword     = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Validate required fields
    if (empty($name) || empty($email)) {
        $error = "Name and Email cannot be empty!";
    } else {
        // check if email already exists for another user
        $checkEmail = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $checkEmail->execute([$email, $userId]);
        if ($checkEmail->rowCount() > 0) {
            $error = "This email is already taken!";
        } else {
            // Check if user is trying to update password
            $wantsPasswordChange = !empty($oldPassword) || !empty($newPassword) || !empty($confirmPassword);

            if ($wantsPasswordChange) {
                // Must validate all 3 fields
                if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
                    $error = "All password fields are required!";
                } elseif (!password_verify($oldPassword, $user['password'])) {
                    $error = "Old password is incorrect!";
                } elseif ($newPassword !== $confirmPassword) {
                    $error = "New password and confirm password do not match!";
                } else {
                    // Everything valid → update name, email, password
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $update = $db->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
                    $update->execute([$name, $email, $hashedPassword, $userId]);
                    $msg = "Profile and password updated successfully!";
                }
            } else {
                // User did not touch password → update only name & email
                $update = $db->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
                $update->execute([$name, $email, $userId]);
                $msg = "Profile updated successfully!";
            }
        }
    }

    // Reload updated info
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="max-w-3xl mx-auto my-12 px-5 md:px-12">
    <h1 class="text-3xl font-bold mb-4 text-gray-800">My Profile</h1>
    <div class="w-24 h-1 bg-gradient-to-r from-green-400 to-blue-500 mb-10 rounded"></div>

    <?php if (!empty($msg)): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $msg; ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-4 bg-white p-6 rounded-lg shadow-md">
        <div>
            <label class="block text-gray-700 font-medium">Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['username']); ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

            <div>
                <label class="block text-gray-700 font-medium">Old Password</label>
                <input type="password" name="old_password" placeholder="Enter old password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <p class="text-sm text-gray-600 my-3">Leave blank if you don't want to change password</p>
            </div>
            <div>
                <label class="block text-gray-700 font-medium">New Password</label>
                <input type="password" name="new_password" placeholder="Enter new password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm new password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

        <div>
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg shadow">
                Update Profile
            </button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>

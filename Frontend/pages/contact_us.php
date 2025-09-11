<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../../admin/config/db_config.php';

$database = new Database();
$db = $database->db_connection();

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        try {
            $stmt = $db->prepare("INSERT INTO contact (name, email, subject, message, is_replied, is_read, created_at) 
                                  VALUES (?, ?, ?, ?, 0, 0, NOW())");
            $stmt->execute([$name, $email, $subject, $message]);
            $success = "Your message has been sent successfully!";
        } catch (PDOException $e) {
            $error = "Something went wrong. Please try again.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<div class="max-w-7xl mx-auto my-12 px-5 md:px-12">
    <h1 class="text-4xl font-bold text-gray-800">Contact Us</h1>
    <div class="w-24 h-1 bg-gradient-to-r from-green-400 to-blue-500 rounded mb-6 mt-3"></div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <!-- Left: Contact Info -->
        <div class="space-y-6">
            <div class="bg-green-50 p-6 rounded-lg shadow-md flex items-center gap-4">
                <i class="fa-solid fa-phone text-green-600 text-2xl"></i>
                <div>
                    <h3 class="font-semibold text-gray-800">Phone</h3>
                    <p class="text-gray-700">+88 01745-654534</p>
                </div>
            </div>

            <div class="bg-green-50 p-6 rounded-lg shadow-md flex items-center gap-4">
                <i class="fa-solid fa-envelope text-green-600 text-2xl"></i>
                <div>
                    <h3 class="font-semibold text-gray-800">Email</h3>
                    <p class="text-gray-700">theWhitepalace@gmail.com</p>
                </div>
            </div>

            <div class="bg-green-50 p-6 rounded-lg shadow-md flex items-center gap-4">
                <i class="fa-solid fa-location-dot text-green-600 text-2xl"></i>
                <div>
                    <h3 class="font-semibold text-gray-800">Address</h3>
                    <p class="text-gray-700">Paola Castillo Avenida Juan, 82</p>
                </div>
            </div>
        </div>

        <!-- Right: Google Map -->
        <div class="rounded-lg overflow-hidden shadow-md h-[340px]">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d116819.89201728202!2d90.29544556550736!3d23.796484572753634!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8f63989f47d%3A0x63796a582ba98abd!2sCogent%20IT!5e0!3m2!1sen!2sbd!4v1757538859884!5m2!1sen!2sbd" 
                width="100%" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>

    <!-- Contact Form -->
    <div class="mt-12 bg-gray-50 p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-2">Send Us a Message</h2>
        <!-- Success / Error Messages -->
    <?php if ($success): ?>
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded"><?= $error ?></div>
    <?php endif; ?>
        <form action="" method="POST" class="space-y-4 mt-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Your Name</label>
                <input type="text" name="name" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Subject</label>
                <input type="text" name="subject" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Message</label>
                <textarea name="message" rows="5" class="w-full border rounded p-2" required></textarea>
            </div>
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">Send Message</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

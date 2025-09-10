<?php
include '../includes/header.php';
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

    <!-- FAQ Section -->
    <div class="mt-20 md:mx-40 shadow-lg p-8 rounded-lg bg-white">
        <h2 class="text-3xl font-semibold mb-6 text-gray-800 text-center">Frequently Asked Questions</h2>
        <div class="w-24 h-1 mx-auto bg-gradient-to-r from-green-400 to-blue-500 rounded mb-6 mt-3"></div>
        <div class="space-y-4">
            <div class="border-b border-gray-200">
                <button class="w-full text-left py-3 px-4 flex justify-between items-center focus:outline-none faq-btn">
                    <span class="font-medium text-gray-700">How can I book a room?</span>
                    <i class="fa-solid fa-chevron-down text-gray-500"></i>
                </button>
                <div class="faq-content hidden px-4 pb-3 text-gray-600">
                    You can book a room by selecting your desired room and dates on our website. You need to log in before booking.
                </div>
            </div>
            <div class="border-b border-gray-200">
                <button class="w-full text-left py-3 px-4 flex justify-between items-center focus:outline-none faq-btn">
                    <span class="font-medium text-gray-700">What payment methods are accepted?</span>
                    <i class="fa-solid fa-chevron-down text-gray-500"></i>
                </button>
                <div class="faq-content hidden px-4 pb-3 text-gray-600">
                    We accept all major credit/debit cards and online payment methods.
                </div>
            </div>
            <div class="border-b border-gray-200">
                <button class="w-full text-left py-3 px-4 flex justify-between items-center focus:outline-none faq-btn">
                    <span class="font-medium text-gray-700">Can I cancel my booking?</span>
                    <i class="fa-solid fa-chevron-down text-gray-500"></i>
                </button>
                <div class="faq-content hidden px-4 pb-3 text-gray-600">
                    Yes, you can cancel your booking according to our cancellation policy available on the booking page.
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Form -->
    <div class="mt-12 bg-gray-50 p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-4">Send Us a Message</h2>
        <form action="#" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Your Name</label>
                <input type="text" name="name" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" class="w-full border rounded p-2" required>
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

<!-- FAQ JS -->
<script>
    document.querySelectorAll('.faq-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const content = btn.nextElementSibling;
            content.classList.toggle('hidden');
            btn.querySelector('i').classList.toggle('rotate-180');
        });
    });
</script>

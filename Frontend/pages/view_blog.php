<?php 

require_once __DIR__ . '/../../admin/config/db_config.php';
require_once __DIR__ . '/../includes/header.php'; 

$database = new Database();
$db = $database->db_connection();

$blog_id = intval($_GET['id'] ?? 0);

$blog = null;
if ($blog_id) {
    $stmt = $db->prepare("SELECT * FROM blogs WHERE id = :id");
    $stmt->execute([':id' => $blog_id]);
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$blog) {
    echo "<div class='text-center py-20 text-red-500'>Blog not found!</div>";
    return;
}
?>

<div class="bg-gray-50">

    <!-- ================= Blog Hero Section ================= -->
    <section class="bg-gray-900 text-white py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-green-900/30"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block px-4 py-2 bg-green-500/20 text-green-400 rounded-full text-sm font-semibold mb-6 border border-green-500/30">
                <?= date('F j, Y', strtotime($blog['created_at'])) ?>
            </span>
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                <?= htmlspecialchars($blog['title']) ?>
            </h1>
            <div class="flex items-center justify-center space-x-4 text-gray-300">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Admin</span>
                </div>
                <span>•</span>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>5 min read</span>
                </div>
            </div>
        </div>
    </section>

   <!-- ================= Blog Content Section ================= -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Featured Image -->
        <div class="mb-12">
            <img src="<?php echo '../../admin/uploads/blogs/'.$blog['image'] ?>" 
                 alt="<?= $blog['title'] ?>"
                 class="w-full h-96 object-cover rounded-2xl shadow-2xl">
        </div>

        <!-- Blog Content -->
        <div class="prose prose-lg max-w-none">
            <!-- Description -->
            <div class="text-xl text-gray-600 leading-relaxed mb-8 font-medium">
                <?= $blog['description'] ?>  <!-- htmlspecialchars সরানো -->
            </div>
            <!-- Content -->
            <div class="text-gray-700 leading-relaxed space-y-6 text-lg">
                <?= $blog['content'] ?? $blog['description'] ?> <!-- htmlspecialchars সরানো -->
            </div>
        </div>

        <!-- Tags Section -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <div class="flex items-center space-x-4">
                <span class="text-gray-500 font-semibold">Tags:</span>
                <div class="flex space-x-2">
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">Hotel</span>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">Travel</span>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">Luxury</span>
                </div>
            </div>
        </div>

        <!-- Share Section -->
        <div class="mt-8 pt-8 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Share This Article</h3>
                <div class="flex space-x-4">
                    <!-- Twitter -->
                    <button class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-full transition-colors duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </button>
                    <!-- Facebook -->
                    <button class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full transition-colors duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </button>
                    <!-- WhatsApp -->
                    <button class="bg-green-500 hover:bg-green-600 text-white p-3 rounded-full transition-colors duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.106"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>


    <!-- ================= Related Articles Section ================= -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Related Articles</h2>
                <p class="text-xl text-gray-600">Discover more insights about luxury hospitality</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php 
                $relatedArticles = [
                    ['title' => 'Luxury Hotel Experience', 'image' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'date' => '2025-01-10'],
                    ['title' => 'Best Travel Destinations', 'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'date' => '2025-01-08'],
                    ['title' => 'Premium Room Services', 'image' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'date' => '2025-01-05']
                ];
                ?>
                <?php foreach ($relatedArticles as $article): ?>
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <div class="overflow-hidden">
                        <img src="<?= $article['image'] ?>" 
                             alt="<?= htmlspecialchars($article['title']) ?>"
                             class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-6">
                        <span class="text-sm text-gray-500"><?= date('F j, Y', strtotime($article['date'])) ?></span>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 mt-2 group-hover:text-green-500 transition-colors duration-300">
                            <?= htmlspecialchars($article['title']) ?>
                        </h3>
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            Discover amazing experiences and insights about luxury hospitality and premium services.
                        </p>
                        <a href="#" class="inline-flex items-center text-green-500 hover:text-green-600 font-semibold transition-colors duration-300">
                            Read More
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ================= Comments Section ================= -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Comments</h2>
                <p class="text-lg text-gray-600">Share your thoughts about this article</p>
            </div>

            <!-- Comment Form -->
            <div class="bg-gray-50 rounded-2xl p-8 mb-12">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Leave a Comment</h3>
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                            <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Your name">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Your email">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Comment</label>
                        <textarea rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Write your comment here..."></textarea>
                    </div>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                        Post Comment
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- ================= Call to Action Section ================= -->
    <section class="h-[60vh] bg-gradient-to-br from-green-600 via-green-500 to-gray-800 relative overflow-hidden flex items-center justify-center">
        <div class="absolute inset-0 bg-gradient-to-r from-green-700/40 to-black/20"></div>
        <div class="relative z-10 text-center text-white px-4 max-w-4xl mx-auto">
            <h2 class="text-3xl md:text-5xl font-bold mb-6 text-white">
                Experience The White Palace
            </h2>
            <p class="text-lg md:text-xl text-green-50 mb-8 max-w-2xl mx-auto leading-relaxed">
                Ready to experience luxury hospitality? Book your stay with us today
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mt-8">
                <a href="/rooms" class="inline-flex items-center bg-white hover:bg-gray-100 text-gray-900 px-10 py-4 rounded-full font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl min-w-[200px] justify-center">
                    Book Your Stay
                </a>
                <a href="/blogs" class="inline-flex items-center bg-transparent border-2 border-white text-white hover:bg-white hover:text-gray-900 px-10 py-4 rounded-full font-bold text-lg transition-all duration-300 transform hover:scale-105 min-w-[200px] justify-center">
                    More Articles
                </a>
            </div>
        </div>
    </section>

</div>


<?php require_once __DIR__ . '/../includes/footer.php';
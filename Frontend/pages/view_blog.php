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
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

// check login status (example, তোমার auth অনুযায়ী বদলাবে)
$isLoggedIn = isset($_SESSION['user_id']);
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Admin</span>
                </div>
                <span>•</span>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                <img src="<?='../../admin/uploads/blogs/' . $blog['image']?>" 
                     alt="<?= htmlspecialchars($blog['title']) ?>"
                     class="w-full h-96 object-cover rounded-2xl shadow-2xl">
            </div>

            <!-- Blog Content -->
            <div class="prose prose-lg max-w-none">
                <div class="text-xl text-gray-600 leading-relaxed mb-8 font-medium">
                    <?= $blog['description'] ?>
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
                        <a target="_blank" href="https://twitter.com/intent/tweet?url=<?=urlencode('http://yoursite.com/view_blog.php?id='.$blog_id)?>"
                           class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-full transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656..."/>
                            </svg>
                        </a>
                        <!-- Facebook -->
                        <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?=urlencode('http://yoursite.com/view_blog.php?id='.$blog_id)?>"
                           class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12..."/>
                            </svg>
                        </a>
                        <!-- WhatsApp -->
                        <a target="_blank" href="https://api.whatsapp.com/send?text=<?=urlencode($blog['title'].' - http://yoursite.com/view_blog.php?id='.$blog_id)?>"
                           class="bg-green-500 hover:bg-green-600 text-white p-3 rounded-full transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758..."/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= Comments Section ================= -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Comments</h2>
                <p class="text-lg text-gray-600">Share your thoughts and read what others have to say</p>
                <div class="w-40 h-1 bg-gradient-to-r from-green-400 to-blue-500 mx-auto mt-4 mb-8 rounded"></div>
            </div>

            <?php if ($isLoggedIn): ?>
            <div class="bg-gray-50 rounded-2xl p-8 mb-12">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Leave a Comment</h3>
                <form id="commentForm" class="space-y-6">
                    <textarea id="commentText" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl 
                              focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                              placeholder="Write your comment here..." required></textarea>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-xl 
                               font-semibold transition-all duration-300 transform hover:scale-105">
                        Post Comment
                    </button>
                </form>
            </div>
            <?php else: ?>
                <p class="text-center text-gray-600 mb-6">Please 
                    <a href="../../auth/login.php" class="text-green-600 underline">login</a> to leave a comment.
                </p>
            <?php endif; ?>

            <div class="my-5">
                <h2 class="text-3xl font-bold">Comments</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-green-400 to-blue-500 mt-4 mb-8 rounded"></div>
            </div>

            <div id="commentsContainer" class="space-y-6"></div>
            <div id="paginationContainer" class="mt-6 flex justify-center space-x-2"></div>
        </div>
    </section>
</div>

<script>
const blogId = <?= $blog_id; ?>;
let currentPage = 1;

function loadComments(page = 1) {
    currentPage = page;
    fetch(`comments_ajax.php?blog_id=${blogId}&page=${page}`)
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('commentsContainer');
            const pagination = document.getElementById('paginationContainer');
            container.innerHTML = '';
            pagination.innerHTML = '';

            if(data.comments.length === 0){
                container.innerHTML = '<p class="text-gray-600">No comments yet. Be the first to comment!</p>';
            }

            data.comments.forEach(c => {
                const div = document.createElement('div');
                div.className = 'bg-white p-6 rounded-2xl shadow-lg border border-gray-100';
                div.innerHTML = `
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold">${c.username.charAt(0).toUpperCase()}</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h4 class="font-bold text-gray-900">${c.username}</h4>
                                    <p class="text-gray-500 text-sm">${c.created_at}</p>
                                </div>
                                ${c.canEdit ? `
                                <div class="flex gap-2">
                                    <button onclick="editComment(${c.id}, this)" class="text-blue-600 hover:underline text-sm">Edit</button>
                                    <button onclick="deleteComment(${c.id})" class="text-red-600 hover:underline text-sm">Delete</button>
                                </div>` : ''}
                            </div>
                            <p class="text-gray-700 leading-relaxed" id="commentText-${c.id}">${c.comment}</p>
                            <div class="mt-2"><button onclick="replyComment(${c.id}, this, null)" class="text-green-600 hover:underline text-sm">Reply</button></div>
                            <div id="replies-${c.id}" class="ml-6 mt-2"></div>
                        </div>
                    </div>
                `;
                container.appendChild(div);

                // render replies with reply option
                const replyDiv = div.querySelector(`#replies-${c.id}`);
                c.replies.forEach(r => {
                    const rDiv = document.createElement('div');
                    rDiv.className = 'bg-gray-50 p-4 rounded-xl mb-2';
                    rDiv.innerHTML = `
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-green-400 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-white font-bold">${r.username.charAt(0).toUpperCase()}</span>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center space-x-2">
                                        <h5 class="font-medium text-gray-800">${r.username}</h5>
                                        <p class="text-xs text-gray-500">${r.created_at}</p>
                                    </div>
                                    ${r.canEdit ? `
                                    <div class="flex gap-2">
                                        <button onclick="editComment(${r.id}, this)" class="text-blue-600 hover:underline text-sm">Edit</button>
                                        <button onclick="deleteComment(${r.id})" class="text-red-600 hover:underline text-sm">Delete</button>
                                    </div>` : ''}
                                </div>
                                <p class="text-gray-700 text-sm" id="commentText-${r.id}">${r.comment}</p>
                                <div class="mt-1"><button onclick="replyComment(${c.id}, this, ${r.id})" class="text-green-600 hover:underline text-sm">Reply</button></div>
                            </div>
                        </div>
                    `;
                    replyDiv.appendChild(rDiv);
                });
            });

            for(let i=1; i<=data.totalPages; i++){
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = `px-3 py-1 rounded-lg ${i===data.currentPage?'bg-green-500 text-white':'bg-gray-200 text-gray-700 hover:bg-gray-300'}`;
                btn.onclick = () => loadComments(i);
                pagination.appendChild(btn);
            }
        })
        .catch(error => console.error('Error loading comments:', error));
}

// Submit comment
document.getElementById('commentForm')?.addEventListener('submit', e => {
    e.preventDefault();
    const comment = document.getElementById('commentText').value.trim();
    if (!comment) return;

    fetch('comments_ajax.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({blog_id: blogId, comment: comment})
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('commentText').value = '';
            loadComments(currentPage);
        } else {
            alert(data.msg);
        }
    })
    .catch(error => console.error('Error posting comment:', error));
});

// Reply
function replyComment(parentId, btn, replyToId) {
    let replyBox = document.getElementById(`replyBox-${parentId}-${replyToId || 'main'}`);
    if (replyBox) {
        replyBox.remove(); // Remove existing reply box to allow multiple replies
    }
    replyBox = document.createElement('div');
    replyBox.id = `replyBox-${parentId}-${replyToId || 'main'}`;
    replyBox.className = 'mt-2';
    let defaultText = '';
    if (replyToId) {
        // Fetch the username from the replies data
        const replyElement = document.querySelector(`#commentText-${replyToId}`).closest('.bg-gray-50').querySelector('h5');
        const replyToUsername = replyElement ? replyElement.textContent : 'Unknown';
        defaultText = `@${replyToUsername} `;
    }
    replyBox.innerHTML = `<textarea id="replyText-${parentId}-${replyToId || 'main'}" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-xl 
                     focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Write your reply...">${defaultText}</textarea>
                     <button class="bg-green-500 text-white px-4 py-2 rounded mt-1" onclick="sendReply(${parentId}, ${replyToId})">Reply</button>`;
    btn.parentNode.appendChild(replyBox);
}

function sendReply(parentId, replyToId) {
    const text = document.getElementById(`replyText-${parentId}-${replyToId || 'main'}`).value.trim();
    if (!text) return;

    fetch('comments_ajax.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({blog_id: blogId, comment: text, parent_id: parentId, reply_to_id: replyToId})
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadComments(currentPage);
            const replyBox = document.getElementById(`replyBox-${parentId}-${replyToId || 'main'}`);
            if (replyBox) replyBox.remove();
        } else {
            alert(data.msg);
        }
    })
    .catch(error => console.error('Error sending reply:', error));
}

// Edit & Delete
function editComment(id, btn) {
    const textP = document.getElementById(`commentText-${id}`);
    const oldText = textP.textContent;
    const textarea = document.createElement('textarea');
    textarea.className = 'w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent';
    textarea.value = oldText;
    textP.replaceWith(textarea);
    btn.textContent = 'Save';
    btn.onclick = () => { 
        fetch('comments_ajax.php', {
            method: 'PUT',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id, comment: textarea.value})
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) loadComments(currentPage);
            else alert(data.msg);
        })
        .catch(error => console.error('Error editing comment:', error));
    };
}

function deleteComment(id) {
    if (!confirm('Are you sure you want to delete this comment?')) return;
    fetch('comments_ajax.php', {
        method: 'DELETE',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id})
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) loadComments(currentPage);
        else alert(data.msg);
    })
    .catch(error => console.error('Error deleting comment:', error));
}

loadComments();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
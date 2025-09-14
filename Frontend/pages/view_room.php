<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../admin/config/db_config.php';
$database = new Database();
$db_connection = $database->db_connection();

// Room ID check
$roomId = intval($_GET['id'] ?? 0);
if ($roomId <= 0) { echo "<p>Invalid Room ID.</p>"; exit; }

// Check-in & Check-out
$checkIn = $_GET['check_in'] ?? date('Y-m-d');
$checkOut = $_GET['check_out'] ?? date('Y-m-d', strtotime($checkIn . ' +1 day'));

// Fetch room info
$stmt = $db_connection->prepare("
    SELECT r.*, c.room_type as category_name,
    (r.total_rooms - (
        SELECT COUNT(*) FROM bookings b
        WHERE b.room_id = r.id
          AND b.status IN ('pending','approved')
          AND NOT (b.check_out <= ? OR b.check_in >= ?)
    )) AS available_rooms
    FROM rooms r
    JOIN categories c ON r.category_id = c.id
    WHERE r.id = ?
");
$stmt->execute([$checkIn, $checkOut, $roomId]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$room) { echo "<p>Room not found.</p>"; exit; }

$galleryImages = [];
if (!empty($room['gallery_images'])) $galleryImages = json_decode($room['gallery_images'], true);

$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

require_once '../includes/header.php';
?>

<div class="max-w-7xl mx-auto my-10 px-5 md:px-12">
    <!-- Main section: Room details + booking -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="space-y-4">
            <div class="rounded-lg overflow-hidden shadow-md">
                <img src="../../<?= $room['image_url']; ?>" 
                     alt="<?= htmlspecialchars($room['name']); ?>" 
                     class="w-full h-96 object-cover">
            </div>

            <!-- Gallery -->
            <?php if (!empty($galleryImages)): ?>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    <?php foreach ($galleryImages as $image): ?>
                        <?php $galleryPath = '/thewhitepalace/' . trim($image); ?>
                        <div class="overflow-hidden rounded-lg shadow-md">
                            <img src="<?= $galleryPath ?>" 
                                 alt="<?= htmlspecialchars($room['name']); ?>" 
                                 class="w-full h-24 object-cover transition-transform duration-300 hover:scale-105">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500">No gallery images available.</p>
            <?php endif; ?>
        </div>

        <div class="space-y-6">
            <h1 class="text-4xl font-bold text-gray-800"><?= htmlspecialchars($room['name']); ?></h1>

            <div class="flex flex-wrap gap-3 items-center">
                <span class="bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded">
                    <?= htmlspecialchars($room['category_name']); ?>
                </span>
                <span class="bg-blue-100 text-blue-700 text-sm px-3 py-1 rounded">
                    Capacity: <?= $room['total_rooms']; ?>
                </span>
                <?php if ($room['available_rooms'] > 0): ?>
                    <span class="bg-green-100 text-green-700 text-sm px-3 py-1 rounded">
                        <?= $room['available_rooms']; ?> Available
                    </span>
                <?php else: ?>
                    <span class="bg-red-100 text-red-700 text-sm px-3 py-1 rounded">
                        No room available
                    </span>
                <?php endif; ?>
            </div>

            <p class="text-2xl font-bold text-green-600">৳<?= $room['price']; ?>/night</p>

            <?php if (!empty($room['amenities'])): ?>
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-800 mb-3">Amenities:</h4>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach (json_decode($room['amenities'], true) as $amenity): ?>
                            <span class="bg-blue-700 text-white px-3 py-1 rounded-full text-sm font-medium">
                                <?= htmlspecialchars($amenity) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?= !$isLoggedIn ? '../../auth/login.php' : 'book_room.php' ?>" method="GET" class="space-y-4 bg-gray-50 p-5 rounded-lg shadow-md">
                <input type="hidden" name="room_id" value="<?= $room['id']; ?>">
                <?php if ($isLoggedIn): ?>
                    <div class="hidden">
                        <label class="block text-sm font-medium text-gray-700">Check In</label>
                        <input type="date" name="check_in" value="<?= htmlspecialchars($checkIn); ?>" class="w-full border rounded p-2">
                    </div>
                    <div class="hidden">
                        <label class="block text-sm font-medium text-gray-700">Check Out</label>
                        <input type="date" name="check_out" value="<?= htmlspecialchars($checkOut); ?>" class="w-full border rounded p-2">
                    </div>
                <?php endif; ?>
                <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition"
                        <?= $isLoggedIn && $room['available_rooms'] <= 0 ? 'disabled' : ''; ?>>
                    <?= !$isLoggedIn ? 'Login to Book Room' : ($room['available_rooms'] > 0 ? 'Book Now' : 'Unavailable') ?>
                </button>
            </form>
        </div>
    </div>

    <!-- Description -->
    <div class="mt-12 bg-gray-50 p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-3">Room Details</h2>
        <p class="text-gray-700"><?= $room['description']; ?></p>
    </div>
</div>

<!-- Comments Section -->
<div class="bg-gray-50">
<section class="py-20 bg-white">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="text-center mb-12">
    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Comments</h2>
    <p class="text-lg text-gray-600">Share your thoughts and read what others have to say</p>
     <div class="w-40 h-1 bg-gradient-to-r from-green-400 to-blue-500 mx-auto mt-4  mb-8 rounded"></div>
</div>

<?php if ($isLoggedIn): ?>
<div class="bg-gray-50 rounded-2xl p-8 mb-12">
<h3 class="text-xl font-bold text-gray-900 mb-6">Leave a Comment</h3>
<form id="commentForm" class="space-y-6">
    <textarea id="commentText" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Write your comment here..." required></textarea>
    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">Post Comment</button>
</form>
</div>
<?php else: ?>
<p class="text-center text-gray-600 mb-6">Please <a href="../../auth/login.php" class="text-green-600 underline">login</a> to leave a comment.</p>
<?php endif; ?>

<div class="my-5">
    <h2 class="text-3xl font-bold" >Comments</h2>
     <div class="w-24 h-1 bg-gradient-to-r from-green-400 to-blue-500  mt-4 mb-8 rounded"></div>
</div>

<div id="commentsContainer" class="space-y-6"></div>
<div id="paginationContainer" class="mt-6 flex justify-center space-x-2"></div>
</div>
</section>
</div>

<script>
const roomId = <?= $roomId; ?>;
let currentPage = 1;

function loadComments(page = 1) {
    currentPage = page;
    fetch(`comments_ajax.php?room_id=${roomId}&page=${page}`)
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

// Main comment submit
document.getElementById('commentForm')?.addEventListener('submit', e => {
    e.preventDefault();
    const comment = document.getElementById('commentText').value.trim();
    if (!comment) return;
    fetch('comments_ajax.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({room_id: roomId, comment})
    }).then(res => res.json()).then(data => {
        if (data.success) {
            document.getElementById('commentText').value = '';
            loadComments(currentPage);
        } else alert(data.msg);
    });
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
        // Fetch the username from the reply's data
        const replyElement = document.querySelector(`#commentText-${replyToId}`).closest('.bg-gray-50').querySelector('h5');
        const replyToUsername = replyElement ? replyElement.textContent : 'Unknown';
        defaultText = `@${replyToUsername} `;
    } else {
        // Fetch the username from the main comment's <h4> tag
        const commentElement = btn.closest('.bg-white').querySelector('h4');
        const replyToUsername = commentElement ? commentElement.textContent : 'Unknown';
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
        body: JSON.stringify({room_id: roomId, comment: text, parent_id: parentId, reply_to_id: replyToId})
    }).then(res => res.json()).then(data => {
        if (data.success) {
            loadComments(currentPage);
            const replyBox = document.getElementById(`replyBox-${parentId}-${replyToId || 'main'}`);
            if (replyBox) replyBox.remove();
        } else alert(data.msg);
    });
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

<?php require_once '../includes/footer.php'; ?>
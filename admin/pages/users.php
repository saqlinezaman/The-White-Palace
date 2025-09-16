<?php
require_once __DIR__ . '/../config/db_config.php';

$database = new Database();
$db = $database->db_connection();

// Block / Unblock handle
if (isset($_GET['action'], $_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($id > 0) {
        if ($action === 'block') {
            $stmt = $db->prepare("UPDATE users SET status = 1 WHERE id = ?");
            $stmt->execute([$id]);
        } elseif ($action === 'unblock') {
            $stmt = $db->prepare("UPDATE users SET status = 0 WHERE id = ?");
            $stmt->execute([$id]);
        }
    }
    echo '<script>window.location.href = "index.php?page=users";</script>';
    exit;
}

// Pagination setup
$limit = 15;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// page minimum 1 হবে
if ($page < 1) {
    $page = 1;
}

$start = ($page - 1) * $limit;

// Total count
$totalStmt = $db->query("SELECT COUNT(*) as total FROM users");
$total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
$pages = ceil($total / $limit);

// যদি start বড় হয় total এর চেয়ে, তাহলে page আবার 1 করে দাও
if ($start >= $total) {
    $page = 1;
    $start = 0;
}

$stmt = $db->prepare("SELECT * FROM users ORDER BY id DESC LIMIT :start, :limit");
$stmt->bindValue(":start", (int)$start, PDO::PARAM_INT);
$stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php require_once __DIR__.'/../includes/header.php'; ?>

<!-- Users Section -->
<section class="p-4">
    <h2 class="mb-4">Users</h2>

    <input type="text" id="search" class="form-control mb-3" placeholder="Search by username or email">

    <table class="table table-bordered table-striped" id="userTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <?= $user['status'] == 1 ? '<span class="badge bg-danger">Blocked</span>' : '<span class="badge bg-success">Active</span>' ?>
                </td>
                <td><?= htmlspecialchars($user['created_at']) ?></td>
                <td>
                    <?php if ($user['status'] == 0): ?>
                        <a href="index.php?page=users&action=block&id=<?= $user['id'] ?>" class="btn btn-sm btn-danger">Block</a>
                    <?php else: ?>
                        <a href="index.php?page=users&action=unblock&id=<?= $user['id'] ?>" class="btn btn-sm btn-success">Unblock</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav>
      <ul class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="?page=users&page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
      </ul>
    </nav>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $("#search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#userTable tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>

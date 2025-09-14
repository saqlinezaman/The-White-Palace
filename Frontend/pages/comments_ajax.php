<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../admin/config/db_config.php';

$database = new Database();
$db = $database->db_connection();

$method = $_SERVER['REQUEST_METHOD'];
$userId = $_SESSION['user_id'] ?? 0;

// ---------- GET: fetch comments + replies ----------
if ($method === 'GET') {
    $roomId = intval($_GET['room_id'] ?? 0);
    $blogId = intval($_GET['blog_id'] ?? 0);
    $page   = max(1, intval($_GET['page'] ?? 1));
    $perPage = 5;
    $offset = ($page - 1) * $perPage;

    if (!$roomId && !$blogId) {
        echo json_encode(['comments'=>[], 'totalPages'=>0, 'currentPage'=>$page]);
        exit;
    }

    $where = $roomId ? "c.room_id=:id" : "c.blog_id=:id";
    $params = [':id' => $roomId ?: $blogId];

    $sql = "SELECT c.*, u.username 
            FROM comments c 
            JOIN users u ON c.user_id = u.id 
            WHERE $where AND c.parent_id IS NULL 
            ORDER BY c.created_at DESC 
            LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    foreach ($params as $k=>$v) {
        $stmt->bindValue($k, $v, PDO::PARAM_INT);
    }
    $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $countStmt = $db->prepare("SELECT COUNT(*) FROM comments c WHERE $where AND parent_id IS NULL");
    $countStmt->execute($params);
    $total = $countStmt->fetchColumn();
    $totalPages = ceil($total / $perPage);

    foreach ($comments as &$c) {
        $c['canEdit'] = ($userId && $userId == $c['user_id']);
        $c['created_at'] = date("M d, Y H:i", strtotime($c['created_at']));
        $c['comment'] = htmlspecialchars($c['comment']);

        // replies
        $rStmt = $db->prepare("SELECT c.*, u.username 
                               FROM comments c 
                               JOIN users u ON c.user_id=u.id 
                               WHERE parent_id=? 
                               ORDER BY created_at ASC");
        $rStmt->execute([$c['id']]);
        $replies = $rStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($replies as &$r) {
            $r['canEdit'] = ($userId && $userId == $r['user_id']);
            $r['created_at'] = date("M d, Y H:i", strtotime($r['created_at']));
            $r['comment'] = htmlspecialchars($r['comment']);
        }
        $c['replies'] = $replies;
    }

    echo json_encode([
        'comments' => $comments, 
        'totalPages' => $totalPages, 
        'currentPage' => $page
    ]);
    exit;
}

// ---------- Require login for POST/PUT/DELETE ----------
if (!$userId) {
    echo json_encode(['success'=>false,'msg'=>'Login required']); 
    exit;
}

// ---------- POST: add comment ----------
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $roomId = intval($input['room_id'] ?? 0);
    $blogId = intval($input['blog_id'] ?? 0);
    $comment = trim($input['comment'] ?? '');
    $parent_id = isset($input['parent_id']) ? intval($input['parent_id']) : null;

    if ((!$roomId && !$blogId) || !$comment) { 
        echo json_encode(['success'=>false,'msg'=>'Invalid data']); 
        exit; 
    }

    try {
        $stmt = $db->prepare("INSERT INTO comments 
            (room_id, blog_id, user_id, comment, parent_id, created_at) 
            VALUES (:room_id, :blog_id, :user_id, :comment, :parent_id, NOW())");

        $stmt->bindValue(':room_id', $roomId ?: null, $roomId ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':blog_id', $blogId ?: null, $blogId ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindValue(':parent_id', $parent_id, PDO::PARAM_INT);

        $stmt->execute();
        echo json_encode(['success'=>true]);
    } catch (PDOException $e) {
        echo json_encode(['success'=>false,'msg'=>'Error: ' . $e->getMessage()]);
    }
    exit;
}

// ---------- PUT: edit ----------
if ($method === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = intval($input['id'] ?? 0);
    $comment = trim($input['comment'] ?? '');

    if (!$id || !$comment) { 
        echo json_encode(['success'=>false,'msg'=>'Invalid data']); 
        exit; 
    }

    $stmt = $db->prepare("UPDATE comments SET comment=? WHERE id=? AND user_id=?");
    $stmt->execute([$comment, $id, $userId]);
    echo json_encode(['success'=>true]);
    exit;
}

// ---------- DELETE: remove ----------
if ($method === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = intval($input['id'] ?? 0);

    if (!$id) { 
        echo json_encode(['success'=>false,'msg'=>'Invalid data']); 
        exit; 
    }

    $stmt = $db->prepare("DELETE FROM comments WHERE id=? AND user_id=?");
    $stmt->execute([$id, $userId]);
    echo json_encode(['success'=>true]);
    exit;
}
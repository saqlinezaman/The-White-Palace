<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../admin/config/db_config.php';
$database = new Database();
$db = $database->db_connection();

$method = $_SERVER['REQUEST_METHOD'];
$userId = $_SESSION['user_id'] ?? 0;

// GET: fetch comments + replies with pagination
if($method==='GET'){
    $roomId = intval($_GET['room_id'] ?? 0);
    $page = max(1,intval($_GET['page'] ?? 1));
    $perPage = 5;
    $offset = ($page-1)*$perPage;

    // fetch main comments (parent_id IS NULL)
    $stmt = $db->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id=u.id WHERE c.room_id=? AND c.parent_id IS NULL ORDER BY c.created_at DESC LIMIT ? OFFSET ?");
    $stmt->bindValue(1,$roomId,PDO::PARAM_INT);
    $stmt->bindValue(2,$perPage,PDO::PARAM_INT);
    $stmt->bindValue(3,$offset,PDO::PARAM_INT);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $countStmt = $db->prepare("SELECT COUNT(*) FROM comments WHERE room_id=? AND parent_id IS NULL");
    $countStmt->execute([$roomId]);
    $total = $countStmt->fetchColumn();
    $totalPages = ceil($total/$perPage);

    foreach($comments as &$c){
        $c['canEdit'] = ($userId && $userId==$c['user_id']);
        $c['created_at'] = date("M d, Y H:i", strtotime($c['created_at']));
        $c['comment'] = htmlspecialchars($c['comment']);

        // fetch replies
        $rStmt = $db->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id=u.id WHERE parent_id=? ORDER BY created_at ASC");
        $rStmt->execute([$c['id']]);
        $replies = $rStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($replies as &$r){
            $r['canEdit'] = ($userId && $userId==$r['user_id']);
            $r['created_at'] = date("M d, Y H:i", strtotime($r['created_at']));
            $r['comment'] = htmlspecialchars($r['comment']);
        }
        $c['replies'] = $replies;
    }

    echo json_encode(['comments'=>$comments,'totalPages'=>$totalPages,'currentPage'=>$page]);
    exit;
}

// Require login for POST, PUT, DELETE
if(!$userId) { echo json_encode(['success'=>false,'msg'=>'Login required']); exit; }

// POST: add comment or reply
if($method==='POST'){
    $input = json_decode(file_get_contents('php://input'), true);
    $roomId = intval($input['room_id'] ?? 0);
    $comment = trim($input['comment'] ?? '');
    $parent_id = isset($input['parent_id']) ? intval($input['parent_id']) : NULL;

    if(!$roomId || !$comment){ echo json_encode(['success'=>false,'msg'=>'Invalid data']); exit; }

    $stmt = $db->prepare("INSERT INTO comments (room_id,user_id,comment,parent_id,created_at) VALUES (?,?,?,?,NOW())");
    $stmt->execute([$roomId,$userId,$comment,$parent_id]);
    echo json_encode(['success'=>true]);
    exit;
}

// PUT: edit comment/reply
if($method==='PUT'){
    $input = json_decode(file_get_contents('php://input'), true);
    $id = intval($input['id'] ?? 0);
    $comment = trim($input['comment'] ?? '');
    if(!$id || !$comment){ echo json_encode(['success'=>false,'msg'=>'Invalid data']); exit; }

    $stmt = $db->prepare("UPDATE comments SET comment=? WHERE id=? AND user_id=?");
    $stmt->execute([$comment,$id,$userId]);
    echo json_encode(['success'=>true]);
    exit;
}

// DELETE: delete comment/reply
if($method==='DELETE'){
    $input = json_decode(file_get_contents('php://input'), true);
    $id = intval($input['id'] ?? 0);
    if(!$id){ echo json_encode(['success'=>false,'msg'=>'Invalid data']); exit; }

    $stmt = $db->prepare("DELETE FROM comments WHERE id=? AND user_id=?");
    $stmt->execute([$id,$userId]);
    echo json_encode(['success'=>true]);
    exit;
}

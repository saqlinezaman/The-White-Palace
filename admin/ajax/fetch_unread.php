<?php
require_once __DIR__ . "/../config/db_config.php";
$database = new Database();
$db = $database->db_connection();

$unread = $db->query("SELECT COUNT(*) FROM contact WHERE is_read=0")->fetchColumn();
header("Content-Type: application/json");
echo json_encode(["unread" => $unread]);

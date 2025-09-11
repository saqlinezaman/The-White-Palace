<?php
if(session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

if(empty($_SESSION['admin_logged_in'])) 
	{ 
		echo json_encode(['count' => 0]);
		exit; 
	}

	require __DIR__ .'/../../config/db_config.php';

	$database = new Database();
	$conn = $database->db_connection();

	$row = $conn->query("SELECT COUNT(*) AS c FROM contact_message WHERE is_read = 0")->fetch(PDO::FETCH_ASSOC);


	echo json_encode(['count' => (int)($row['c'] ?? 0 )]);




 
?>
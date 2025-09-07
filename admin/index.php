<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])){
    header('Location: login.php');
    exit;
}

include __DIR__.'/includes/header.php';
include __DIR__.'/includes/sidebar.php';

$page = $_GET['page'] ?? 'dashboard';
$pagePath = "pages/".basename($page).".php";

if(file_exists($pagePath)){
    include $pagePath;
} else {
    echo "<h2 class='text-center mt-5'>Page not found.</h2>";
}

?>

   
    
<?php include __DIR__.'/includes/footer.php'; ?>
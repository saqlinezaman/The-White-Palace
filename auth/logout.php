<?php
session_start();

// Destroy all sessions
$_SESSION = [];
session_destroy();

// Redirect to login page
header("Location: login.php");
exit;

<?php

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page
// header("Location: index.php?page=login");
echo '<script>window.location.href"admin/login.php"</script>';
exit();
?>

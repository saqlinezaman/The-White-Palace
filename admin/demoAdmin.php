<?php 

require_once('config/db_config.php');
$database = new Database();
$db_connection = $database->$db_connection();

// admin credentials
$username = 'admin';
$password = '123';

$query = "SELECT * FROM admins WHERE username = ?" ;
$stmt = $db_connection->prepare($query);
$stmt->execute([$username]);
if($stmt->rowCount() > 0){
    echo "<h2>Admin user already exists. No changes made.</h2>";
}else{
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $insertQuery = "INSERT INTO admins (username, password) VALUES (?, ?)";
    $insertStmt = $db_connection->prepare($insertQuery);
    if($insertStmt->execute([$username, $hashed_password])){
        echo "<h2>Admin user created successfully.</h2>";
        echo "<p>Username: <strong>$username</strong></p>";
        echo "<p>Password: <strong>$password</strong></p>";
        echo "<a href='login.php'>Go to Login Page</a>";
    } else {
        echo "<h2>Error creating admin user.</h2>";
    }
}

?>

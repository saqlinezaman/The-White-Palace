<?php

if(!isset($_SESSION['admin_logged_in'])){
    header('Location: ../login.php');
    exit;
}

require_once __DIR__.'/../config/db_config.php';

// Database connection
$database = new Database();
$db = $database->db_connection();

// Fetch current admin data
$admin_id = 1; // চাইলে session থেকে dynamic করা যাবে
$stmt = $db->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

$success = '';
$error = '';

// Handle profile update
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    // Username update
    $new_username = $_POST['username'] ?? $admin['username'];

    // Profile image upload
    if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0){
        $allowed_ext = ['jpg','jpeg','png','gif'];
        $file_name = $_FILES['profile_image']['name'];
        $file_tmp = $_FILES['profile_image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if(in_array($file_ext, $allowed_ext)){
            $upload_dir = __DIR__.'/../uploads/profile/';
            if(!is_dir($upload_dir)){
                mkdir($upload_dir, 0777, true);
            }

            // Delete old image if not default
            if(!empty($admin['profile_image']) && $admin['profile_image'] != 'default.png'){
                $old_image_path = $upload_dir . $admin['profile_image'];
                if(file_exists($old_image_path)){
                    unlink($old_image_path);
                }
            }

            $new_file_name = 'admin_'.$admin_id.'_'.time().'.'.$file_ext;
            $destination = $upload_dir . $new_file_name;

            if(move_uploaded_file($file_tmp, $destination)){
                // Update DB with new image and username
                $stmt = $db->prepare("UPDATE admins SET profile_image = ?, username = ? WHERE id = ?");
                $stmt->execute([$new_file_name, $new_username, $admin_id]);
                $success = "Profile updated successfully!";
                $admin['username'] = $new_username;
                $admin['profile_image'] = $new_file_name;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Only JPG, JPEG, PNG, GIF files are allowed.";
        }
    } else {
        // Update only username
        $stmt = $db->prepare("UPDATE admins SET username = ? WHERE id = ?");
        $stmt->execute([$new_username, $admin_id]);
        $success = "Profile updated successfully!";
        $admin['username'] = $new_username;
    }
}

require_once __DIR__.'/../includes/header.php';
?>
<div class="">
    <h4 class="mb-4">Update Profile</h4>
    <div class="card p-4" ">
    
        <?php if($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    
        <form method="POST" enctype="multipart/form-data">
            <div class="row mb-3 text-center">
                <div class="col-12">
                    <img src="uploads/profile/<?= htmlspecialchars($admin['profile_image'] ?? 'default.png') ?>" 
                         alt="Profile Image" class="rounded-circle mb-3" width="150" height="150" style="object-fit: cover;">
                </div>
            </div>
    
            <div class="row mb-3">
                <label for="username" class="col-sm-3 col-form-label">Username</label>
                <div class="col-sm-9">
                    <input type="text" name="username" class="form-control" id="username" 
                           value="<?= htmlspecialchars($admin['username']) ?>" required>
                </div>
            </div>
    
            <div class="row mb-3">
                <label for="profile_image" class="col-sm-3 col-form-label">Profile Image</label>
                <div class="col-sm-9">
                    <input type="file" name="profile_image" class="form-control" id="profile_image" accept="image/*">
                    <small class="form-text text-muted">Only JPG, JPEG, PNG, GIF allowed. Old image will be replaced.</small>
                </div>
            </div>
    
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                    <button type="submit" class="btn btn-primary px-5 rounded-0">Update Profile</button>
                </div>
            </div>
        </form>
    </div>
</div>

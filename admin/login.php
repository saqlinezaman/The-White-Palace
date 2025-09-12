<?php

session_start();
include 'config/db_config.php';

// Database connection
$database = new Database();
$db_connection = $database->db_connection();

$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if($username && $password){
        $stmt = $db_connection->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if($admin && password_verify($password, $admin['password'])){
            $_SESSION['admin_logged_in'] = true;
            header('Location: index.php');
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Please enter both username and password.";
    }
}

?>
<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login into the white palace</title>

  <!--plugins-->
  <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
  <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet">
  <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet">
  <!--Styles-->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="cdn.jsdelivr.net/npm/bootstrap-icons%401.10.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/icons.css">

  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&amp;display=swap"
    rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">
  <link href="assets/css/dark-theme.css" rel="stylesheet">

</head>

<body>


  <!--authentication-->

  <div class="mx-3 mx-lg-0">

    <div class="card my-3 col-xl-9 col-xxl-8 mx-auto rounded-4 overflow-hidden border-3 p-3">
      <div class="row g-3">
        <div class="col-lg-6 d-flex">
          <div class="card-body p-5 w-100">
            <img src="assets/images/logo-icon.png" class="mb-4" width="45" alt="">
            <h4 class="fw-bold">Hi Admin</h4>
            <p class="mb-0">Enter your credentials to login your account</p>
              <?php if($error): ?>
                <div class="alert alert-danger mt-2" role="alert">
                  <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?> 
                
            <div class="form-body mt-4">
              <form method="POST" action="login.php" class="row g-3">
                <div class="col-12">
                  <label for="inputEmailAddress" class="form-label">Username</label>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Input your username">
                </div>
                <div class="col-12">
                  <label for="inputChoosePassword" class="form-label">Password</label>
                  <div class="input-group" id="show_hide_password">
                    <input type="password" class="form-control border-end-0" id="inputChoosePassword" name="password"
                      placeholder="Enter Password">
                    <a href="javascript:;" class="input-group-text bg-transparent"><i
                        class="bi bi-eye-slash-fill"></i></a>
                  </div>
                </div>
                <div class="col-12">
                  <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                  </div>
                </div>
                <div class="col-12">
                  <div class="text-start">
                    <p class="mb-0">Don't have an account yet? <a href="auth-boxed-register.html">Sign up here</a>
                    </p>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-lg-6 d-lg-flex d-none">
          <div class="p-3 rounded-4 w-100 d-flex align-items-center justify-content-center border-3 bg-primary">
            <img src="assets/images/boxed-login.png" class="img-fluid" alt="">
          </div>
        </div>

      </div><!--end row-->
    </div>

  </div>




  <!--authentication-->




  <!--plugins-->
  <script src="assets/js/jquery.min.js"></script>

  <script>
    $(document).ready(function () {
      $("#show_hide_password a").on('click', function (event) {
        event.preventDefault();
        if ($('#show_hide_password input').attr("type") == "text") {
          $('#show_hide_password input').attr('type', 'password');
          $('#show_hide_password i').addClass("bi-eye-slash-fill");
          $('#show_hide_password i').removeClass("bi-eye-fill");
        } else if ($('#show_hide_password input').attr("type") == "password") {
          $('#show_hide_password input').attr('type', 'text');
          $('#show_hide_password i').removeClass("bi-eye-slash-fill");
          $('#show_hide_password i').addClass("bi-eye-fill");
        }
      });
    });
  </script>

</body>

<script>'undefined' === typeof _trfq || (window._trfq = []); 'undefined' === typeof _trfd && (window._trfd = []), _trfd.push({ 'tccl.baseHost': 'secureserver.net' }, { 'ap': 'cpsh-oh' }, { 'server': 'p3plzcpnl509132' }, { 'dcenter': 'p3' }, { 'cp_id': '10399385' }, { 'cp_cl': '8' }) // Monitoring performance to make your website faster. If you want to opt-out, please contact web hosting support.</script>
<script src='../../../../img1.wsimg.com/signals/js/clients/scc-c2/scc-c2.min.js'></script>
<!-- Mirrored from codervent.com/roksyn/demo/ltr/auth-boxed-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 27 Aug 2025 09:51:36 GMT -->

</html>
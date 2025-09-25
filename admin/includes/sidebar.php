<?php
// যদি session এ login থাকে
if(isset($_SESSION['admin_logged_in'])) {
    require_once __DIR__ . '/../config/db_config.php';
    $database = new Database();
    $db = $database->db_connection();

    // ধরো session এ username আছে
    $stmt = $db->prepare("SELECT * FROM admins LIMIT 1"); // যদি এক জন admin থাকে
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>


<!--start sidebar-->
<aside class="sidebar-wrapper">
  <div class="sidebar-header">
    <div class="logo-icon">
      <img src="<?= BASE_URL ?>assets/images/logo-icon.png" class="logo-img" alt="">
    </div>
    <div class="logo-name flex-grow-1">
      <h6 class="mb-0">The White Palace Admin panel</h6>
    </div>
    <div class="sidebar-close ">
      <span class="material-symbols-outlined">close</span>
    </div>
  </div>
  <div class="sidebar-nav" data-simplebar="true">

    <!--navigation-->
    <ul class="metismenu" id="menu">

      <!-- View Website -->
      <li>
        <a href="../Frontend/index.php" target="_blank">
          <div class="parent-icon"><span class="material-symbols-outlined">language</span></div>
          <div class="menu-title">View Website</div>
        </a>
      </li>

      <!-- Dashboard -->
      <li class="nav-item">
        <a class="nav-link d-flex align-items-center" data-bs-toggle="collapse" href="#dashMenu" role="button"
          aria-expanded="false" aria-controls="blogsMenu">
          <div class="parent-icon">
            <span class="material-symbols-outlined">dashboard</span>
          </div>
          <div class="menu-title ms-2">Dashboard</div>
          <span class="ms-auto material-symbols-outlined">expand_more</span>
        </a>
        <div class="collapse" id="dashMenu">
          <ul class="nav flex-column ms-4">
            <li class="nav-item">
              <a class="nav-link" href="index.php?page=dashboard">
                <span class="material-symbols-outlined">chevron_right</span>Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?page=users">
                <span class="material-symbols-outlined">chevron_right</span>Users
              </a>
            </li>
          </ul>
        </div>
      </li>

      <!-- categories -->
      <li>
        <a href="index.php?page=add_categories">
          <div class="parent-icon"><span class="material-symbols-outlined">category</span></div>
          <div class="menu-title">Categories</div>
        </a>
      </li>
      <!-- Rooms -->
      <li>
        <a href="index.php?page=room">
          <div class="parent-icon"><span class="material-symbols-outlined">hotel</span></div>
          <div class="menu-title">Rooms</div>
        </a>
      </li>
      <!-- Bookings -->
      <li>
        <a href="index.php?page=manage_bookings">
          <div class="parent-icon"><span class="material-symbols-outlined">event_available</span></div>
          <div class="menu-title">Bookings</div>
        </a>
      </li>
        <!-- services -->
      <li>
        <a href="index.php?page=services">
          <div class="parent-icon"><span class="material-symbols-outlined">confirmation_number</span></div>
          <div class="menu-title">Services</div>
        </a>
      </li>
      <!-- blogs -->
      <li class="nav-item">
        <a class="nav-link d-flex align-items-center" data-bs-toggle="collapse" href="#blogsMenu" role="button"
          aria-expanded="false" aria-controls="blogsMenu">
          <div class="parent-icon">
            <span class="material-symbols-outlined">article</span>
          </div>
          <div class="menu-title ms-2">Blogs</div>
          <span class="ms-auto material-symbols-outlined">expand_more</span>
        </a>
        <div class="collapse" id="blogsMenu">
          <ul class="nav flex-column ms-4">
            <li class="nav-item">
              <a class="nav-link" href="index.php?page=blogs">
                <span class="material-symbols-outlined">chevron_right</span> All Blogs
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?page=add_blogs">
                <span class="material-symbols-outlined">chevron_right</span> Write Blogs
              </a>
            </li>
          </ul>
        </div>
      </li>


      <!-- Testimonials -->
      <li>
        <a href="index.php?page=testimonials">
          <div class="parent-icon"><span class="material-symbols-outlined">reviews</span></div>
          <div class="menu-title">Testimonials</div>
        </a>
      </li>
      <!-- Comments -->
      <li>
        <a href="index.php?page=comments">
          <div class="parent-icon"><span class="material-symbols-outlined">campaign</span></div>
          <div class="menu-title">Comments</div>
        </a>
      </li>
      <!-- Reviews -->
      <li>
        <a href="index.php?page=contact">
          <div class="parent-icon"><span class="material-symbols-outlined">outgoing_mail</span></div>
          <div class="menu-title">Reviews</div>
        </a>
      </li>


    </ul>
    <!--end navigation-->

  </div>

  <!-- bottom section for profile and log out -->
  <div class="sidebar-bottom dropdown dropup-center dropup">
    <div class="dropdown-toggle d-flex align-items-center px-3 gap-3 w-100 h-100" data-bs-toggle="dropdown">
      <div class="user-img">
        <img src="../admin/uploads/profile/<?= htmlspecialchars($admin['profile_image'] ?? 'default.png') ?>"  alt="">
      </div>
      <div class="user-info">
        <h5 class="mb-0 user-name">Md. Saqline Zaman</h5>
      </div>
    </div>
    <ul class="dropdown-menu dropdown-menu-end">
      <li><a class="dropdown-item" href="index.php?page=profile"><span class="material-symbols-outlined me-2">
            account_circle
          </span><span>Profile</span></a>
      </li>
      <li><a class="dropdown-item" href="index.php?page=logout"><span class="material-symbols-outlined me-2">
            logout
          </span><span>Logout</span></a>
      </li>
    </ul>
  </div>
</aside>
<!--end sidebar-->
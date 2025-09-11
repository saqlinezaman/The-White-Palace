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
      <li>
        <a href="index.php?page=dashboard">
          <div class="parent-icon"><span class="material-symbols-outlined">dashboard</span></div>
          <div class="menu-title">Dashboard</div>
        </a>
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

      <!-- Blog -->
      <li>
        <a href="index.php?page=manage_blog">
          <div class="parent-icon"><span class="material-symbols-outlined">article</span></div>
          <div class="menu-title">Blog</div>
        </a>
      </li>

      <!-- Testimonials -->
      <li>
        <a href="index.php?page=manage_testimonial">
          <div class="parent-icon"><span class="material-symbols-outlined">reviews</span></div>
          <div class="menu-title">Testimonials</div>
        </a>
      </li>
      <!-- Testimonials -->
      <li>
        <a href="index.php?page=contact">
          <div class="parent-icon"><span class="material-symbols-outlined">review</span></div>
          <div class="menu-title">Contact</div>
        </a>
      </li>

    </ul>
    <!--end navigation-->

  </div>
  <div class="sidebar-bottom dropdown dropup-center dropup">
    <div class="dropdown-toggle d-flex align-items-center px-3 gap-3 w-100 h-100" data-bs-toggle="dropdown">
      <div class="user-img">
        <img src="../assets/images/avatars/01.png" alt="">
      </div>
      <div class="user-info">
        <h5 class="mb-0 user-name">Jhon Maxwell</h5>
        <p class="mb-0 user-designation">UI Engineer</p>
      </div>
    </div>
    <ul class="dropdown-menu dropdown-menu-end">
      <li><a class="dropdown-item" href="javascript:;"><span class="material-symbols-outlined me-2">
            account_circle
          </span><span>Profile</span></a>
      </li>
      <li><a class="dropdown-item" href="javascript:;"><span class="material-symbols-outlined me-2">
            tune
          </span><span>Settings</span></a>
      </li>
      <li><a class="dropdown-item" href="javascript:;"><span class="material-symbols-outlined me-2">
            dashboard
          </span><span>Dashboard</span></a>
      </li>
      <li><a class="dropdown-item" href="javascript:;"><span class="material-symbols-outlined me-2">
            account_balance_wallet
          </span><span>Earnings</span></a>
      </li>
      <li><a class="dropdown-item" href="javascript:;"><span class="material-symbols-outlined me-2">
            cloud_download
          </span><span>Downloads</span></a>
      </li>
      <li>
        <div class="dropdown-divider mb-0"></div>
      </li>
      <li><a class="dropdown-item" href="javascript:;"><span class="material-symbols-outlined me-2">
            logout
          </span><span>Logout</span></a>
      </li>
    </ul>
  </div>
</aside>
<!--end sidebar-->

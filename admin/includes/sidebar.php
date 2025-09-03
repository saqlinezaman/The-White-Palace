
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
                <!-- view website -->
                <li>
                  <a href="<?= BASE_URL ?>..//Frontend/index.php">
                    <div class="parent-icon"><span class="material-symbols-outlined">home</span>
                    </div>
                    <div class="menu-title">view</div>
                  </a>
                </li>
                <!-- categories -->
                <li>
                  <a href="<?= BASE_URL ?>pages/categories/add_categories.php">
                    <div class="parent-icon"><span class="material-symbols-outlined">home</span>
                    </div>
                    <div class="menu-title">Categories</div>
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
                  account_balance
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
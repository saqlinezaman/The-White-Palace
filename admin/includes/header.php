<?php
define("BASE_URL", "/thewhitepalace/admin/");
?>
<!doctype html>
<html lang="en" data-bs-theme="white">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>The White Palace admin panel</title>

  <!--plugins-->
  <link href="<?= BASE_URL ?>assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>../assets/plugins/simplebar/css/simplebar.css" rel="stylesheet">
  <!-- loader-->
  <link href="<?= BASE_URL ?>assets/css/pace.min.css" rel="stylesheet">
  <script src="<?= BASE_URL ?>assets/js/pace.min.js"></script>
  <!--Styles-->
  <link href="<?= BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../cdn.jsdelivr.net/npm/bootstrap-icons%401.10.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/icons.css">

  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&amp;display=swap"
    rel="stylesheet">
  <link href="<?= BASE_URL ?>assets/css/main.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>assets/css/dark-theme.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>assets/css/semi-dark-theme.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>assets/css/minimal-theme.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>assets/css/shadow-theme.css" rel="stylesheet">

</head>

<body>

  <!--start header-->
  <header class="top-header">
    <nav class="navbar navbar-expand justify-content-between">
      <div class="btn-toggle-menu">
        <span class="material-symbols-outlined">menu</span>
      </div>
      <ul class="navbar-nav top-right-menu gap-2">
        <li class="nav-item d-lg-none d-block" data-bs-toggle="modal" data-bs-target="#exampleModal">
          <a class="nav-link" href="javascript:;"><span class="material-symbols-outlined">
              search
            </span></a>
        </li>
        <li class="nav-item dark-mode">
          <a class="nav-link dark-mode-icon" href="javascript:;"><span
              class="material-symbols-outlined">dark_mode</span></a>
        </li>
        <li class="nav-item dropdown dropdown-app">
          
          <div class="dropdown-menu dropdown-menu-end mt-lg-2 p-0">
            <div class="app-container p-2 my-2">
            </div>
          </div>
        </li>
        <li class="nav-item dropdown dropdown-large">
          
          <div class="dropdown-menu dropdown-menu-end mt-lg-2">
            <a href="javascript:;">
              <div class="msg-header">
                <p class="msg-header-title">Notifications</p>
                <p class="msg-header-clear ms-auto">Marks all as read</p>
              </div>
            </a>
            <div class="header-notifications-list">
              <a class="dropdown-item" href="javascript:;">
                <div class="d-flex align-items-center">
                  <div class="notify text-primary border">
                    <span class="material-symbols-outlined">
                      add_shopping_cart
                    </span>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="msg-name">New Orders <span class="msg-time float-end">2 min
                        ago</span></h6>
                    <p class="msg-info">You have recived new orders</p>
                  </div>
                </div>
              </a>
              <a class="dropdown-item" href="javascript:;">
                <div class="d-flex align-items-center">
                  <div class="notify text-danger border">
                    <span class="material-symbols-outlined">
                      account_circle
                    </span>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="msg-name">New Customers<span class="msg-time float-end">14 Sec
                        ago</span></h6>
                    <p class="msg-info">5 new user registered</p>
                  </div>
                </div>
              </a>
              <a class="dropdown-item" href="javascript:;">
                <div class="d-flex align-items-center">
                  <div class="notify text-success border">
                    <span class="material-symbols-outlined">
                      picture_as_pdf
                    </span>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="msg-name">24 PDF File<span class="msg-time float-end">19 min
                        ago</span></h6>
                    <p class="msg-info">The pdf files generated</p>
                  </div>
                </div>
              </a>
              <a class="dropdown-item" href="javascript:;">
                <div class="d-flex align-items-center">
                  <div class="notify text-info border">
                    <span class="material-symbols-outlined">
                      store
                    </span>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="msg-name">New Product Approved <span class="msg-time float-end">2 hrs ago</span></h6>
                    <p class="msg-info">Your new product has approved</p>
                  </div>
                </div>
              </a>
              <a class="dropdown-item" href="javascript:;">
                <div class="d-flex align-items-center">
                  <div class="notify text-warning border">
                    <span class="material-symbols-outlined">
                      event_available
                    </span>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="msg-name">Time Response <span class="msg-time float-end">28 min
                        ago</span></h6>
                    <p class="msg-info">5.1 min avarage time response</p>
                  </div>
                </div>
              </a>
              <a class="dropdown-item" href="javascript:;">
                <div class="d-flex align-items-center">
                  <div class="notify text-danger border">
                    <span class="material-symbols-outlined">
                      forum
                    </span>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="msg-name">New Comments <span class="msg-time float-end">4 hrs
                        ago</span></h6>
                    <p class="msg-info">New customer comments recived</p>
                  </div>
                </div>
              </a>
              <a class="dropdown-item" href="javascript:;">
                <div class="d-flex align-items-center">
                  <div class="notify text-primary border">
                    <span class="material-symbols-outlined">
                      local_florist
                    </span>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="msg-name">New 24 authors<span class="msg-time float-end">1 day
                        ago</span></h6>
                    <p class="msg-info">24 new authors joined last week</p>
                  </div>
                </div>
              </a>
              <a class="dropdown-item" href="javascript:;">
                <div class="d-flex align-items-center">
                  <div class="notify text-success border">
                    <span class="material-symbols-outlined">
                      park
                    </span>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="msg-name">Your item is shipped <span class="msg-time float-end">5 hrs
                        ago</span></h6>
                    <p class="msg-info">Successfully shipped your item</p>
                  </div>
                </div>
              </a>
              <a class="dropdown-item" href="javascript:;">
                <div class="d-flex align-items-center">
                  <div class="notify text-warning border">
                    <span class="material-symbols-outlined">
                      elevation
                    </span>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="msg-name">Defense Alerts <span class="msg-time float-end">2 weeks
                        ago</span></h6>
                    <p class="msg-info">45% less alerts last 4 weeks</p>
                  </div>
                </div>
              </a>
            </div>
            <a href="javascript:;">
              <div class="text-center msg-footer">View All</div>
            </a>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="offcanvas" href="#ThemeCustomizer"><span
              class="material-symbols-outlined">
              settings
            </span></a>
        </li>
      </ul>
    </nav>
  </header>
  <!--end header-->
  <!--start main content-->
  <main class="page-content">
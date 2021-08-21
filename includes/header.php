<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Duty Roaster Dashboard</title>

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="120x120" href="assets/img/favicon/apple-touch-icon.png">

  <!-- Fonts -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
  <!-- Page plugins -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.14.0/dist/sweetalert2.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.1/fullcalendar.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css"/>
  <!-- Argon CSS -->
  <link rel="stylesheet" href="assets/css/dashboard.css" type="text/css">
  <link rel="stylesheet" href="assets/css/custom.css" type="text/css">
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <?php require("includes/profile.php"); ?>
</head>

<body>
  <!-- Sidenav -->
  <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
      <div class="navbar-inner">
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
          <!-- Nav items -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="index">
                <i class="fas fa-home"></i>
                <span class="nav-link-text">Dashboard</span>
              </a>
            </li>
            <?php if($_SESSION['type']=='admin') {?>
            <li class="nav-item">
              <a class="nav-link" href="employee">
                <i class="fas fa-user-tie"></i>
                <span class="nav-link-text">Employees</span>
              </a>
            </li>
            <?php }?>
            <li class="nav-item">
              <a class="nav-link" href="holiday">
                <i class="fas fa-gift"></i>
                <span class="nav-link-text">Holidays</span>
              </a>
            </li>
            <?php if($_SESSION['type']=='employee') {?>
            <li class="nav-item">
              <a class="nav-link" href="notification">
                <i class="fas fa-bell"></i>
                <span class="nav-link-text">
                  <div class="row">
                    <div class="col-6">
                      Notification
                    </div>
                    <?php $count = $mysqli->query("SELECT COUNT(*) AS `count` FROM `swapdetails` WHERE `active`= 0 AND `swap_with_id`= $id")->fetch_row()[0]; 
                    if($count>0) { 
                    ?> 
                    <div class="col-6">
                      <span class="rounded-circle bg-info p-1 text-white"><b>&nbsp; <?= $count; ?> &nbsp;</b></span>
                    </div>
                    <?php } ?>
                  </div>
                </span>
              </a>
            </li>
            <?php }?>
            <?php if($_SESSION['type']=='admin') {?>
            <li class="nav-item">
              <a class="nav-link" href="analysis-shift">
                <i class="fas fa-tasks"></i>
                <span class="nav-link-text">Analysis</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="color">
              <i class="fas fa-palette"></i>
                <span class="nav-link-text">Colours</span>
              </a>
            </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
  </nav>
  <!-- Main content -->
  <div class="main-content" id="panel">
    <!-- Topnav -->
    <nav class="navbar navbar-top navbar-expand navbar-light bg-white border-bottom fixed-top shadow-sm">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Navbar links -->
          <ul class="navbar-nav align-items-center">
            <li class="nav-item d-flex align-items-center">
              <!-- Sidenav toggler -->
              <div class="pr-3 sidenav-toggler" data-action="sidenav-pin" data-target="#sidenav-main">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </div>
              <a class="navbar-brand pl-3" href="index.php">
                <img src="assets/img/logo.png" height="40" class="navbar-brand-img" alt="...">
              </a>
            </li>
          </ul>
          <ul class="navbar-nav align-items-center  ml-auto">
            <li class="nav-item dropdown">
              <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                    <img alt="Image placeholder" src="assets/img/avatar.png">
                  </span>
                  <div class="media-body  ml-2  d-none d-lg-block">
                    <span class="mb-0 text-sm  font-weight-bold text-dark"><?=$fname." ".$lname ?></span>
                  </div>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-right ">
                <div class="dropdown-header noti-title">
                  <h6 class="text-overflow m-0">Welcome!</h6>
                </div>
                <div class="dropdown-divider"></div>
                <a href="includes/logout" class="dropdown-item">
                  <i class="ni ni-user-run"></i>
                  <span>Logout</span>
                </a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
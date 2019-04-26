<?php
$admin = $getFromA->get_admin_by('id', $_SESSION['admin_id'], ['*']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title> Admin space </title>

    <!-- Fontfaces CSS-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans: 400, 700" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="vendor/mdi-font/css/material-design-iconic-font.min.css">

    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <!-- Vendor CSS-->
    <link rel="stylesheet" href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css">
    <link rel="stylesheet" href="vendor/wow/animate.css">
    <link rel="stylesheet" href="vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" href="vendor/slick/slick.css">
    <link rel="stylesheet" href="vendor/perfect-scrollbar/perfect-scrollbar.css">

    <!-- Main CSS-->
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="css/theme.css">
    
    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
</head>

<body class="animsition">
  <div class="page-wrapper">
<!-- Desktop Sidebar-->
<aside class="menu-sidebar2">
    <div class="logo">
      <a href="index.php">
        <h3 class="text-white">
            Admin
        </h3>
      </a>
    </div>
    <div class="menu-sidebar2__content js-scrollbar1">
        <div class="account2">
            <!-- <div style="position: absolute; top: 3%; right: 3%">
                <a href='./users.php' class="btn btn-sm btn-secondary rounded-circle">
                    <i class="fas fa-pencil-alt"></i>
                </a>
            </div> -->
            <div class="image img-cir img-90">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQgw8TvKTHQbuywHbEFFeTtM0Vi3i2scLt2pF759uch0fC02sIi" alt="ADMIN_IMG" />
            </div>
            <h4 class="name"><?php echo $admin->name?></h4>
            <a href="logout.php">Sign out</a>
        </div>
        <nav class="navbar-sidebar2">
            <ul class="list-unstyled navbar__list">
                 <li data-url='index.php'>
                    <a href="./index.php">
                        <i class="fas fa-store"></i> Dashboard
                    </a>
                </li>
                <!-- <li>
                    <a href="inbox.html">
                        <i class="fas fa-chart-bar"></i>Inbox
                    </a>
                    <span class="inbox-num">3</span>
                </li> -->
                  <li data-url='products' class="has-sub">
                    <a class="js-arrow" href="#">
                        <i class="fas fa-cube"></i> Products
                        <span class="arrow">
                            <i class="fas fa-angle-down"></i>
                        </span>
                    </a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                        <li data-url='add-product'>
                            <a href="./add-product">
                                add
                            </a>
                        </li>
                        <li data-url='edit-product'>
                            <a href="./edit-product">
                                manage
                            </a>
                        </li>
                    </ul>
                </li>
                <li data-url='orders.php'>
                    <a href="./orders.php">
                        <i class="fas fa-boxes"></i> Orders
                    </a>
                </li>
                <li class="has-sub">
                    <a class="js-arrow" href="#">
                        <i class="fas fa-network-wired"></i> Categories
                         <span class="arrow">
                            <i class="fas fa-angle-down"></i>
                        </span>
                    </a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                        <li data-url='categorys.php'>
                            <a href="./categorys.php">
                                add
                            </a>
                        </li>
                        <li data-url='edit-cates'>
                            <a href="./edit-cates">
                                manage
                            </a>
                        </li>
                    </ul>
                </li>
                <li data-url='users' class="has-sub">
                    <a class="js-arrow" href="#">
                        <i class="fas fa-users"></i> Users
                        <span class="arrow">
                            <i class="fas fa-angle-down"></i>
                        </span>
                    </a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                        <li data-url='add-users'>
                            <a href="./add-users">
                                add
                            </a>
                        </li>
                        <li data-url='edit-users'>
                            <a href="./edit-users">
                                manage
                            </a>
                        </li>
                    </ul>
                </li>
                 <li data-url='discounts' class="has-sub">
                    <a class="js-arrow" href="#">
                        <i class="fas fa-percent"></i> Discounts
                        <span class="arrow">
                            <i class="fas fa-angle-down"></i>
                        </span>
                    </a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                        <li data-url='discounts.php'>
                            <a href="./discounts.php">
                                add
                            </a>
                        </li>
                        <li data-url='edit-discounts'>
                            <a href="./edit-discounts">
                                manage
                            </a>
                        </li>
                    </ul>
                </li>
                 <li data-url='store_settings.php'>
                    <a href="./store_settings.php">
                        <i class="fas fa-tools"></i> Store settings
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
<!-- END MENU SIDEBAR-->

<!-- PAGE CONTAINER-->
<div class="page-container2">
    <!-- HEADER DESKTOP-->
    <header class="header-desktop2">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="header-wrap2">
                    <div class="logo d-block d-lg-none">
                       <a href="#">
                        <h3 class="text-white">
                                Admin
                        </h3>
                      </a>
                    </div>
                    <div class="header-button2">
                        <div class="header-button-item mr-0 js-sidebar-btn d-block d-lg-none">
                            <i class="zmdi zmdi-menu"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ############# Mobile Side Bar ############## -->
    <aside class="menu-sidebar2 js-right-sidebar d-block d-lg-none">
        <div class="logo d-flex justify-content-between">
                   <a href="index.php">
                    <h5 class="text-white">
                         admin
                    </h5>
                  </a>
            <span class="text-white" id='close_mobile_sidebar'>
                <i class="fas fa-times"></i>
            </span>
        </div>
        <div class="menu-sidebar2__content js-scrollbar2">
            <div class="account2">
                <!-- <div style="position: absolute; top: 3%; right: 3%">
                    <a href='./users.php' class="btn btn-sm btn-secondary rounded-circle">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                </div> -->
                <div class="image img-cir img-90">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQgw8TvKTHQbuywHbEFFeTtM0Vi3i2scLt2pF759uch0fC02sIi" alt="ADMIN_IMG" />
                </div>
                <h4 class="name"><?php echo $admin->name?></h4>
                <a href="logout.php">Sign out</a>
            </div>
            <nav class="navbar-sidebar2">
                <ul class="list-unstyled navbar__list">
                   <li data-url='index.php'>
                        <a href="./index.php">
                            <i class="fas fa-store"></i> Dashboard
                        </a>
                    </li>

                   <li class="has-sub">
                        <a class="js-arrow" href="#">
                            <i class="fas fa-cube"></i> Products
                            <span class="arrow">
                                <i class="fas fa-angle-down"></i>
                            </span>
                        </a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list">
                            <li data-url='add-product'>
                                <a href="./add-product">
                                    add
                                </a>
                            </li>
                            <li data-url='edit-product'>
                                <a href="./edit-product">
                                    EDIT
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li data-url='orders.php'>
                        <a href="./orders.php">
                            <i class="fas fa-boxes"></i> Orders
                        </a>
                    </li>
                     <li class="has-sub">
                        <a class="js-arrow" href="#">
                            <i class="fas fa-network-wired"></i> Categories
                             <span class="arrow">
                                <i class="fas fa-angle-down"></i>
                            </span>
                        </a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list">
                            <li data-url='categorys.php'>
                                <a href="./categorys.php">
                                    add
                                </a>
                            </li>
                            <li data-url='edit-cates'>
                                <a href="./edit-cates">
                                    manage
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-sub">
                        <a class="js-arrow" href="#">
                            <i class="fas fa-users"></i> Users
                            <span class="arrow">
                                <i class="fas fa-angle-down"></i>
                            </span>
                        </a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list">
                            <li data-url='add-users'>
                                <a href="./add-users">
                                    add
                                </a>
                            </li>
                            <li data-url='edit-users'>
                                <a href="./edit-users">
                                    manage
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-sub">
                        <a class="js-arrow" href="#">
                            <i class="fas fa-percent"></i> Discounts
                            <span class="arrow">
                                <i class="fas fa-angle-down"></i>
                            </span>
                        </a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list">
                            <li data-url='discounts.php'>
                                <a href="./discounts.php">
                                    add
                                </a>
                            </li>
                            <li data-url='edit-discounts'>
                                <a href="./edit-discounts">
                                    manage
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li data-url='store_settings.php'>
                        <a href="./store_settings.php">
                            <i class="fas fa-tools"></i> Store settings
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
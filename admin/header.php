<?php
session_start();
require("config.php");

// ✅ Secure access: only admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<div class="header">
    <!-- Logo -->
    <div class="header-left">
        <a href="dashboard.php" class="logo">
            <img src="assets/img/rsadmin.png" alt="Logo">
        </a>
        <a href="dashboard.php" class="logo logo-small">
            <img src="assets/img/logo-small.png" alt="Logo" width="30" height="30">
        </a>
    </div>
    <!-- /Logo -->

    <a href="javascript:void(0);" id="toggle_btn">
        <i class="fe fe-text-align-left"></i>
    </a>

    <!-- Mobile Menu Toggle -->
    <a class="mobile_btn" id="mobile_btn">
        <i class="fa fa-bars"></i>
    </a>
    <!-- /Mobile Menu Toggle -->

    <!-- Header Right Menu -->
    <ul class="nav user-menu">

        <!-- User Menu -->
        <li class="nav-item dropdown app-dropdown">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                <span class="user-img">
                    <img class="rounded-circle" src="assets/img/profiles/avatar-01.png" width="31" alt="Admin">
                </span>
            </a>
            <div class="dropdown-menu">
                <div class="user-header">
                    <div class="avatar avatar-sm">
                        <img src="assets/img/profiles/avatar-01.png" alt="User Image" class="avatar-img rounded-circle">
                    </div>
                    <div class="user-text">
                        <h6><?php echo $_SESSION['admin_email']; ?></h6>
                        <p class="text-muted mb-0">Administrator</p>
                    </div>
                </div>
                <a class="dropdown-item" href="profile.php">Profile</a>
                <a class="dropdown-item" href="logout.php">Logout</a>
            </div>
        </li>
        <!-- /User Menu -->

    </ul>
    <!-- /Header Right Menu -->
</div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title"><span>Main</span></li>
                <li><a href="dashboard.php"><i class="fe fe-home"></i> <span>Dashboard</span></a></li>

                <li class="menu-title"><span>All Users</span></li>
                <li class="submenu">
                    <a href="#"><i class="fe fe-user"></i> <span> All Users </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="adminlist.php"> Admin </a></li>
                        <li><a href="userlist.php"> Users </a></li>
                        <li><a href="useragent.php"> Agent </a></li>
                        <li><a href="userbuilder.php"> Seller </a></li>
                    </ul>
                </li>

                

                <li class="menu-title"><span>Property Management</span></li>
                <li class="submenu">
                    <a href="#"><i class="fe fe-map"></i> <span> Property</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="propertyadd.php"> Add Property</a></li>
                        <li><a href="propertyview.php"> View Property </a></li>
                    </ul>
                </li>

                <li class="menu-title"><span>Transaction</span></li>
                <li class="submenu">
                    <a href="#"><i class="fe fe-browser"></i> <span> Sold Property</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="aboutadd.php"> Offers to a property </a></li>
                        <li><a href="aboutdelete.php"> Property Bought By </a></li>


                        
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->

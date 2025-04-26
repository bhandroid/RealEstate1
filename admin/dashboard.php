<?php
require("config.php");
session_start();


// Access Control
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>LM Homes - Dashboard</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/feathericon.min.css">
    <link rel="stylesheet" href="assets/plugins/morris/morris.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Header -->
<?php include("header.php"); ?>
<!-- /Header -->

<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Welcome Admin!</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <!-- Registered Users -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-primary"><i class="fe fe-users"></i></span>
                        </div>
                        <div class="dash-widget-info">
                            <h3><?php $sql = "SELECT COUNT(*) AS total_users FROM user WHERE role != 'admin'"; $query = $con->query($sql); echo $query->fetch_assoc()['total_users']; ?></h3>
                            <h6 class="text-muted">Registered Users</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agents -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-success"><i class="fe fe-users"></i></span>
                        </div>
                        <div class="dash-widget-info">
                            <h3><?php $sql = "SELECT COUNT(*) AS agents FROM user WHERE role = 'agent'"; $query = $con->query($sql); echo $query->fetch_assoc()['agents']; ?></h3>
                            <h6 class="text-muted">Agents</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sellers -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-danger"><i class="fe fe-user"></i></span>
                        </div>
                        <div class="dash-widget-info">
                            <h3><?php $sql = "SELECT COUNT(*) AS sellers FROM user WHERE role = 'seller'"; $query = $con->query($sql); echo $query->fetch_assoc()['sellers']; ?></h3>
                            <h6 class="text-muted">Sellers</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Properties -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-info"><i class="fe fe-home"></i></span>
                        </div>
                        <div class="dash-widget-info">
                            <h3><?php $sql = "SELECT COUNT(*) AS total_props FROM property_listings WHERE status = 'available'"; $query = $con->query($sql); echo $query->fetch_assoc()['total_props']; ?></h3>
                            <h6 class="text-muted">Total Properties</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Apartments -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-warning"><i class="fe fe-table"></i></span>
                        </div>
                        <div class="dash-widget-info">
                            <h3><?php $sql = "SELECT COUNT(*) AS apartments FROM property_listings WHERE property_type = 'apartment'"; $query = $con->query($sql); echo $query->fetch_assoc()['apartments']; ?></h3>
                            <h6 class="text-muted">Apartments</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Houses -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-info"><i class="fe fe-home"></i></span>
                        </div>
                        <div class="dash-widget-info">
                            <h3><?php $sql = "SELECT COUNT(*) AS houses FROM property_listings WHERE property_type = 'house'"; $query = $con->query($sql); echo $query->fetch_assoc()['houses']; ?></h3>
                            <h6 class="text-muted">Houses</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- For Sale -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-secondary"><i class="fe fe-briefcase"></i></span>
                        </div>
                        <div class="dash-widget-info">
                            <h3><?php $sql = "SELECT COUNT(*) AS sale FROM property_listings WHERE property_type = 'for_sale'"; $query = $con->query($sql); echo $query->fetch_assoc()['sale']; ?></h3>
                            <h6 class="text-muted">Properties For Sale</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rentals -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-primary"><i class="fe fe-briefcase"></i></span>
                        </div>
                        <div class="dash-widget-info">
                            <h3><?php $sql = "SELECT COUNT(*) AS rental FROM property_listings WHERE property_type = 'rental'"; $query = $con->query($sql); echo $query->fetch_assoc()['rental']; ?></h3>
                            <h6 class="text-muted">Rental Properties</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View Tickets -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-dark"><i class="fe fe-message-square"></i></span>
                        </div>
                        <div class="dash-widget-info">
                            <h3><?php $sql = "SELECT COUNT(*) AS total_tickets FROM tickets"; $query = $con->query($sql); echo $query->fetch_assoc()['total_tickets']; ?></h3>
                            <h6 class="text-muted">Support Tickets</h6>
                            <a href="view_tickets.php" class="btn btn-sm btn-outline-dark mt-2">View Tickets</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports & Analytics -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-warning"><i class="fe fe-bar-chart-2"></i></span>
                        </div>
                        <div class="dash-widget-info">
                            <h3>Reports</h3>
                            <h6 class="text-muted">Sales, Users & Trends</h6>
                            <a href="reports.php" class="btn btn-sm btn-outline-warning mt-2">View Reports</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- /Page Wrapper -->

<!-- Scripts -->
<script src="assets/js/jquery-3.2.1.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/raphael/raphael.min.js"></script>
<script src="assets/plugins/morris/morris.min.js"></script>
<script src="assets/js/chart.morris.js"></script>
<script src="assets/js/script.js"></script>

</body>
</html>

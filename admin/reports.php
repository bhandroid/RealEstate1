<?php
require("config.php");
include("header.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Access Control
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LM Homes - Reports & Analytics</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/feathericon.min.css">
    <link rel="stylesheet" href="assets/plugins/morris/morris.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            width: 80%;
            margin: 30px auto;
            padding: 20px;
        }
        canvas {
            max-height: 400px !important;
        }
    </style>
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
                    <h3 class="page-title">📊 Reports & Analytics Dashboard</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Reports & Analytics</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <!-- Property Sales Report -->
        <h4 class="mt-4">🏘️ Property Sales Report (Day-wise)</h4>
        <div class="chart-container">
            <canvas id="salesChart"></canvas>
        </div>
        <?php
        $salesData = mysqli_query($con, 
            "SELECT DATE(created_at) AS Day, COUNT(*) AS Properties_Sold 
             FROM property_listings 
             WHERE status = 'sold' 
             GROUP BY DATE(created_at)
             ORDER BY DATE(created_at)");
        $days = [];
        $sales = [];
        while ($row = mysqli_fetch_assoc($salesData)) {
            $days[] = $row['Day'];
            $sales[] = $row['Properties_Sold'];
        }
        ?>

        <!-- User Engagement Report -->
        <h4 class="mt-5">👥 User Engagement (Registrations Per Day)</h4>
        <div class="chart-container">
            <canvas id="userChart"></canvas>
        </div>
        <?php
        $userData = mysqli_query($con, 
            "SELECT DATE(date_of_creation) AS Day, COUNT(*) AS Registrations 
             FROM user 
             GROUP BY DATE(date_of_creation)
             ORDER BY DATE(date_of_creation)");
        $userDays = [];
        $userCounts = [];
        while ($row = mysqli_fetch_assoc($userData)) {
            $userDays[] = $row['Day'];
            $userCounts[] = $row['Registrations'];
        }
        ?>

        <!-- Market Trends Report -->
        <h4 class="mt-5">📌 Market Trends (Average Property Price by Location)</h4>
        <div class="chart-container">
            <canvas id="priceChart"></canvas>
        </div>
        <?php
        $priceData = mysqli_query($con, 
            "SELECT location, AVG(price) AS AvgPrice 
             FROM property_listings 
             GROUP BY location");
        $locations = [];
        $avgPrices = [];
        while ($row = mysqli_fetch_assoc($priceData)) {
            $locations[] = $row['location'];
            $avgPrices[] = $row['AvgPrice'];
        }
        ?>

        <!-- Property Types Distribution -->
        <h4 class="mt-5">🏠 Property Types Distribution</h4>
        <div class="chart-container">
            <canvas id="typeChart"></canvas>
        </div>
        <?php
        $typeData = mysqli_query($con, 
            "SELECT property_type, COUNT(*) AS Count 
             FROM property_listings 
             GROUP BY property_type");
        $types = [];
        $typeCounts = [];
        while ($row = mysqli_fetch_assoc($typeData)) {
            $types[] = $row['property_type'];
            $typeCounts[] = $row['Count'];
        }
        ?>
    </div>
</div>
<!-- /Page Wrapper -->

<!-- Chart.js Scripts -->
<script>
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($days); ?>,
            datasets: [{
                label: 'Properties Sold',
                data: <?php echo json_encode($sales); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderWidth: 1
            }]
        }
    });

    const userCtx = document.getElementById('userChart').getContext('2d');
    new Chart(userCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($userDays); ?>,
            datasets: [{
                label: 'User Registrations',
                data: <?php echo json_encode($userCounts); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderWidth: 2,
                fill: true
            }]
        }
    });

    const priceCtx = document.getElementById('priceChart').getContext('2d');
    new Chart(priceCtx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($locations); ?>,
            datasets: [{
                label: 'Average Price',
                data: <?php echo json_encode($avgPrices); ?>,
                backgroundColor: [
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)'
                ],
                borderWidth: 1
            }]
        }
    });

    const typeCtx = document.getElementById('typeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($types); ?>,
            datasets: [{
                label: 'Number of Listings',
                data: <?php echo json_encode($typeCounts); ?>,
                backgroundColor: 'rgba(255, 159, 64, 0.7)',
                borderWidth: 1
            }]
        }
    });
</script>

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

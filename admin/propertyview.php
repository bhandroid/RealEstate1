<?php
session_start();
require("config.php");

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
    <title>LM Homes - Property Listings</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/feathericon.min.css">
    <link rel="stylesheet" href="assets/plugins/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/plugins/datatables/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/plugins/datatables/select.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/plugins/datatables/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
<?php include("header.php"); ?>

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Property Listings</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Property</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mt-0 mb-4">Property View</h4>
                        <?php if (isset($_GET['msg'])) echo '<div class="alert alert-success">'.htmlspecialchars($_GET['msg']).'</div>'; ?>

                        <table id="datatable-buttons" class="table table-striped dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Bedrooms</th>
                                    <th>Bathrooms</th>
                                    <th>Size (sqft)</th>
                                    <th>Price</th>
                                    <th>Street</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Zip</th>
                                    <th>Pool Available</th>
                                    <th>Dog Friendly</th>
                                    <th>Nearest School</th>
                                    <th>Bus Availability</th>
                                    <th>Tram Availability</th>
                                    <th>Available Date (If Rental)</th>
                                    <th>Security Deposit (If Rental)</th>
                                    <th>Status</th>
                                    <th>Added Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($con, "SELECT * FROM property_listings");
                                while ($row = mysqli_fetch_assoc($query)) {
                                    // Fetch rental details if Rental
                                    $available_date = 'N/A';
                                    $security_deposit = 'N/A';
                                    if ($row['property_type'] === 'Rental') {
                                        $rentalQuery = mysqli_query($con, "SELECT available_date, security_deposit FROM rental_contracts WHERE property_id = '{$row['property_id']}' LIMIT 1");
                                        $rentalData = mysqli_fetch_assoc($rentalQuery);
                                        $available_date = $rentalData['available_date'] ?? 'N/A';
                                        $security_deposit = $rentalData['security_deposit'] ?? 'N/A';
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo htmlspecialchars($row['property_type']); ?></td>
                                        <td><?php echo $row['bedrooms']; ?></td>
                                        <td><?php echo $row['bathrooms']; ?></td>
                                        <td><?php echo $row['size_sqft']; ?></td>
                                        <td><?php echo $row['price']; ?></td>
                                        <td><?php echo htmlspecialchars($row['street']); ?></td>
                                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                                        <td><?php echo htmlspecialchars($row['state']); ?></td>
                                        <td><?php echo htmlspecialchars($row['zip']); ?></td>
                                        <td><?php echo $row['pool_available']; ?></td>
                                        <td><?php echo $row['is_dog_friendly']; ?></td>
                                        <td><?php echo htmlspecialchars($row['nearest_school']); ?></td>
                                        <td><?php echo $row['bus_availability']; ?></td>
                                        <td><?php echo $row['tram_availability']; ?></td>
                                        <td><?php echo $available_date; ?></td>
                                        <td><?php echo $security_deposit; ?></td>
                                        <td><?php echo $row['status']; ?></td>
                                        <td><?php echo $row['created_at']; ?></td>
                                        <td>
                                            <a href="propertyedit.php?id=<?php echo $row['property_id']; ?>" class="btn btn-info btn-sm">Edit</a>
                                            <a href="propertydelete.php?id=<?php echo $row['property_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this property?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Scripts -->
<script src="assets/js/jquery-3.2.1.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables/dataTables.select.min.js"></script>
<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="assets/plugins/datatables/buttons.flash.min.js"></script>
<script src="assets/plugins/datatables/buttons.print.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>

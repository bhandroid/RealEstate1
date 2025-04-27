<?php
session_start();
require("config.php");
require("../functions.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$error = "";
$msg = "";

if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $street = $_POST['street'];
    $location = $_POST['location'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $property_type = $_POST['property_type'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $size_sqft = $_POST['size_sqft'];
    $nearest_school = $_POST['nearest_school'];
    $bus_availability = $_POST['bus_availability'];
    $tram_availability = $_POST['tram_availability'];
    $pool_available = $_POST['pool_available'];
    $is_dog_friendly = $_POST['is_dog_friendly'];
    $status = $_POST['status'];
    $seller_id = $_POST['seller_id'];

    $sql = "INSERT INTO property_listings 
        (title, description, price, street, location, state, zip, property_type, bedrooms, bathrooms, size_sqft, nearest_school, bus_availability, tram_availability, pool_available, is_dog_friendly, seller_id, status, created_at)
        VALUES 
        ('$title', '$description', '$price', '$street', '$location', '$state', '$zip', '$property_type', '$bedrooms', '$bathrooms', '$size_sqft', '$nearest_school', '$bus_availability', '$tram_availability', '$pool_available', '$is_dog_friendly', '$seller_id', '$status', NOW())";

    $result = mysqli_query($con, $sql);

    if ($result) {
        $property_id = mysqli_insert_id($con);
        addAuditLog($_SESSION['uid'], 'ADMIN_ADD_PROPERTY', 'Admin added property with title: ' . $title . ' for Seller/Agent ID: ' . $seller_id);

        // ✅ Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imgName = basename($_FILES['image']['name']);
            $targetPath = "property/" . $imgName;

            if (!is_dir("property")) {
                mkdir("property", 0777, true);
            }

            move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);

            $imgSql = "INSERT INTO property_image (property_id, image_url) VALUES ('$property_id', '$imgName')";
            mysqli_query($con, $imgSql);
        }

        // ✅ Handle rental contract
        if (strtolower($property_type) === 'rental') {
            $available_date = $_POST['available_date'];
            $security_deposit = $_POST['security_deposit'];
            $rentalSql = "INSERT INTO rental_contracts (property_id, available_date, security_deposit) 
                          VALUES ('$property_id', '$available_date', '$security_deposit')";
            mysqli_query($con, $rentalSql);
        }

        $msg = "<p class='alert alert-success'>Property Inserted Successfully</p>";
    } else {
        $error = "<p class='alert alert-warning'>Property Not Inserted. Some Error Occurred.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Add Property - Admin | LM Homes</title>
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/feathericon.min.css">
    <link rel="stylesheet" href="assets/plugins/morris/morris.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include("header.php"); ?>

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <h3 class="page-title">Add Property</h3>
        </div>

        <?php echo $msg; ?><?php echo $error; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="card p-4">
                <h5>Basic Information</h5>
                <div class="form-group"><label>Title</label><input type="text" name="title" class="form-control" required></div>
                <div class="form-group"><label>Description</label><textarea name="description" class="form-control" required></textarea></div>
                <div class="form-group"><label>Price</label><input type="number" name="price" class="form-control" required></div>

                <h5 class="mt-4">Address Details</h5>
                <div class="form-group"><label>Street</label><input type="text" name="street" class="form-control" required></div>
                <div class="form-group"><label>City</label><input type="text" name="location" class="form-control" required></div>
                <div class="form-group"><label>State</label><input type="text" name="state" class="form-control" required></div>
                <div class="form-group"><label>Zip</label><input type="text" name="zip" class="form-control" required></div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Property Type</label>
                        <select name="property_type" class="form-control" id="property_type" required>
                            <option value="">Select</option>
                            <option value="Sale" <?= (isset($row['property_type']) && $row['property_type'] === 'Sale') ? 'selected' : '' ?>>Sale</option>
                            <option value="Rental" <?= (isset($row['property_type']) && $row['property_type'] === 'Rental') ? 'selected' : '' ?>>Rental</option>
                        </select>

                    </div>
                    <div class="form-group col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="available">Available</option>
                            <option value="sold">Sold</option>
                        </select>
                    </div>
                </div>

                <div id="rental_fields" style="display:none;">
                    <h6 class="mt-3">Rental Contract Details</h6>
                    <div class="form-group"><label>Available Date</label><input type="date" name="available_date" class="form-control"></div>
                    <div class="form-group"><label>Security Deposit</label><input type="number" name="security_deposit" class="form-control"></div>
                </div>

                <h5 class="mt-4">Additional Details</h5>
                <div class="form-row">
                    <div class="form-group col-md-4"><label>Bedrooms</label><input type="number" name="bedrooms" class="form-control" required></div>
                    <div class="form-group col-md-4"><label>Bathrooms</label><input type="number" name="bathrooms" class="form-control" required></div>
                    <div class="form-group col-md-4"><label>Size (sqft)</label><input type="number" name="size_sqft" class="form-control" required></div>
                </div>
                <div class="form-row">
                   
                    <div class="form-group col-md-3">
                        <label>Tram Availability</label>
                        <select name="tram_availability" class="form-control">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Bus Availability</label>
                        <select name="bus_availability" class="form-control">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group"><label>Nearest School</label><input type="text" name="nearest_school" class="form-control"></div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Pool Available</label>
                        <select name="pool_available" class="form-control">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Dog Friendly</label>
                        <select name="is_dog_friendly" class="form-control">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Property Image</label>
                    <input type="file" name="image" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Select Seller/Agent (User)</label>
                    <select name="seller_id" class="form-control" required>
                        <option value="">Select User</option>
                        <?php
                        $q = mysqli_query($con, "SELECT user_id, name, role FROM user WHERE role IN ('agent','seller')");
                        while ($u = mysqli_fetch_assoc($q)) {
                            echo "<option value='{$u['user_id']}'>{$u['name']} ({$u['role']})</option>";
                        }
                        ?>
                    </select>
                </div>

                <input type="submit" name="add" value="Submit Property" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>

<script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/raphael/raphael.min.js"></script>
    <script src="assets/plugins/morris/morris.min.js"></script>
    <script src="assets/js/chart.morris.js"></script>
    <script src="assets/js/script.js"></script>
<script>
    document.getElementById("property_type").addEventListener("change", function () {
        const val = this.value.toLowerCase();
        document.getElementById("rental_fields").style.display = val === "rental" ? "block" : "none";
    });
</script>
</body>
</html>

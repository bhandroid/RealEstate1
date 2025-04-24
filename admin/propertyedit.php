<?php
session_start();
require("config.php");
require("../functions.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$msg = "";

if (isset($_POST['update'])) {
    $property_id = $_POST['property_id'];
    $title = $_POST['title'];
    $property_type = $_POST['property_type'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $size_sqft = $_POST['size_sqft'];
    $price = $_POST['price'];
    $location = $_POST['location'];
    $status = $_POST['status'];

    // Image upload handling
    $image = $_FILES['image']['name'];
    $temp_image = $_FILES['image']['tmp_name'];
    if (!empty($image)) {
        move_uploaded_file($temp_image, "property/$image");
        $image_query = ", image_url = '$image'";
    } else {
        $image_query = "";
    }

    $sql = "UPDATE property_listings 
            SET title = '$title', property_type = '$property_type', bedrooms = '$bedrooms', bathrooms = '$bathrooms',
                size_sqft = '$size_sqft', price = '$price', location = '$location', status = '$status' $image_query
            WHERE property_id = '$property_id'";

    $result = mysqli_query($con, $sql);
    if ($result) {
        header("Location: propertyview.php?msg=Property+Updated+Successfully");
        addAuditLog($_SESSION['uid'], 'ADMIN_EDIT_PROPERTY', 'Admin edited property with ID: ' . $property_id);

        exit();
    } else {
        $msg = "<p class='alert alert-warning'>Failed to Update Property</p>";
    }
}

if (isset($_GET['id'])) {
    $property_id = $_GET['id'];
    $query = mysqli_query($con, "SELECT * FROM property_listings WHERE property_id = '$property_id'");
    $row = mysqli_fetch_assoc($query);
} else {
    header("Location: propertyview.php?msg=No+Property+Selected");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>LM HOMES | Property</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
		
		<!-- Feathericon CSS -->
        <link rel="stylesheet" href="assets/css/feathericon.min.css">
		
		<!-- Main CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
		
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
    </head>

<body>
<?php include("header.php"); ?>

<div class="container mt-5">
    <h2>Edit Property Details</h2>
    <?php echo $msg; ?>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="property_id" value="<?php echo $row['property_id']; ?>">

        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required value="<?php echo $row['title']; ?>">
        </div>

        <div class="form-group">
            <label>Property Type</label>
            <select name="property_type" class="form-control" required>
                <option value="apartment" <?php if($row['property_type'] == 'apartment') echo 'selected'; ?>>Apartment</option>
                <option value="house" <?php if($row['property_type'] == 'house') echo 'selected'; ?>>House</option>
                <option value="villa" <?php if($row['property_type'] == 'villa') echo 'selected'; ?>>Villa</option>
                <option value="office" <?php if($row['property_type'] == 'office') echo 'selected'; ?>>Office</option>
            </select>
        </div>

        <div class="form-group">
            <label>Bedrooms</label>
            <input type="number" name="bedrooms" class="form-control" required value="<?php echo $row['bedrooms']; ?>">
        </div>

        <div class="form-group">
            <label>Bathrooms</label>
            <input type="number" name="bathrooms" class="form-control" required value="<?php echo $row['bathrooms']; ?>">
        </div>

        <div class="form-group">
            <label>Size (sqft)</label>
            <input type="number" name="size_sqft" class="form-control" required value="<?php echo $row['size_sqft']; ?>">
        </div>

        <div class="form-group">
            <label>Price</label>
            <input type="number" name="price" class="form-control" required value="<?php echo $row['price']; ?>">
        </div>

        <div class="form-group">
            <label>Location</label>
            <input type="text" name="location" class="form-control" required value="<?php echo $row['location']; ?>">
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="available" <?php if($row['status'] == 'available') echo 'selected'; ?>>Available</option>
                <option value="sold out" <?php if($row['status'] == 'sold out') echo 'selected'; ?>>Sold Out</option>
            </select>
        </div>

        <div class="form-group">
            <label>Change Image (Optional)</label><br>
            <input type="file" name="image" class="form-control">
            <?php if (!empty($row['image_url'])): ?>
                <br><img src="property/<?php echo $row['image_url']; ?>" width="150" height="150">
            <?php endif; ?>
        </div>

        <button type="submit" name="update" class="btn btn-primary">Update Property</button>
    </form>
</div>

<!-- jQuery -->
<script src="assets/js/jquery-3.2.1.min.js"></script>
		<script src="assets/plugins/tinymce/tinymce.min.js"></script>
		<script src="assets/plugins/tinymce/init-tinymce.min.js"></script>
		<!-- Bootstrap Core JS -->
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
		
		<!-- Slimscroll JS -->
        <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		
		<!-- Custom JS -->
		<script  src="assets/js/script.js"></script>
		
    </body>


</html>

<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");

if (!isset($_SESSION['uemail'])) {
    header("location:login.php");
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'agent') {
    echo "Access denied. Only sellers can access this page.";
    exit();
}

$error = "";
$msg = "";

if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $location = $_POST['location'];
    $property_type = $_POST['property_type'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $size_sqft = $_POST['size_sqft'];
    $amenities = $_POST['amenities'];
    $nearest_school = $_POST['nearest_school'];
    $bus_availability = $_POST['bus_availability'];
    $tram_availability = $_POST['tram_availability'];
    $status = $_POST['status'];
    $seller_id = $_SESSION['uid'];

    $sql = "INSERT INTO PROPERTY_LISTINGS 
    (TITLE, DESCRIPTION, PRICE, LOCATION, PROPERTY_TYPE, BEDROOMS, BATHROOMS, SIZE_SQFT, AMENITIES, NEAREST_SCHOOL, BUS_AVAILABILITY, TRAM_AVAILABILITY, SELLER_ID, STATUS, CREATED_AT)
    VALUES 
    ('$title', '$description', '$price', '$location', '$property_type', '$bedrooms', '$bathrooms', '$size_sqft', '$amenities', '$nearest_school', '$bus_availability', '$tram_availability', '$seller_id', '$status', NOW())";

    $result = mysqli_query($con, $sql);

    if ($result) {
        $property_id = mysqli_insert_id($con);

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imgName = basename($_FILES['image']['name']);
            $targetPath = "property_images/" . $imgName;
            move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);

            $imgSql = "INSERT INTO PROPERTY_IMAGE (PROPERTY_ID, IMAGE_URL) VALUES ('$property_id', '$targetPath')";
            mysqli_query($con, $imgSql);
        }

        if (strtolower($property_type) === 'rental') {
            $available_date = $_POST['available_date'];
            $security_deposit = $_POST['security_deposit'];
            $rentalSql = "INSERT INTO RENTAL_CONTRACTS (PROPERTY_ID, AVAILABLE_DATE, SECURITY_DEPOSIT) 
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
<!-- Required meta tags -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="shortcut icon" href="images/favicon.ico">
<link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/layerslider.css">
<link rel="stylesheet" type="text/css" href="css/color.css">
<link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/login.css">
<title>Real Estate PHP</title>
</head>

<body>
<div id="page-wrapper">
    <div class="row">
        <?php include("include/header.php"); ?>

        <div class="banner-full-row page-banner" style="background-image:url('images/breadcromb.jpg');">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Submit Property</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-left float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Submit Property</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="full-row">
            <div class="container">
                <div class="dashboard-personal-info p-5 bg-white">
                    <form method="post" enctype="multipart/form-data">
                        <h5 class="text-secondary border-bottom-on-white pb-3 mb-4">Property Information</h5>
                        <?php echo $msg; ?><?php echo $error; ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- Left column fields -->
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="number" name="price" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Location</label>
                                    <input type="text" name="location" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Property Type</label>
                                    <select name="property_type" class="form-control" id="property_type" required>
                                        <option value="">Select</option>
                                        <option value="Apartment">Apartment</option>
                                        <option value="House">House</option>
                                        <option value="Villa">Villa</option>
                                        <option value="Rental">Rental</option>
                                    </select>
                                </div>
                                <div id="rental_fields" style="display:none;">
                                    <div class="form-group">
                                        <label>Available Date</label>
                                        <input type="date" name="available_date" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Security Deposit</label>
                                        <input type="number" name="security_deposit" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Amenities</label>
                                    <textarea name="amenities" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Nearest School</label>
                                    <input type="text" name="nearest_school" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <!-- Right column fields -->
                                <div class="form-group">
                                    <label>Bedrooms</label>
                                    <input type="number" name="bedrooms" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Bathrooms</label>
                                    <input type="number" name="bathrooms" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Size (sqft)</label>
                                    <input type="number" name="size_sqft" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Bus Availability</label>
                                    <select name="bus_availability" class="form-control">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Tram Availability</label>
                                    <select name="tram_availability" class="form-control">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="available">Available</option>
                                        <option value="sold">Sold</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Upload Property Image</label>
                                    <input type="file" name="image" accept="image/*" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <input type="submit" name="add" value="Submit Property" class="btn btn-info btn-block">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php include("include/footer.php"); ?>
        <a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a>
    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
$(document).ready(function () {
    $('#property_type').on('change', function () {
        var val = $(this).val().toLowerCase();
        if (val === 'rental') {
            $('#rental_fields').show();
        } else {
            $('#rental_fields').hide();
        }
    });
});
</script>
</body>
</html>
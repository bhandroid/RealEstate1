<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");
include("functions.php");  // ✅ Audit log function included!

if (!isset($_SESSION['email'])) {
    header("location:login.php");
    exit();
}

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Seller' && $_SESSION['role'] !== 'Agent')) {
    echo "Access denied. Only sellers or agents can access this page.";
    exit();
}

$error = "";
$msg = "";

if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $location = $_POST['location'];

    $zip = $_POST['zip'];
    $street = $_POST['street'];
    $state = $_POST['state'];


    $property_type = $_POST['property_type'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $size_sqft = $_POST['size_sqft'];

    $pool_available = $_POST['pool_available'];
    $is_dog_friendly = $_POST['is_dog_friendly'];

    $nearest_school = $_POST['nearest_school'];
    $bus_availability = $_POST['bus_availability'];
    $tram_availability = $_POST['tram_availability'];
    $status = $_POST['status'];
    $seller_id = $_SESSION['uid'];

    // ✅ Insert into property_listings table
    $sql = "INSERT INTO property_listings 
        (title, description, price, street,location,state,zip ,property_type, bedrooms, bathrooms, size_sqft, pool_available,is_dog_friendly, nearest_school, bus_availability, tram_availability, seller_id, status, created_at)
        VALUES 
        ('$title', '$description', '$price', '$street','$location','$state','$zip', '$property_type', '$bedrooms', '$bathrooms', '$size_sqft', '$pool_available','$is_dog_friendly', '$nearest_school', '$bus_availability', '$tram_availability', '$seller_id', '$status', NOW())";

    $result = mysqli_query($con, $sql);

    if ($result) {
        $property_id = mysqli_insert_id($con);

        // ✅ Handle property image upload
        if (isset($_FILES['images'])) {
            $totalImages = count($_FILES['images']['name']);
            for ($i = 0; $i < $totalImages; $i++) {
                if ($_FILES['images']['error'][$i] === 0) {
                    $imgName = basename($_FILES['images']['name'][$i]);
                    $targetPath = "admin/property/" . $imgName;
        
                    if (!is_dir("admin/property")) {
                        mkdir("admin/property", 0777, true);
                    }
        
                    move_uploaded_file($_FILES['images']['tmp_name'][$i], $targetPath);
        
                    $imgSql = "INSERT INTO property_image (property_id, image_url) VALUES ('$property_id', '$imgName')";
                    mysqli_query($con, $imgSql);
                }
            }
        }
        

        // ✅ Handle rental-specific data if property type is Rental
        if (strtolower($property_type) === 'rental') {
            $available_date = $_POST['available_date'];
            $security_deposit = $_POST['security_deposit'];
            $rentalSql = "INSERT INTO rental_contracts (property_id, available_date, security_deposit) 
                          VALUES ('$property_id', '$available_date', '$security_deposit')";
            mysqli_query($con, $rentalSql);
        }

        // ✅ Audit log for property addition
        addAuditLog($seller_id, 'ADD_PROPERTY', 'Added property with title: ' . $title);

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
    <title>Submit Property | Real Estate PHP</title>
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/layerslider.css">
<link rel="stylesheet" type="text/css" href="css/color.css" id="color-change">
<link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
    <!-- ✅ Script to toggle rental fields -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const propertyType = document.getElementById('property_type');
            const rentalFields = document.getElementById('rental_fields');

            function toggleRentalFields() {
                if (propertyType.value.toLowerCase() === 'rental') {
                    rentalFields.style.display = 'block';
                } else {
                    rentalFields.style.display = 'none';
                }
            }

            propertyType.addEventListener('change', toggleRentalFields);
            toggleRentalFields();
        });
    </script>
</head>

<body>
<div id="page-wrapper">
    <div class="row">
        <?php include("include/header.php"); ?>

        <div class="banner-full-row page-banner" style="background-image:url('images/breadcromb.jpg');">
            <div class="container">
                <div class="row">
                    <div class="col-md-6"><h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Submit Property</b></h2></div>
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
                                <div class="form-group"><label>Title</label><input type="text" name="title" class="form-control" required></div>
                                <div class="form-group"><label>Description</label><textarea name="description" class="form-control" required></textarea></div>
                                <div class="form-group"><label>Price</label><input type="number" name="price" class="form-control" required></div>
                                <div class="form-group"><label>Street</label><input type="text" name="street" class="form-control" required></div>

                                <div class="form-group"><label>City</label><input type="text" name="location" class="form-control" required></div>
                                <div class="form-group"><label>State</label><input type="text" name="state" class="form-control" required></div>
                                <div class="form-group"><label>Zip</label><input type="text" name="zip" class="form-control" required></div>

                                <div class="form-group">
                                    <label>Property Type</label>
                                    <select name="property_type" class="form-control" id="property_type" required>
                                        <option value="">Select</option>
                                        <option value="Sale">Sale</option>
                                        <option value="Rental">Rental</option>
                                    </select>
                                </div>
                                <div id="rental_fields" style="display:none;">
                                    <div class="form-group"><label>Available Date</label><input type="date" name="available_date" class="form-control"></div>
                                    <div class="form-group"><label>Security Deposit</label><input type="number" name="security_deposit" class="form-control"></div>
                                </div>
                                <div class="form-group"><label>Pool Available</label>
                                    <select name="pool_available" class="form-control">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="form-group"><label>Dog Friendly</label>
                                    <select name="is_dog_friendly" class="form-control">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>

                                <div class="form-group"><label>Nearest School</label><input type="text" name="nearest_school" class="form-control"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"><label>Bedrooms</label><input type="number" name="bedrooms" class="form-control" required></div>
                                <div class="form-group"><label>Bathrooms</label><input type="number" name="bathrooms" class="form-control" required></div>
                                <div class="form-group"><label>Size (sqft)</label><input type="number" name="size_sqft" class="form-control" required></div>
                                <div class="form-group"><label>Bus Availability</label>
                                    <select name="bus_availability" class="form-control">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="form-group"><label>Tram Availability</label>
                                    <select name="tram_availability" class="form-control">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="form-group"><label>Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="available">Available</option>
                                        <option value="sold">Sold</option>
                                    </select>
                                </div>
                                <div class="form-group"><label>Upload Property Image</label><input type="file" name="images[]" accept="image/*" class="form-control" multiple>
                                </div>
                                <div class="form-group"><label>&nbsp;</label><input type="submit" name="add" value="Submit Property" class="btn btn-info btn-block"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<?php include("include/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/custom.js"></script>
</html>

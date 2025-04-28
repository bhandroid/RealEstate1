<?php 
session_start();
include("config.php");

// Sanitize inputs
$type = isset($_POST['type']) ? mysqli_real_escape_string($con, $_POST['type']) : '';
// $stype = isset($_POST['stype']) ? mysqli_real_escape_string($con, $_POST['stype']) : '';
$city = isset($_POST['city']) ? mysqli_real_escape_string($con, $_POST['city']) : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Meta Tags -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Real Estate PHP">
<meta name="keywords" content="">
<meta name="author" content="Unicoder">
<link rel="shortcut icon" href="images/favicon.ico">

<!--	Fonts
	========================================================-->
<link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">

<!--	Css Link
	========================================================-->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/layerslider.css">
<link rel="stylesheet" type="text/css" href="css/color.css" id="color-change">
<link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
<link rel="stylesheet" type="text/css" href="css/style.css">

<!--	Title
	=========================================================-->
<title>Real Estate PHP</title>
</head>
<body>

<?php include("include/header.php"); ?>

<div class="banner-full-row page-banner" style="background-image:url('images/breadcromb.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="page-name text-white text-uppercase"><b>Filtered Properties</b></h2>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb" class="float-right">
                    <ol class="breadcrumb bg-transparent m-0 p-0">
                        <li class="breadcrumb-item text-white"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active text-white">Filter Property</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="full-row">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="row">

<?php
if (!empty($type) || !empty($city)) {
    $query = mysqli_query($con, "
        SELECT p.*, u.name AS seller_name, 
        (SELECT image_url FROM property_image WHERE property_id = p.property_id LIMIT 1) AS image_url
        FROM property_listings p
        JOIN user u ON p.seller_id = u.user_id
        WHERE p.property_type = '$type' AND p.status = 'available' AND p.location LIKE '%$city%'
    ");

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
?>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <img src="admin/property/<?php echo htmlspecialchars($row['image_url'] ?? 'default.jpg'); ?>" 
                         class="card-img-top" alt="Property Image">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize">
                            <a href="propertydetail.php?pid=<?php echo $row['property_id']; ?>">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </a>
                        </h5>
                        <p class="card-text">
                            $<?php echo number_format($row['price']); ?> | <?php echo $row['size_sqft']; ?> Sqft<br>
                            Location: <?php echo htmlspecialchars($row['location']); ?><br>
                            Seller: <?php echo htmlspecialchars($row['seller_name']); ?><br>
                            Status: <?php echo ucfirst($row['status']); ?>
                        </p>
                    </div>
                    <div class="card-footer text-muted">
                        Added on: <?php echo date('d-m-Y', strtotime($row['created_at'])); ?>
                    </div>
                </div>
            </div>
<?php
        }
    } else {
        echo "<h3 class='text-center w-100'>No Properties Found for Your Search Criteria.</h3>";
    }
} else {
    echo "<h3 class='text-center w-100'>Please provide search criteria.</h3>";
}
?>
                </div>
            </div>

      <!--	Footer   start-->
		<?php include("include/footer.php");?>
		<!--	Footer   start-->
        
        <!-- Scroll to top --> 
        <a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a> 
        <!-- End Scroll To top --> 
    </div>
</div>
<!-- Wrapper End --> 

<!--	Js Link
============================================================--> 
<script src="js/jquery.min.js"></script> 
<!--jQuery Layer Slider --> 
<script src="js/greensock.js"></script> 
<script src="js/layerslider.transitions.js"></script> 
<script src="js/layerslider.kreaturamedia.jquery.js"></script> 
<!--jQuery Layer Slider --> 
<script src="js/popper.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/owl.carousel.min.js"></script> 
<script src="js/tmpl.js"></script> 
<script src="js/jquery.dependClass-0.1.js"></script> 
<script src="js/draggable-0.1.js"></script> 
<script src="js/jquery.slider.js"></script> 
<script src="js/wow.js"></script> 

<script src="js/custom.js"></script>
</body>

</html>

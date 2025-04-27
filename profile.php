<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");
if(!isset($_SESSION['email'])) {
	header("location:login.php");
}
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
<link rel="shortcut icon" href="images/favicon.ico">

<!--	Fonts	-->
<link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">

<!--	Css Link	-->
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

<!--	Title	-->
<title>Real Estate PHP</title>
</head>
<body>

<div id="page-wrapper">
    <div class="row"> 
        <!--	Header start  -->
		<?php include("include/header.php");?>
        <!--	Header end  -->
        
        <!--	Banner   -->
        <div class="banner-full-row page-banner" style="background-image:url('images/breadcromb.jpg');">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Profile</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-left float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!--	Banner End  -->
		 
		<!--	Profile Section   -->
        <div class="full-row">
            <div class="container">
                <div class="row">
					<div class="col-lg-12">
						<h2 class="text-secondary double-down-line text-center">Profile</h2>
                    </div>
				</div>
                <div class="dashboard-personal-info p-5 bg-white">
                    <h5 class="text-secondary border-bottom-on-white pb-3 mb-4 text-center">Profile Information</h5>
                    <div class="row justify-content-center"> <!-- Centered the row -->
                        <div class="col-lg-6 col-md-8 text-center"> <!-- Reduced width and centered -->
                            <?php 
                                $uid = $_SESSION['uid'];
                                $query = mysqli_query($con, "SELECT * FROM `user` WHERE user_id='$uid'");
                                while($row = mysqli_fetch_array($query)) {
                            ?>
                            <div class="font-18">
                                <div class="mb-3 text-capitalize"><b>Name:</b> <?php echo $row['1']; ?></div>
                                <div class="mb-3"><b>Email:</b> <?php echo $row['2']; ?></div>
                                <div class="mb-3"><b>Contact:</b> <?php echo $row['3']; ?></div>
                                <div class="mb-3 text-capitalize"><b>Role:</b> <?php echo $row['5']; ?></div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>            
            </div>
        </div>
		<!--	Profile Section End   -->
        
        <!--	Footer start -->
		<?php include("include/footer.php");?>
		<!--	Footer end -->
        
        <!-- Scroll to top --> 
        <a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a> 
        <!-- End Scroll To top --> 
    </div>
</div>
<!-- Wrapper End --> 

<!--	Js Link	--> 
<script src="js/jquery.min.js"></script> 
<script src="js/greensock.js"></script> 
<script src="js/layerslider.transitions.js"></script> 
<script src="js/layerslider.kreaturamedia.jquery.js"></script> 
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

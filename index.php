<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");
								
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- FOR MORE PROJECTS visit: codeastro.com -->
<!-- Required meta tags -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Meta Tags -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
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

<!--	Page Loader  -->
<!--<div class="page-loader position-fixed z-index-9999 w-100 bg-white vh-100">
	<div class="d-flex justify-content-center y-middle position-relative">
	  <div class="spinner-border" role="status">
		<span class="sr-only">Loading...</span>
	  </div>
	</div>
</div>  -->
<!--	Page Loader  -->

<div id="page-wrapper">
    <div class="row"> 
        <!--	Header start  -->
		<?php include("include/header.php");?>
        <!--	Header end  -->
		
        <!--	Banner Start   -->
        <div class="overlay-black w-100 slider-banner1 position-relative" style="background-image: url('images/banner/rshmpg.jpg'); background-size: cover; background-position: center center; background-repeat: no-repeat;">
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-lg-12">
                        <div class="text-white">
                            <h1 class="mb-4"><span class="text-success">Let us</span><br>
                            Guide you Home</h1><!-- FOR MORE PROJECTS visit: codeastro.com -->
                            <form method="post" action="propertygrid.php">
                                <div class="row">
                                    <div class="col-md-6 col-lg-2">
                                        <div class="form-group">
                                            <select class="form-control" name="type">
                                                <option value="">Select Type</option>
												<option value="Rental">Rental</option>
												<option value="Sale">Sale</option>
												
                                            </select>
                                        </div>
                                    </div>

                                    
                                    
                                    <div class="col-md-8 col-lg-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="city" placeholder="Enter City" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-2">
                                        <div class="form-group">
                                            <button type="submit" name="filter" class="btn btn-success w-100">Search Property</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--	Banner End  -->
        
        <!--	Text Block One
		======================================================-->
        <div class="full-row bg-gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12"><!-- FOR MORE PROJECTS visit: codeastro.com -->
                        <h2 class="text-secondary double-down-line text-center mb-5">What We Do</h2></div>
                </div>
                <div class="text-box-one">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="p-4 text-center hover-bg-white hover-shadow rounded mb-4 transation-3s"> 
								<i class="flaticon-rent text-success flat-medium" aria-hidden="true"></i>
                                <h5 class="text-secondary hover-text-success py-3 m-0"><a href="#">Selling Service</a></h5>
                                <p>This is a </p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="p-4 text-center hover-bg-white hover-shadow rounded mb-4 transation-3s"> 
								<i class="flaticon-for-rent text-success flat-medium" aria-hidden="true"></i>
                                <h5 class="text-secondary hover-text-success py-3 m-0"><a href="#">Rental Service</a></h5>
                                <p>This is a dummy text for filling out spaces. Just some random words...</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="p-4 text-center hover-bg-white hover-shadow rounded mb-4 transation-3s"> 
								<i class="flaticon-list text-success flat-medium" aria-hidden="true"></i>
                                <h5 class="text-secondary hover-text-success py-3 m-0"><a href="#">Property Listing</a></h5>
                                <p>This is a dummy text for filling out spaces. Just some random words...</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="p-4 text-center hover-bg-white hover-shadow rounded mb-4 transation-3s"> 
								<i class="flaticon-diagram text-success flat-medium" aria-hidden="true"></i>
                                <h5 class="text-secondary hover-text-success py-3 m-0"><a href="#">Legal Investment</a></h5>
                                <p>This is a dummy text for filling out spaces. Just some random words...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<!-----  Our Services  ---->
		
        <!--	Recent Properties  -->
<div class="full-row">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-secondary double-down-line text-center mb-4">Recent Properties</h2>
            </div>
            <div class="col-md-12">
                <div class="tab-content mt-4">
                    <div class="tab-pane fade show active">
                        <div class="row">

                        <?php
                        $query = mysqli_query($con, "
                            SELECT 
                                pl.*, 
                                u.name AS seller_name, 
                                u.role AS seller_role, 
                                u.phone_num, 
                                (
                                    SELECT image_url 
                                    FROM property_image 
                                    WHERE property_id = pl.property_id 
                                    LIMIT 1
                                ) AS image
                            FROM property_listings pl
                            JOIN user u ON pl.seller_id = u.user_id
                            WHERE pl.status = 'available'
                            ORDER BY pl.created_at DESC 
                            LIMIT 9
                        ");

                        while ($row = mysqli_fetch_assoc($query)) {
                        ?>

                        <div class="col-md-6 col-lg-4">
                            <div class="featured-thumb hover-zoomer mb-4">
                                <div class="overlay-black overflow-hidden position-relative">
                                    <img src="<?php echo $row['image'] ? 'admin/property/' . $row['image'] : 'images/default-property.jpg'; ?>" alt="Property Image">
                                    <div class="featured bg-success text-white">New</div>
                                    <div class="sale bg-success text-white text-capitalize">For <?php echo $row['property_type']; ?></div>
                                    <div class="price text-primary">
                                        <b>$<?php echo number_format($row['price']); ?></b>
                                        <span class="text-white"><?php echo $row['size_sqft']; ?> Sqft</span>
                                    </div>
                                </div>
                                <div class="featured-thumb-data shadow-one">
                                    <div class="p-3">
                                        <h5 class="text-secondary hover-text-success mb-2 text-capitalize">
                                            <a href="propertydetail.php?pid=<?php echo $row['property_id']; ?>"><?php echo $row['title']; ?></a>
                                        </h5>
                                        <span class="location text-capitalize">
                                            <i class="fas fa-map-marker-alt text-success"></i> <?php echo $row['location']; ?>
                                        </span>
                                    </div>
                                    <div class="bg-gray quantity px-4 pt-4">
                                        <ul>
                                            <li><span><?php echo $row['size_sqft']; ?></span> Sqft</li>
                                            <li><span><?php echo $row['bedrooms']; ?></span> Beds</li>
                                            <li><span><?php echo $row['bathrooms']; ?></span> Baths</li>
                                        </ul>
                                    </div>
                                    <div class="p-4 d-inline-block w-100">
                                        <div class="float-left text-capitalize">
                                            <i class="fas fa-user text-success mr-1"></i> By : <?php echo $row['seller_name']; ?>
                                        </div>
                                        <div class="float-right">
                                            <i class="far fa-calendar-alt text-success mr-1"></i> 
                                            <?php echo date('d-m-Y', strtotime($row['created_at'])); ?>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- Recent Properties -->

        
        <!--	Why Choose Us -->
        <div class="full-row living bg-one overlay-secondary-half" style="background-image: url('images/01.jpg'); background-size: cover; background-position: center center; background-repeat: no-repeat;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="living-list pr-4">
                            <h3 class="pb-4 mb-3 text-white">Why Choose Us</h3>
                            <ul>
                                <li class="mb-4 text-white d-flex"> 
									<i class="flaticon-reward flat-medium float-left d-table mr-4 text-success" aria-hidden="true"></i>
									<div class="pl-2">
										<h5 class="mb-3">Top Rated</h5>
										<p>This is a dummy text for filling out spaces. This is just a dummy text for filling out blank spaces.</p>
									</div>
                                </li>
                                <li class="mb-4 text-white d-flex"> 
									<i class="flaticon-real-estate flat-medium float-left d-table mr-4 text-success" aria-hidden="true"></i>
									<div class="pl-2">
										<h5 class="mb-3">Experience Quality</h5>
										<p>This is a dummy text for filling out spaces. This is just a dummy text for filling out blank spaces.</p>
									</div>
                                </li>
                                <li class="mb-4 text-white d-flex"> 
									<i class="flaticon-seller flat-medium float-left d-table mr-4 text-success" aria-hidden="true"></i>
									<div class="pl-2">
										<h5 class="mb-3">Experienced Agents</h5>
										<p>This is a dummy text for filling out spaces. This is just a dummy text for filling out blank spaces.</p>
									</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<!--	why choose us -->
		
		<!--	How it work -->
        <div class="full-row">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-secondary double-down-line text-center mb-5">How It Work</h2>
                        </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="icon-thumb-one text-center mb-5">
                            <div class="bg-success text-white rounded-circle position-absolute z-index-9">1</div>
                            <div class="left-arrow"><i class="flaticon-investor flat-medium icon-success" aria-hidden="true"></i></div>
                            <h5 class="text-secondary mt-5 mb-4">Discussion</h5>
                            <p>Nascetur cubilia sociosqu aliquet ut elit nascetur nullam duis tincidunt nisl non quisque vestibulum platea ornare ridiculus.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="icon-thumb-one text-center mb-5">
                            <div class="bg-success text-white rounded-circle position-absolute z-index-9">2</div>
                            <div class="left-arrow"><i class="flaticon-search flat-medium icon-success" aria-hidden="true"></i></div>
                            <h5 class="text-secondary mt-5 mb-4">Files Review</h5>
                            <p>Nascetur cubilia sociosqu aliquet ut elit nascetur nullam duis tincidunt nisl non quisque vestibulum platea ornare ridiculus.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="icon-thumb-one text-center mb-5">
                            <div class="bg-success text-white rounded-circle position-absolute z-index-9">3</div>
                            <div><i class="flaticon-handshake flat-medium icon-success" aria-hidden="true"></i></div>
                            <h5 class="text-secondary mt-5 mb-4">Acquire</h5>
                            <p>Nascetur cubilia sociosqu aliquet ut elit nascetur nullam duis tincidunt nisl non quisque vestibulum platea ornare ridiculus.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!--	How It Work -->
        
      
        <!--	Footer   start-->
		<?php include("include/footer.php");?>
		<!--	Footer   start-->
        
        
        <!-- Scroll to top --> 
        <a href="#" class="bg-success text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a> 
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
<script src="js/YouTubePopUp.jquery.js"></script> 
<script src="js/validate.js"></script> 
<script src="js/jquery.cookie.js"></script> 
<script src="js/custom.js"></script>




<!-- 🔹 Floating "Need Help?" Button -->
<button id="chatbot-toggle" 
    style="position: fixed; bottom: 20px; right: 20px; background: #28a745; color: white; border: none; padding: 12px 20px; border-radius: 50px; font-weight: bold; z-index: 999;">
    💬 Need Help?
</button>

<!-- 🔹 Chatbot Popup (Initially Hidden) -->
<div id="chatbot-popup" style="display: none; position: fixed; bottom: 0; right: 20px; width: 300px; background: #fff; border: 1px solid #ccc; border-radius: 10px; overflow: hidden; box-shadow: 0px 0px 10px rgba(0,0,0,0.2); z-index: 1000;">
    <div class="chatbot-header" style="background: #28a745; color: white; padding: 10px; font-weight: bold;">
        Need Help?
        <span style="float: right; cursor: pointer;" onclick="closeChatbot()">❌</span>
    </div>
    <div id="chatbox" style="height: 250px; overflow-y: auto; padding: 10px; background: #f9f9f9;">
        <!-- Messages will appear here -->
    </div>
    <div style="padding: 10px;">
        <input type="text" id="userInput" class="form-control" placeholder="Type your message...">
        <button class="btn btn-success btn-chat mt-2 w-100">Send</button>
    </div>
</div>

<!-- 🔹 JavaScript to handle Chatbot -->
<script>
document.getElementById('chatbot-toggle').addEventListener('click', function() {
    var popup = document.getElementById('chatbot-popup');
    popup.style.display = 'block';
    this.style.display = 'none'; // Hide "Need Help?" button
});

function closeChatbot() {
    document.getElementById('chatbot-popup').style.display = 'none';
    document.getElementById('chatbot-toggle').style.display = 'block';
}

$(document).ready(function() {
    // When user clicks Send
    $(".btn-chat").click(function() {
        sendMessage();
    });

    // Or when user presses Enter key
    $("#userInput").keypress(function(e) {
        if (e.which == 13) { // Enter key pressed
            sendMessage();
            return false;
        }
    });

    function sendMessage() {
        var userMessage = $("#userInput").val().trim();
        if (userMessage === "") return;

        // Show user's message immediately
        $("#chatbox").append('<div class="mb-2"><strong>You:</strong> ' + userMessage + '</div>');

        // Send message to backend chatbot.php
        $.ajax({
            url: "chatbot.php",
            method: "POST",
            data: { message: userMessage },
            success: function(response) {
                $("#chatbox").append('<div class="mb-2"><strong>Bot:</strong> ' + response + '</div>');
                $("#userInput").val(''); // Clear input
                $("#chatbox").scrollTop($("#chatbox")[0].scrollHeight); // Auto-scroll
            },
            error: function(xhr, status, error) {
                $("#chatbox").append('<div class="mb-2"><strong>Bot:</strong> Oops! Something went wrong.</div>');
            }
        });
    }
});
</script>

</body>
</html>
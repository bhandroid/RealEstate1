<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");

$user_id = $_SESSION['uid'] ?? null;
$role = strtolower($_SESSION['role'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Real Estate PHP - Available Properties</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div id="page-wrapper">
    <div class="row">
        <?php include("include/header.php"); ?>

        <div class="full-row">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <?php 
                            $query = mysqli_query($con, "
                                SELECT property_listings.*, user.name AS uname, user.role AS utype, 
                                (SELECT image_url FROM property_image WHERE property_image.property_id = property_listings.property_id LIMIT 1) AS image_url 
                                FROM property_listings 
                                JOIN user ON property_listings.seller_id = user.user_id 
                                WHERE property_listings.status IN ('available', 'hold')
                            ");
                            
                            while($row = mysqli_fetch_assoc($query)) {
                                $property_id = $row['property_id'];
                                $seller_id = $row['seller_id'];
                                $image = $row['image_url'] ?? 'default.jpg';

                                // Skip own properties for buyers only
                                if ($seller_id == $user_id && $role === 'buyer') continue; 
                            ?>
                            <div class="col-md-6">
                                <div class="featured-thumb hover-zoomer mb-4">
                                    <div class="overlay-black overflow-hidden position-relative">
                                        <img src="admin/property/<?php echo htmlspecialchars($image); ?>" alt="pimage" style="height:250px; width:100%;">
                                        <div class="sale bg-success text-white">For <?php echo htmlspecialchars($row['property_type']); ?></div>
                                        <div class="price text-primary text-capitalize">
                                            ₹<?php echo number_format($row['price']); ?> <span class="text-white"><?php echo htmlspecialchars($row['size_sqft']); ?> Sqft</span>
                                        </div>
                                    </div>
                                    <div class="featured-thumb-data shadow-one">
                                        <div class="p-4">
                                            <h5 class="text-secondary hover-text-success mb-2 text-capitalize">
                                                <a href="propertydetail.php?pid=<?php echo $property_id; ?>"><?php echo htmlspecialchars($row['title']); ?></a>
                                            </h5>
                                            <span class="location text-capitalize">
                                                <i class="fas fa-map-marker-alt text-success"></i> <?php echo htmlspecialchars($row['location']); ?>
                                            </span>
                                        </div>
                                        <div class="px-4 pb-4 d-inline-block w-100">
                                            <div class="float-left text-capitalize"><i class="fas fa-user text-success mr-1"></i>By : <?php echo $row['uname'];?></div>
                                            <div class="float-right">
                                                <i class="far fa-calendar-alt text-success mr-1"></i> 
                                                <?php 
                                                echo isset($row['created_at']) && strtotime($row['created_at']) 
                                                    ? date('d-m-Y', strtotime($row['created_at'])) 
                                                    : 'Not Available';
                                                ?>
                                            </div>
                                        </div>
                                        <!-- ✅ Booking & Favorites Buttons -->
                                        <div class="px-4 pb-4">
                                            <?php 
                                            if (in_array($role, ['buyer', 'agent'])) {
                                                echo "<a href='appointment.php?property_id=$property_id' class='btn btn-primary btn-block'>📅 Book Appointment</a>";
                                            }
                                            if ($user_id) { // Only logged-in users can add to favorites
                                                echo "<form method='POST' action='favorite_add.php' class='mt-2'>
                                                        <input type='hidden' name='property_id' value='$property_id'>
                                                        <button type='submit' class='btn btn-outline-danger btn-block'>❤ Add to Favorites</button>
                                                      </form>";
                                            }
                                            ?>
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

        <?php include("include/footer.php"); ?>
        <a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a>
    </div>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>
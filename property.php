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
<meta http-equiv="X-UA-Compatible" content="IE=edge"><title>Real Estate PHP - Available Properties</title>
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
<style>
    body {
        background-color: #f0f2f5;
        font-family: 'Poppins', sans-serif;
    }
    .property-card {
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background-color: #fff;
    }
    .property-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .property-image {
        height: 220px;
        object-fit: cover;
        border-bottom: 1px solid #eee;
    }
    .badge-sale {
    background: linear-gradient(135deg, #28a745, #218838); /* Green for Sale */
    color: #fff;
    border-radius: 20px;
    padding: 6px 18px;
    font-size: 0.85rem;
    position: absolute;
    top: 15px;
    left: 15px;
    z-index: 10; /* 🟢 Ensure it stays on top of the image */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
}
.badge-rent {
    background: linear-gradient(135deg, #dc3545, #c82333); /* Red for Rent */
    color: #fff;
    border-radius: 20px;
    padding: 6px 18px;
    font-size: 0.85rem;
    position: absolute;
    top: 15px;
    left: 15px;
    z-index: 10;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
}

    .btn-book {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        border: none;
        transition: background 0.3s ease;
    }
    .btn-book:hover {
        background: linear-gradient(135deg, #0056b3, #003f7f);
    }
    .btn-favorite {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        border: none;
        transition: background 0.3s ease;
    }
    .btn-favorite:hover {
        background: linear-gradient(135deg, #c82333, #a71d2a);
    }
    .property-info {
        padding: 15px;
    }
    .property-title {
        font-weight: 600;
        font-size: 1.2rem;
    }
    .property-footer {
        padding: 0 15px 15px;
    }
    .btn-block {
        width: 100%;
    }
</style>
</head>
<body>

<?php include("include/header.php"); ?>

<div class="container mt-5">
    <h3 class="mb-4 text-center text-primary"> Available Properties </h3>
    <div class="row">
        <?php 
        $query = mysqli_query($con, "
        SELECT property_listings.*, user.name AS uname, user.role AS utype
        FROM property_listings 
        JOIN user ON property_listings.seller_id = user.user_id 
        WHERE property_listings.status IN ('available', 'hold')
        ");
    
        
        while($row = mysqli_fetch_assoc($query)) {
            $property_id = $row['property_id'];
            $seller_id = $row['seller_id'];
            $image = $row['image_url'] ?? 'default.jpg';

            if (in_array($role, ['buyer', 'agent']) && $seller_id == $user_id) continue;
 
        ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="property-card shadow-sm position-relative">
            <?php 
$type = strtolower($row['property_type']);
$badgeClass = ($type === 'rental' || $type === 'rent') ? 'badge-rent' : 'badge-sale';
?>
<span class="badge <?= $badgeClass; ?>">
    <?= ($type === 'rental' || $type === 'rent') ? 'For Rent' : 'For Sale'; ?>
</span>
                <?php
$imgQuery = mysqli_query($con, "SELECT image_url FROM property_image WHERE property_id = $property_id");
if (mysqli_num_rows($imgQuery) > 0) {
?>
    <div id="carousel<?= $property_id; ?>" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php 
            $activeSet = false;
            while ($imgRow = mysqli_fetch_assoc($imgQuery)) {
                $imgUrl = $imgRow['image_url'];
            ?>
                <div class="carousel-item <?= !$activeSet ? 'active' : ''; ?>">
                    <img src="admin/property/<?= htmlspecialchars($imgUrl); ?>" alt="Property Image" class="property-image w-100">
                </div>
            <?php 
                $activeSet = true;
            } 
            ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?= $property_id; ?>" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carousel<?= $property_id; ?>" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
<?php 
} else {
    // If no image, show default image
    echo '<img src="admin/property/default.jpg" alt="No Image Available" class="property-image w-100">';
}
?>
                <div class="property-info">
                    <h5 class="property-title">
                        <a href="propertydetail.php?pid=<?= $property_id; ?>" class="text-decoration-none text-dark">
                            <?= htmlspecialchars($row['title']); ?>
                        </a>
                    </h5>
                    <p class="text-muted mb-1">
                        $<?= number_format($row['price']); ?> | <?= htmlspecialchars($row['size_sqft']); ?> Sqft
                    </p>
                    <p class="text-muted mb-2">
                        📍 <?= htmlspecialchars($row['location']); ?>
                    </p>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>By: <?= $row['uname']; ?></span>
                        <span>
                        <?php 
                        echo isset($row['created_at']) && strtotime($row['created_at']) 
                            ? date('d-m-Y', strtotime($row['created_at'])) 
                            : 'Not Available';
                        ?>
                        </span>
                    </div>
                </div>
                <div class="property-footer">
                    <?php 
                    if (in_array($role, ['buyer', 'agent'])) {
                        echo "<a href='appointment.php?property_id=$property_id' class='btn btn-book btn-sm btn-block mb-2'>📅 Book Appointment</a>";
                    }
                    if ($user_id) {
                        echo "<form method='POST' action='favorite_add.php'>
                                <input type='hidden' name='property_id' value='$property_id'>
                                <button type='submit' class='btn btn-favorite btn-sm btn-block'>❤ Add to Favorites</button>
                              </form>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<?php include("include/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>

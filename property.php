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
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Real Estate PHP - Available Properties</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
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
        background: linear-gradient(135deg, #28a745, #218838);
        color: #fff;
        border-radius: 30px;
        padding: 5px 15px;
        font-size: 0.85rem;
        position: absolute;
        top: 15px;
        left: 15px;
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
    <h3 class="mb-4 text-center text-primary">✨ Available Properties ✨</h3>
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

            if ($seller_id == $user_id && $role === 'buyer') continue; 
        ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="property-card shadow-sm position-relative">
                <span class="badge badge-sale">For <?= htmlspecialchars($row['property_type']); ?></span>
                <img src="admin/property/<?= htmlspecialchars($image); ?>" alt="Property Image" class="property-image w-100">
                <div class="property-info">
                    <h5 class="property-title">
                        <a href="propertydetail.php?pid=<?= $property_id; ?>" class="text-decoration-none text-dark">
                            <?= htmlspecialchars($row['title']); ?>
                        </a>
                    </h5>
                    <p class="text-muted mb-1">
                        ₹<?= number_format($row['price']); ?> | <?= htmlspecialchars($row['size_sqft']); ?> Sqft
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

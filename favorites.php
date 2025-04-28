<?php
session_start();
include("config.php");

if (!isset($_SESSION['uid'])) {
    echo "🔒 Please log in to view your favorites.";
    exit();
}

$user_id = $_SESSION['uid'];
$query = mysqli_query($con, "
    SELECT f.date, p.*, 
        (SELECT image_url FROM property_image WHERE property_id = p.property_id LIMIT 1) AS image_url 
    FROM favorite f
    JOIN property_listings p ON f.property_id = p.property_id
    WHERE f.user_id = $user_id
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>My Favorites - Real Estate Portal</title>
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
            transition: all 0.3s ease;
            background-color: #fff;
        }
        .property-card:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            transform: translateY(-6px);
        }
        .property-img {
            height: 220px;
            object-fit: cover;
        }
        .property-title {
            font-weight: 600;
            font-size: 1.2rem;
        }
        .btn-details {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border: none;
        }
        .btn-details:hover {
            background: linear-gradient(135deg, #0056b3, #003f7f);
        }
    </style>
</head>
<body>

<?php include("include/header.php"); ?>

<div class="container mt-5">
    <h3 class="mb-4 text-center text-primary"> My Favorite Properties</h3>

    <div class="row">
        <?php if (mysqli_num_rows($query) == 0): ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">No favorite properties yet. Start exploring!</div>
            </div>
        <?php endif; ?>

        <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card property-card h-100 shadow-sm">
                    <img src="admin/property/<?= htmlspecialchars($row['image_url'] ?? 'default.jpg'); ?>" 
                         class="property-img w-100" alt="Property Image">
                    <div class="card-body">
                        <h5 class="property-title"><?= htmlspecialchars($row['title']) ?></h5>
                        <p class="card-text text-muted">
                            $<?= number_format($row['price']) ?><br>
                            📍 <?= htmlspecialchars($row['location']) ?><br>
                            🧱 <?= $row['size_sqft'] ?> Sqft
                        </p>
                        <a href="propertydetail.php?pid=<?= $row['property_id'] ?>" class="btn btn-details btn-sm w-100 mt-2">🔍 View Details</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include("include/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

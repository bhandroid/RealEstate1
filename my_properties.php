<?php
session_start();
include("config.php");

$role = $_SESSION['role'] ?? '';
$user_id = $_SESSION['uid'] ?? null;

if (!$user_id || !in_array($role, ['Seller', 'Agent'])) {
    $_SESSION['error'] = "Access Denied. You must be a Seller or Agent.";
    header("Location: dashboard.php");
    exit();
}

$result = mysqli_query($con, "
    SELECT p.*, 
        (SELECT image_url FROM property_image WHERE property_id = p.property_id LIMIT 1) AS image_url 
    FROM property_listings p 
    WHERE p.seller_id = $user_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>My Properties - Real Estate Portal</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include("include/header.php"); ?>

<div class="container mt-5">
    <h3 class="mb-4">🏠 My Posted Properties</h3>

    <?php if (mysqli_num_rows($result) == 0): ?>
        <div class="alert alert-warning">You have not posted any properties yet.</div>
    <?php endif; ?>

    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="admin/property/<?= htmlspecialchars($row['image_url'] ?? 'default.jpg') ?>" 
                     class="card-img-top" alt="Property Image">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                    <p class="card-text">
                        <strong>₹<?= number_format($row['price']) ?></strong><br>
                        <?= htmlspecialchars($row['location']) ?><br>
                        <?= htmlspecialchars($row['size_sqft']) ?> Sqft
                    </p>
                    <a href="view_appointments.php?property_id=<?= $row['property_id'] ?>" class="btn btn-info btn-sm">
                        📋 View Appointments
                    </a>
                    <a href="edit_property.php?property_id=<?= $row['property_id'] ?>" class="btn btn-warning btn-sm ml-2">
                        ✏️ Edit Property
                    </a>
                    <a href="view_offers.php?property_id=<?= $row['property_id'] ?>" class="btn btn-success btn-sm mt-2">
                        💰 View Offers
                    </a>
                    <a href="submitpropertydelete.php?id=<?= $row['property_id'] ?>" 
                        onclick="return confirm('Are you sure you want to delete this property?');" 
                        class="btn btn-danger btn-sm mt-2 ml-2">
                        🗑️ Delete Property
                    </a>
                    
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include("include/footer.php"); ?>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/custom.js"></script>

</body>
</html>

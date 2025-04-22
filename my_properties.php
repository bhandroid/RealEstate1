<?php
session_start();
include("config.php");

$role = $_SESSION['role'] ?? '';
$user_id = $_SESSION['uid'] ?? null;

if (!$user_id || !in_array($role, ['Seller', 'Agent'])) {
    die("❌ Access denied.");
}

$result = mysqli_query($con, "
    SELECT p.*, 
        (SELECT image_url FROM property_image WHERE property_id = p.property_id LIMIT 1) AS image_url 
    FROM property_listings p 
    WHERE p.seller_id = $user_id
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Properties</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h3>🏠 My Posted Properties</h3>

    <?php if (mysqli_num_rows($result) == 0): ?>
        <div class="alert alert-warning">You have not posted any properties yet.</div>
    <?php endif; ?>

    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <img src="admin/property/<?= htmlspecialchars($row['image_url'] ?? 'default.jpg') ?>" class="card-img-top" alt="Image">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                    <p class="card-text">
                        ₹<?= number_format($row['price']) ?><br>
                        <?= $row['location'] ?><br>
                        <?= $row['size_sqft'] ?> Sqft
                    </p>
                    <a href="view_appointments.php?property_id=<?= $row['property_id'] ?>" class="btn btn-info btn-sm">📋 View Appointments</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
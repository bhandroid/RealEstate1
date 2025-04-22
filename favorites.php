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
<html>
<head>
    <title>My Favorites</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h3>❤️ My Favorite Properties</h3>
    <div class="row">
        <?php if (mysqli_num_rows($query) == 0): ?>
            <div class="col-12 alert alert-warning">No favorites yet.</div>
        <?php endif; ?>

        <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <img src="admin/property/<?php echo htmlspecialchars($row['image_url'] ?? 'default.jpg'); ?>" class="card-img-top" alt="Image">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                        <p class="card-text">
                            ₹<?= number_format($row['price']) ?><br>
                            <?= htmlspecialchars($row['location']) ?><br>
                            <?= $row['size_sqft'] ?> sqft
                        </p>
                        <a href="propertydetail.php?pid=<?= $row['property_id'] ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>

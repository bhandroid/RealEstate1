<?php
session_start();
include("config.php");

$role = $_SESSION['role'] ?? '';
$user_id = $_SESSION['uid'] ?? null;
if (!$user_id || !in_array($role, ['Buyer', 'Agent'])) {
    $_SESSION['error'] = "Access Denied. Only Buyers or Agents can view this page.";
    header("Location: index.php");
    exit();
}

// Query for Purchased Properties (Sale)
$query_sale = "
    SELECT 
        p.*, 
        (SELECT image_url FROM property_image WHERE property_id = p.property_id LIMIT 1) AS image_url,
        pay.payment_date, 
        pay.status AS payment_status, 
        'Sale' AS transaction_type
    FROM property_listings p
    JOIN offer o ON p.property_id = o.property_id
    JOIN payment pay ON o.offer_id = pay.offer_id
    WHERE o.buyer_id = $user_id 
    AND pay.status = 'Completed'
    AND pay.payment_type = 'Sale'
";

// Query for Rented Properties (Rental)
$query_rental = "
    SELECT 
        p.*, 
        (SELECT image_url FROM property_image WHERE property_id = p.property_id LIMIT 1) AS image_url,
        pay.payment_date, 
        pay.status AS payment_status, 
        'Rental' AS transaction_type
    FROM property_listings p
    JOIN rental_interest ri ON p.property_id = ri.property_id
    JOIN payment pay ON ri.interest_id = pay.rental_interest_id
    WHERE ri.buyer_id = $user_id 
    AND pay.status = 'Completed'
    AND pay.payment_type = 'Rental'
";

// Combine Sale and Rental using UNION ALL
$final_query = "$query_sale UNION ALL $query_rental ORDER BY payment_date DESC";
$result = mysqli_query($con, $final_query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Bought Properties - Real Estate Portal</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
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
        body { background: linear-gradient(135deg, #f5f7fa, #c3cfe2); font-family: 'Poppins', sans-serif; }
        .property-card { border-radius: 20px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.15); background: #fff; }
        .property-image { height: 230px; object-fit: cover; width: 100%; }
        .card-footer { background-color: #fff; border-top: none; text-align: center; }
        h3.title-heading { background: linear-gradient(135deg, rgb(0, 0, 0), #836fff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>

<?php include("include/header.php"); ?>

<div class="container my-5">
    <h3 class="title-heading">  My Bought Properties</h3>

    <?php if (mysqli_num_rows($result) == 0): ?>
        <div class="alert alert-warning text-center">🚫 You have not bought any properties yet.</div>
    <?php else: ?>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card property-card h-100">
                    <img src="admin/property/<?= htmlspecialchars($row['image_url'] ?? 'default.jpg') ?>" 
                         class="property-image" alt="Property Image">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                        <p class="card-text text-muted mb-3">
                            <strong>$<?= number_format($row['price']) ?></strong><br>
                            <?= htmlspecialchars($row['location']) ?><br>
                            <?= htmlspecialchars($row['size_sqft']) ?> Sqft
                        </p>
                    </div>
                    <div class="card-footer">
                    <p class="mb-1">
                        <strong>Type:</strong> <?= $row['transaction_type'] ?> <br>
                        <strong>Payment Status:</strong> <?= $row['payment_status'] ?>
                    </p>
                        <p class="mb-0"><strong>Payment Date:</strong> <?= date('d-m-Y', strtotime($row['payment_date'])) ?></p>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<?php include("include/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>

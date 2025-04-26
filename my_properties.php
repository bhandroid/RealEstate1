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
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>My Properties - Real Estate Portal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<style>
    body {
        background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
        font-family: 'Poppins', sans-serif;
    }
    .property-card {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #fff;
        position: relative;
    }
    .property-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }
    .property-image {
        height: 230px;
        object-fit: cover;
        width: 100%;
        border-bottom: 5px solid #6c63ff;
    }
    .edit-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #6c63ff, #836fff);
        border: none;
        border-radius: 50%;
        padding: 10px 12px;
        color: #fff;
        text-decoration: none;
        font-size: 1.2rem;
        transition: background 0.3s ease;
    }
    .edit-btn:hover {
        background: linear-gradient(135deg, #4e4eff, #635bff);
    }
    .btn-group-custom a {
        margin: 5px;
        font-weight: 500;
        border-radius: 50px;
        padding: 8px 18px;
        transition: all 0.3s ease;
        border: none;
    }
    .btn-group-custom a.btn-primary {
        background: linear-gradient(135deg, #00c9ff, #92fe9d);
        color: #fff;
    }
    .btn-group-custom a.btn-secondary {
        background: linear-gradient(135deg, #ff758c, #ff7eb3);
        color: #fff;
    }
    .btn-group-custom a.btn-success {
        background: linear-gradient(135deg, #43e97b, #38f9d7);
        color: #fff;
    }
    .btn-group-custom a.btn-danger {
        background: linear-gradient(135deg, #ff512f, #dd2476);
        color: #fff;
    }
    .btn-group-custom a:hover {
        opacity: 0.85;
        transform: scale(1.05);
    }
    .card-footer {
        background-color: #fff;
        border-top: none;
        text-align: center;
    }
    h3.title-heading {
        background: linear-gradient(135deg, #6c63ff, #836fff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 700;
        text-align: center;
        margin-bottom: 30px;
    }
</style>
</head>
<body>

<?php include("include/header.php"); ?>

<div class="container my-5">
    <h3 class="title-heading">✨ My Posted Properties</h3>

    <?php if (mysqli_num_rows($result) == 0): ?>
        <div class="alert alert-warning text-center">🚫 You have not posted any properties yet.</div>
    <?php endif; ?>

    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card property-card h-100">
                <!-- Edit Button -->
                <a href="edit_property.php?property_id=<?= $row['property_id'] ?>" class="edit-btn" title="Edit Property">
                    ✏️
                </a>
                <img src="admin/property/<?= htmlspecialchars($row['image_url'] ?? 'default.jpg') ?>" 
                     class="property-image" alt="Property Image">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                    <p class="card-text text-muted mb-3">
                        <strong>₹<?= number_format($row['price']) ?></strong><br>
                        <?= htmlspecialchars($row['location']) ?><br>
                        <?= htmlspecialchars($row['size_sqft']) ?> Sqft
                    </p>
                </div>
                <div class="card-footer">
                    <div class="btn-group-custom d-flex flex-wrap justify-content-center">
                        <a href="view_appointments.php?property_id=<?= $row['property_id'] ?>" class="btn btn-primary btn-sm">
                            📋 Appointments
                        </a>
                        <?php if (strtolower($row['property_type']) === 'rental'): ?>
                            <a href="view_rental_interest.php?property_id=<?= $row['property_id'] ?>" class="btn btn-secondary btn-sm">
                                🏷️ Rental Interests
                            </a>
                        <?php else: ?>
                            <a href="view_offers.php?property_id=<?= $row['property_id'] ?>" class="btn btn-success btn-sm">
                                💰 Offers
                            </a>
                        <?php endif; ?>
                        <a href="submitpropertydelete.php?id=<?= $row['property_id'] ?>" 
                           onclick="return confirm('Are you sure you want to delete this property?');" 
                           class="btn btn-danger btn-sm">
                            🗑️ Delete
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include("include/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/custom.js"></script>

</body>
</html>

<?php
session_start();
include("config.php");

$role = $_SESSION['role'] ?? '';
$seller_id = $_SESSION['uid'] ?? null;
$property_id = isset($_GET['property_id']) ? (int) $_GET['property_id'] : 0;

// Security check: only Seller/Agent can access their own property offers
if (!$seller_id || !in_array($role, ['Seller', 'Agent'])) {
    $_SESSION['error'] = "Access Denied.";
    header("Location: dashboard.php");
    exit();
}

// Check if the property belongs to the seller
$check = mysqli_query($con, "SELECT * FROM property_listings WHERE property_id = $property_id AND seller_id = $seller_id");
if (mysqli_num_rows($check) == 0) {
    echo "<div class='container mt-5 alert alert-danger'>Access denied. You don’t own this property.</div>";
    exit();
}

// Fetch offers for this property
$offers = mysqli_query($con, "
    SELECT o.*, u.name AS buyer_name, u.email AS buyer_email, u.phone_num AS buyer_phone
    FROM offer o
    JOIN user u ON o.buyer_id = u.user_id
    WHERE o.property_id = $property_id
    ORDER BY o.offer_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Offers for Property #<?= $property_id ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<?php include("include/header.php"); ?>

<div class="container mt-5">
    <h3>💰 Offers for Property ID: <?= $property_id ?></h3>

    <?php if (mysqli_num_rows($offers) == 0): ?>
        <div class="alert alert-info mt-3">No offers have been made for this property yet.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>Buyer Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Offer Price (₹)</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($offer = mysqli_fetch_assoc($offers)): ?>
                    <tr>
                        <td><?= htmlspecialchars($offer['buyer_name']) ?></td>
                        <td><?= htmlspecialchars($offer['buyer_email']) ?></td>
                        <td><?= htmlspecialchars($offer['buyer_phone']) ?></td>
                        <td><?= number_format($offer['offer_price']) ?></td>
                        <td><?= date("d M Y", strtotime($offer['offer_date'])) ?></td>
                        <td>
                            <?php if ($offer['status'] === 'Accepted'): ?>
                                <span class="badge badge-success">Accepted</span>
                            <?php elseif ($offer['status'] === 'Rejected'): ?>
                                <span class="badge badge-danger">Rejected</span>
                            <?php elseif ($offer['status'] === 'Sold'): ?>
                                <span class="badge badge-dark">Sold</span>
                            <?php else: ?>
                                <span class="badge badge-warning">Pending</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="my_properties.php" class="btn btn-secondary mt-3">← Back to My Properties</a>
</div>

<?php include("include/footer.php"); ?>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>

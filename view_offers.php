<?php
session_start();
include("config.php");

$role = $_SESSION['role'] ?? '';
$seller_id = $_SESSION['uid'] ?? null;
$property_id = isset($_GET['property_id']) ? (int) $_GET['property_id'] : 0;

if (!$seller_id || !in_array($role, ['Seller', 'Agent'])) {
    $_SESSION['error'] = "Access Denied.";
    header("Location: dashboard.php");
    exit();
}

$check = mysqli_query($con, "SELECT * FROM property_listings WHERE property_id = $property_id AND seller_id = $seller_id");
if (mysqli_num_rows($check) == 0) {
    echo "<div class='container mt-5 alert alert-danger'>Access denied. You don’t own this property.</div>";
    exit();
}

// Handle accept/reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['offer_id'])) {
    $offer_id = (int) $_POST['offer_id'];

    if (isset($_POST['accept'])) {
        // Accept selected offer, reject others, mark property as Hold
        mysqli_query($con, "UPDATE offer SET status = 'Accepted' WHERE offer_id = $offer_id");
        mysqli_query($con, "UPDATE offer SET status = 'Rejected' WHERE property_id = $property_id AND offer_id != $offer_id");
        mysqli_query($con, "UPDATE property_listings SET status = 'Hold' WHERE property_id = $property_id");
        $_SESSION['message'] = "Offer accepted. Property status set to Hold.";
    } elseif (isset($_POST['reject'])) {
        mysqli_query($con, "UPDATE offer SET status = 'Rejected' WHERE offer_id = $offer_id");
        $_SESSION['message'] = "Offer rejected.";
    }

    header("Location: view_offers.php?property_id=$property_id");
    exit();
}

// Fetch offers
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/layerslider.css">
<link rel="stylesheet" type="text/css" href="css/color.css" id="color-change">
<link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
<link rel="stylesheet" type="text/css" href="css/style.css">

</head>
<body>

<?php include("include/header.php"); ?>

<div class="container mt-5">
    <h3>💰 Offers for Property ID: <?= $property_id ?></h3>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($offers) == 0): ?>
        <div class="alert alert-info mt-3">No offers have been made for this property yet.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>Buyer Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Offer Price ($)</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
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
                    <td>
                        <?php if ($offer['status'] === 'Pending'): ?>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="offer_id" value="<?= $offer['offer_id'] ?>">
                                <button type="submit" name="accept" class="btn btn-success btn-sm">Accept</button>
                                <button type="submit" name="reject" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        <?php else: ?>
                            <span class="text-muted">—</span>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>

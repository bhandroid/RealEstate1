<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
ob_start();
include("config.php");

$pid = isset($_GET['pid']) ? (int) $_GET['pid'] : 0;
$query = mysqli_query($con, "
    SELECT property_listings.*, user.name AS uname, user.email AS uemail, user.phone_num AS uphone, user.role, user.user_id
    FROM property_listings
    JOIN user ON property_listings.seller_id = user.user_id
    WHERE property_listings.property_id = '$pid'
") or die("Query Failed: " . mysqli_error($con));

if(mysqli_num_rows($query) == 0) {
    echo "<h2 class='text-center'>Property not found</h2>";
    exit;
}
$row = mysqli_fetch_assoc($query);
$property_type = strtolower($row['property_type']);
$property_id = $pid;
$buyer_id = $_SESSION['user_id'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<title><?php echo htmlspecialchars($row['title']); ?> - Property Details</title>
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
        background: linear-gradient(135deg, #f0f4fd, #d9e4ff);
        font-family: 'Poppins', sans-serif;
    }
    .property-box {
        background: #fff;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        margin-top: 40px;
        transition: all 0.3s ease-in-out;
    }
    .property-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }
    h2, h4 {
        background: linear-gradient(135deg, #6a11cb, #2575fc);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 700;
    }
    .btn-gradient {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        border: none;
        border-radius: 50px;
        padding: 10px 20px;
        transition: all 0.3s ease;
    }
    .btn-gradient:hover {
        background: linear-gradient(135deg, #5563de, #5f4caf);
        transform: scale(1.05);
    }
    .btn-gradient-secondary {
        background: linear-gradient(135deg, #ff758c, #ff7eb3);
        color: #fff;
    }
    .btn-gradient-secondary:hover {
        background: linear-gradient(135deg, #ff5e7e, #ff6699);
        transform: scale(1.05);
    }
    .alert-info, .alert-success {
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .back-btn {
        margin-top: 30px;
        display: inline-block;
        text-decoration: none;
        color: #fff;
        background: linear-gradient(135deg, #43e97b, #38f9d7);
        padding: 10px 25px;
        border-radius: 50px;
        transition: 0.3s;
    }
    .back-btn:hover {
        background: linear-gradient(135deg, #3dc96d, #30d6b2);
        transform: scale(1.05);
    }
</style>
</head>
<body>

<?php include("include/header.php"); ?>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="alert alert-success text-center mt-4">✅ Rental payment successful.</div>
<?php endif; ?>

<div class="container">
    <div class="property-box">
        <h2 class="mb-4 text-center"><?= htmlspecialchars($row['title']) ?></h2>

        <h4>🏡 Property Details</h4>
        <div class="row">
            <!-- Left Column -->
            <div class="col-md-6">
                <p><strong>📍 Location:</strong> <?= htmlspecialchars($row['street'] . ', ' . $row['location'] . ', ' . $row['state'] . ' - ' . $row['zip']) ?></p>
                <p><strong>💰 Price:</strong> $<?= number_format($row['price'], 2) ?></p>
                <p><strong>🏠 Type:</strong> <?= htmlspecialchars($row['property_type']) ?></p>
                <p><strong>📐 Size:</strong> <?= $row['size_sqft'] ?> Sqft</p>
                <p><strong>🛏️ Bedrooms:</strong> <?= $row['bedrooms'] ?></p>
                <p><strong>🚿 Bathrooms:</strong> <?= $row['bathrooms'] ?></p>
                <?php if (!empty($row['amenities'])): ?>
                    <p><strong>🛠️ Amenities:</strong> <?= htmlspecialchars($row['amenities']) ?></p>
                <?php endif; ?>
            </div>

            <!-- Right Column -->
            <div class="col-md-6">
                <?php if (!empty($row['nearest_school'])): ?>
                    <p><strong>🏫 Nearest School:</strong> <?= htmlspecialchars($row['nearest_school']) ?></p>
                <?php endif; ?>
                <p><strong>🚌 Bus Availability:</strong> <?= htmlspecialchars($row['bus_availability']) ?></p>
                <p><strong>🚋 Tram Availability:</strong> <?= htmlspecialchars($row['tram_availability']) ?></p>
                <p><strong>🏊 Pool Available:</strong> <?= htmlspecialchars($row['pool_available']) ?></p>
                <p><strong>🐕 Dog Friendly:</strong> <?= htmlspecialchars($row['is_dog_friendly']) ?></p>
                <p><strong>📌 Status:</strong> <?= htmlspecialchars($row['status']) ?></p>
                <?php if (!empty($row['created_at'])): ?>
                    <p><strong>📅 Posted On:</strong> <?= date("d M Y", strtotime($row['created_at'])) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <hr>
        <h4 class="mt-4">👤 Seller Contact Info</h4>
        <p><strong>Name:</strong> <?= htmlspecialchars($row['uname']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($row['uemail']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($row['uphone']) ?></p>

        <h4 class="mt-4">📝 Description</h4>
        <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>


        <?php
        if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['Buyer', 'Agent']))
        {
            if ($property_type === 'rental') {
                $rental_query = mysqli_query($con, "SELECT * FROM rental_interest WHERE property_id = $property_id AND buyer_id = $buyer_id");
                if (mysqli_num_rows($rental_query) === 0) {
                    echo '<h4 class="mt-4 text-secondary">Express Rental Interest</h4>
                    <form method="POST"><button type="submit" name="interested_rental" class="btn btn-gradient-secondary">I\'m Interested</button></form>';
                } else {
                    $rental_res = mysqli_query($con, "SELECT * FROM rental_interest WHERE property_id = $property_id AND buyer_id = $buyer_id");
                    $rental = mysqli_fetch_assoc($rental_res);
                    echo "<div class='alert alert-info mt-3'>
                        <strong>You expressed interest on:</strong> " . date("d M Y", strtotime($rental['interest_date'])) . "<br>
                        Status: <strong>{$rental['status']}</strong>
                    </div>";
                    if ($rental['status'] === 'Accepted' && $rental['payment_status'] !== 'Paid') {
                        echo '<h4 class="mt-4">Pay Deposit</h4>';
                        echo '<a href="create_checkout_session_rental.php?pid=' . $pid . '" class="btn btn-gradient mt-3">';
                        echo 'Pay $' . number_format($row['price'], 2) . ' via Stripe';
                        echo '</a>';

                    } elseif ($rental['payment_status'] === 'Paid') {
                        echo "<div class='alert alert-success'>✅ Payment completed. Property rented.</div>";
                    }
                }
            } else {
                $existing_offer = mysqli_query($con, "SELECT * FROM offer WHERE property_id=$property_id AND buyer_id=$buyer_id");
                if (mysqli_num_rows($existing_offer) === 0) {
                    echo '<h4 class="mt-5 text-secondary">Submit an Offer</h4>
                    <form method="POST">
                        <div class="form-group">
                            <label>Offer Price ($)</label>
                            <input type="number" name="offer_price" class="form-control" required>
                        </div>
                        <button type="submit" name="submit_offer" class="btn btn-gradient mt-3">Submit Offer</button>
                    </form>';
                } else {
                    $offer_row = mysqli_fetch_assoc($existing_offer);
                    echo "<div class='alert alert-info mt-3'>
                        <strong>Offer Submitted:</strong><br>
                        Price: <strong>{$offer_row['offer_price']}$</strong><br>
                        Date: <strong>" . date("d M Y", strtotime($offer_row['offer_date'])) . "</strong><br>
                        Status: <strong>{$offer_row['status']}</strong>
                    </div>";
                }

                $paid = mysqli_query($con, "SELECT * FROM payment WHERE offer_id IN (
                    SELECT offer_id FROM offer WHERE property_id = $property_id AND buyer_id = $buyer_id AND status = 'Accepted')");
                if (mysqli_num_rows($paid) === 0) {
                    $check_offer = mysqli_query($con, "SELECT offer_id, offer_price FROM offer WHERE property_id = $property_id AND buyer_id = $buyer_id AND status = 'Accepted'");
                    if (mysqli_num_rows($check_offer) > 0) {
                        $offer = mysqli_fetch_assoc($check_offer);
                        // echo '<h4 class="mt-5 text-secondary">Make Payment</h4>
                        echo '<h4 class="mt-5 text-secondary">Make Payment</h4>';
                        echo '<a href="create_checkout_session_sale.php?offer_id=' . $offer['offer_id'] . '" class="btn btn-gradient mt-3">';
                        echo 'Pay $' . number_format($offer['offer_price'], 2) . ' via Stripe';
                        echo '</a>';

                    }
                } else {
                    echo "<div class='alert alert-success mt-3'>✅ Payment completed for this property.</div>";
                }
            }
        }
        ?>

        <a href="javascript:history.back()" class="back-btn">⬅️ Back</a>
    </div>
</div>

<?php include("include/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php
if (isset($_POST['submit_offer']) && in_array($_SESSION['role'], ['Buyer', 'Agent'])){

    $offer_price = $_POST['offer_price'];
    $offer_date = date('Y-m-d');
    mysqli_query($con, "INSERT INTO offer (property_id, buyer_id, offer_price, offer_date, status) VALUES ($pid, $buyer_id, $offer_price, '$offer_date', 'Pending')");
    header("Location: propertydetail.php?pid=$pid");
    exit;
}

if (isset($_POST['make_payment']) && in_array($_SESSION['role'], ['Buyer', 'Agent'])){
    $offer_id = $_POST['offer_id'];
    $method = $_POST['payment_method'];
    $date = date('Y-m-d');
    $get = mysqli_query($con, "SELECT offer_price, property_id FROM offer WHERE offer_id = $offer_id");
    $off = mysqli_fetch_assoc($get);
    $amount = $off['offer_price'];
    $prop = $off['property_id'];

    mysqli_query($con, "INSERT INTO payment (offer_id, seller_id, amount_paid, payment_method, payment_date, status, payment_type)
        VALUES ($offer_id, {$row['seller_id']}, $amount, '$method', '$date', 'Completed', 'Sale')");
    mysqli_query($con, "UPDATE offer SET status = 'Sold' WHERE offer_id = $offer_id");
    mysqli_query($con, "UPDATE property_listings SET status = 'Sold' WHERE property_id = $prop");
    header("Location: propertydetail.php?pid=$pid");
    exit;
}

if (isset($_POST['interested_rental']) && in_array($_SESSION['role'], ['Buyer', 'Agent'])){
    $date = date('Y-m-d');
    mysqli_query($con, "INSERT INTO rental_interest (property_id, buyer_id, interest_date, status) VALUES ($pid, $buyer_id, '$date', 'Pending')");
    header("Location: propertydetail.php?pid=$pid");
    exit;
}

if (isset($_POST['rental_payment']) && in_array($_SESSION['role'], ['Buyer', 'Agent'])){
$method = $_POST['payment_method'];
    $date = date('Y-m-d');
    $rental_res = mysqli_query($con, "SELECT interest_id FROM rental_interest WHERE property_id = $pid AND buyer_id = $buyer_id AND status = 'Accepted'");
    $rental = mysqli_fetch_assoc($rental_res);
    $rental_id = $rental['interest_id'];

    mysqli_query($con, "INSERT INTO payment (rental_interest_id, seller_id, amount_paid, payment_method, payment_date, status, payment_type)
        VALUES ($rental_id, {$row['seller_id']}, {$row['price']}, '$method', '$date', 'Completed', 'Rental')");
    mysqli_query($con, "UPDATE rental_interest SET payment_status = 'Paid', payment_method = '$method', payment_date = '$date' WHERE interest_id = $rental_id");
    mysqli_query($con, "UPDATE property_listings SET status = 'Sold' WHERE property_id = $pid");
    $_SESSION['success_message'] = 'Rental payment successful.';
    header("Location: propertydetail.php?pid=$pid&success=1");
    exit;
}
ob_end_flush();
?>
</body>
</html>

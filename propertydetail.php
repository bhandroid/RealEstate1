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
    <title><?php echo htmlspecialchars($row['title']); ?> - Property Details</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<?php include("include/header.php"); ?>
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="alert alert-success">Rental payment successful.</div>
<?php endif; ?>

<div class="container mt-4">
    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
    <p><strong>Price:</strong> $<?php echo number_format($row['price'], 2); ?></p>
    <p><strong>Type:</strong> <?php echo htmlspecialchars($row['property_type']); ?></p>
    <p><strong>Size:</strong> <?php echo $row['size_sqft']; ?> Sqft</p>
    <p><strong>Bedrooms:</strong> <?php echo $row['bedrooms']; ?> | <strong>Bathrooms:</strong> <?php echo $row['bathrooms']; ?></p>
    <p><strong>Description:</strong><br><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>

    <h4 class="mt-4">Seller Contact Info</h4>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($row['uname']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['uemail']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['uphone']); ?></p>

<?php
if (isset($_SESSION['role']) && $_SESSION['role'] === 'Buyer') {
    if ($property_type === 'rental') {
        $rental_query = mysqli_query($con, "SELECT * FROM rental_interest WHERE property_id = $property_id AND buyer_id = $buyer_id");
        if (mysqli_num_rows($rental_query) === 0) {
            echo '<h4 class="mt-4 text-secondary">Express Rental Interest</h4>
            <form method="POST"><button type="submit" name="interested_rental" class="btn btn-info">I\'m Interested</button></form>';
        } else {
            // Refetch updated rental row after redirect
            $rental_res = mysqli_query($con, "SELECT * FROM rental_interest WHERE property_id = $property_id AND buyer_id = $buyer_id");
            $rental = mysqli_fetch_assoc($rental_res);

            echo "<div class='alert alert-info mt-3'>
                <strong>You expressed interest on:</strong> " . date("d M Y", strtotime($rental['interest_date'])) . "<br>
                Status: <strong>{$rental['status']}</strong>
            </div>";

            // ✅ Only show payment form if still Pending
            if ($rental['status'] === 'Accepted' && $rental['payment_status'] !== 'Paid') {
                echo '
                <h4 class="mt-4">Pay Deposit</h4>
                <form method="POST">
                    <input type="hidden" name="rental_id" value="' . $rental['interest_id'] . '">
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <button name="rental_payment" class="btn btn-primary">Pay $' . number_format($row['price'], 2) . '</button>
                </form>';
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
                <button type="submit" name="submit_offer" class="btn btn-success">Submit Offer</button>
            </form>';
        } else {
            $offer_row = mysqli_fetch_assoc($existing_offer);
            echo "<div class='alert alert-info mt-3'>
                <strong>Offer Submitted:</strong><br>
                Price: <strong>\${$offer_row['offer_price']}</strong><br>
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
                echo '<h4 class="mt-5 text-secondary">Make Payment</h4>
                <form method="POST">
                    <input type="hidden" name="offer_id" value="' . $offer['offer_id'] . '">
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <button type="submit" name="make_payment" class="btn btn-primary">Pay $' . number_format($offer['offer_price'], 2) . '</button>
                </form>';
            }
        } else {
            echo "<div class='alert alert-success mt-3'>Payment completed for this property.</div>";
        }
    }
}
?>

<a href="javascript:history.back()" class="btn btn-secondary mt-4">Back</a>
</div>
<?php include("include/footer.php"); ?>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<?php
// SALE LOGIC
if (isset($_POST['submit_offer']) && $_SESSION['role'] === 'Buyer') {
    $offer_price = $_POST['offer_price'];
    $offer_date = date('Y-m-d');
    mysqli_query($con, "INSERT INTO offer (property_id, buyer_id, offer_price, offer_date, status) VALUES ($pid, $buyer_id, $offer_price, '$offer_date', 'Pending')");
    header("Location: propertydetail.php?pid=$pid");
    exit;
}

if (isset($_POST['make_payment']) && $_SESSION['role'] === 'Buyer') {
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

// RENTAL LOGIC
if (isset($_POST['interested_rental']) && $_SESSION['role'] === 'Buyer') {
    $date = date('Y-m-d');
    mysqli_query($con, "INSERT INTO rental_interest (property_id, buyer_id, interest_date, status) VALUES ($pid, $buyer_id, '$date', 'Pending')");
    header("Location: propertydetail.php?pid=$pid");
    exit;
}

if (isset($_POST['rental_payment']) && $_SESSION['role'] === 'Buyer') {
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

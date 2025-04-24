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
$property_id = $pid;
$buyer_id = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($row['title']); ?> - Property Details</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include("include/header.php"); ?>
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
// 🟢 BUYER SECTION
if (isset($_SESSION['role']) && $_SESSION['role'] === 'Buyer') {
    if ($row['property_type'] === 'rental') {
        $interest_query = mysqli_query($con, "SELECT * FROM rental_interest WHERE property_id=$property_id AND buyer_id=$buyer_id");

        if (mysqli_num_rows($interest_query) === 0) {
            echo '
                <form method="POST">
                    <input type="hidden" name="express_interest" value="1">
                    <button type="submit" class="btn btn-primary mt-3">I\'m Interested</button>
                </form>';
        } else {
            $interest = mysqli_fetch_assoc($interest_query);
            echo "<div class='alert alert-info mt-3'>
                    <strong>Interest Status:</strong> {$interest['status']}
                  </div>";

            if ($interest['status'] === 'Accepted' && $interest['payment_status'] !== 'Completed') {
                echo '
                <h4 class="mt-4 mb-2">Pay Deposit</h4>
                <form method="POST">
                    <input type="hidden" name="interest_id" value="' . $interest['id'] . '">
                    <label>Payment Method</label>
                    <select name="payment_method" class="form-control mb-2" required>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="PayPal">PayPal</option>
                    </select>
                    <button type="submit" name="pay_rent_deposit" class="btn btn-success">Pay Deposit</button>
                </form>';
            }
        }

    } else {
        // For Sale Properties
        $existing_offer = mysqli_query($con, "SELECT * FROM offer WHERE property_id=$property_id AND buyer_id=$buyer_id");

        if (mysqli_num_rows($existing_offer) === 0) {
            echo '<h4 class="mt-5 mb-4 text-secondary">Submit an Offer</h4>
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

        $paid = mysqli_query($con, "SELECT * FROM payment 
            WHERE offer_id IN (
                SELECT offer_id FROM offer WHERE property_id = $property_id AND buyer_id = $buyer_id AND status = 'Accepted'
            )");

        if (mysqli_num_rows($paid) === 0) {
            $check_offer = mysqli_query($con, "
                SELECT offer_id, offer_price 
                FROM offer 
                WHERE property_id = $property_id AND buyer_id = $buyer_id AND status = 'Accepted'
            ");
            if (mysqli_num_rows($check_offer) > 0) {
                $offer = mysqli_fetch_assoc($check_offer);
                echo '<h4 class="mt-5 mb-4 text-secondary">Make Payment</h4>
                <form method="POST">
                    <input type="hidden" name="offer_id" value="' . $offer['offer_id'] . '">
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="PayPal">PayPal</option>
                        </select>
                    </div>
                    <button type="submit" name="make_payment" class="btn btn-primary">Pay $' . number_format($offer['offer_price'], 2) . '</button>
                </form>';
            }
        } else {
            echo "<div class='alert alert-success mt-3'>Payment already completed for this property.</div>";
        }
    }
}
?>

<?php
// 🔄 Interest submission for rental
if (isset($_POST['express_interest']) && $_SESSION['role'] === 'Buyer') {
    $interest_date = date('Y-m-d');
    $insert = mysqli_query($con, "
        INSERT INTO rental_interest (property_id, buyer_id, interest_date, status)
        VALUES ($property_id, $buyer_id, '$interest_date', 'Pending')
    ");
    if ($insert) {
        echo "<script>alert('Interest submitted! Seller will respond soon.'); window.location.href = window.location.href;</script>";
    } else {
        echo "<script>alert('Failed to express interest.');</script>";
    }
}

// 💳 Rental deposit payment
if (isset($_POST['pay_rent_deposit']) && $_SESSION['role'] === 'Buyer') {
    $interest_id = $_POST['interest_id'];
    $payment_method = $_POST['payment_method'];
    $payment_date = date('Y-m-d');

    $update_payment = mysqli_query($con, "
        UPDATE rental_interest 
        SET payment_status = 'Completed', payment_method = '$payment_method', payment_date = '$payment_date'
        WHERE id = $interest_id
    ");

    if ($update_payment) {
        mysqli_query($con, "UPDATE property_listings SET status = 'Sold' WHERE property_id = $property_id");
        echo "<script>alert('Deposit paid. Property marked as Sold.'); window.location.href = window.location.href;</script>";
        exit();
    } else {
        echo "<script>alert('Deposit payment failed. Try again.');</script>";
    }
}
?>
<a href="javascript:history.back()" class="btn btn-secondary mt-4">Back to Listings</a>
</div>

<?php include("include/footer.php"); ?>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>

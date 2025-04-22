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
    JOIN user ON property_listings.SELLER_ID = user.user_id
    WHERE property_listings.PROPERTY_ID = '$pid'
") or die("Query Failed: " . mysqli_error($con));

if(mysqli_num_rows($query) == 0) {
    echo "<h2 class='text-center'>Property not found</h2>";
    exit;
}
$row = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($row['TITLE']); ?> - Property Details</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include("include/header.php"); ?>
<div class="container mt-4">
    <h2><?php echo htmlspecialchars($row['TITLE']); ?></h2>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($row['LOCATION']); ?></p>
    <p><strong>Price:</strong> $<?php echo number_format($row['PRICE'], 2); ?></p>
    <p><strong>Type:</strong> <?php echo htmlspecialchars($row['PROPERTY_TYPE']); ?></p>
    <p><strong>Size:</strong> <?php echo $row['SIZE_SQFT']; ?> Sqft</p>
    <p><strong>Bedrooms:</strong> <?php echo $row['BEDROOMS']; ?> | <strong>Bathrooms:</strong> <?php echo $row['BATHROOMS']; ?></p>
    <p><strong>Description:</strong><br><?php echo nl2br(htmlspecialchars($row['DESCRIPTION'])); ?></p>

    <h4 class="mt-4">Seller Contact Info</h4>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($row['uname']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['uemail']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['uphone']); ?></p>

<?php
$property_id = $pid;
$buyer_id = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;

// 🟢 BUYER SECTION
if (isset($_SESSION['role']) && $_SESSION['role'] === 'Buyer') {
    $existing_offer = mysqli_query($con, "SELECT * FROM offer WHERE property_id=$property_id AND buyer_id=$buyer_id");

    if (mysqli_num_rows($existing_offer) === 0) {
?>
        <h4 class="mt-5 mb-4 text-secondary">Submit an Offer</h4>
        <form method="POST">
            <div class="form-group">
                <label>Offer Price ($)</label>
                <input type="number" name="offer_price" class="form-control" required>
            </div>
            <button type="submit" name="submit_offer" class="btn btn-success">Submit Offer</button>
        </form>
<?php
    } else {
        $offer_row = mysqli_fetch_assoc($existing_offer);
        echo "
        <div class='alert alert-info mt-3'>
            <strong>Offer Submitted:</strong><br>
            Price: <strong>\${$offer_row['offer_price']}</strong><br>
            Date: <strong>" . date("d M Y", strtotime($offer_row['offer_date'])) . "</strong><br>
            Status: <strong>{$offer_row['status']}</strong>
        </div>";
    }

    // Check if already paid
    $paid = mysqli_query($con, "
        SELECT * FROM payment 
        WHERE offer_id IN (
            SELECT offer_id FROM offer WHERE property_id = $property_id AND buyer_id = $buyer_id AND status = 'Accepted'
        )
    ");
    
    if (mysqli_num_rows($paid) === 0) {
        // Show payment form if accepted and not paid
        $check_offer = mysqli_query($con, "
            SELECT offer_id, offer_price 
            FROM offer 
            WHERE property_id = $property_id AND buyer_id = $buyer_id AND status = 'Accepted'
        ");
        if (mysqli_num_rows($check_offer) > 0) {
            $offer = mysqli_fetch_assoc($check_offer);
?>
            <h4 class="mt-5 mb-4 text-secondary">Make Payment</h4>
            <form method="POST">
                <input type="hidden" name="offer_id" value="<?php echo $offer['offer_id']; ?>">
                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="PayPal">PayPal</option>
                    </select>
                </div>
                <button type="submit" name="make_payment" class="btn btn-primary">Pay $<?php echo number_format($offer['offer_price'], 2); ?></button>
            </form>
<?php
        }
    } else {
        echo "<div class='alert alert-success mt-3'>Payment already completed for this property.</div>";
    }
}

// 🟠 SELLER VIEWING OFFERS
if (
    isset($_SESSION['user_id']) &&
    $_SESSION['role'] === 'Seller' &&
    $_SESSION['user_id'] == $row['SELLER_ID']
) {
    $offers = mysqli_query($con, "
        SELECT offer.*, user.name AS buyer_name, user.email AS buyer_email
        FROM offer
        JOIN user ON offer.buyer_id = user.user_id
        WHERE offer.property_id = $pid
    ");
    echo "<h4 class='mt-5'>Offers for this Property</h4>";

    if (mysqli_num_rows($offers) > 0) {
        echo "<table class='table table-bordered mt-3'>
            <thead><tr>
                <th>Buyer</th><th>Email</th><th>Price</th><th>Date</th><th>Status</th><th>Action</th>
            </tr></thead><tbody>";
        while ($offer = mysqli_fetch_assoc($offers)) {
            echo "<tr>
                <td>{$offer['buyer_name']}</td>
                <td>{$offer['buyer_email']}</td>
                <td>\${$offer['offer_price']}</td>
                <td>{$offer['offer_date']}</td>
                <td><strong>{$offer['status']}</strong></td>
                <td>";
            if ($offer['status'] === 'Pending') {
                echo "
                <form method='POST' style='display:inline'>
                    <input type='hidden' name='offer_id' value='{$offer['offer_id']}'>
                    <button name='accept_offer' class='btn btn-success btn-sm'>Accept</button>
                    <button name='reject_offer' class='btn btn-danger btn-sm'>Reject</button>
                </form>";
            } else {
                echo "<span class='badge bg-secondary'>Final</span>";
            }
            echo "</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No offers yet.</p>";
    }
}
?>

<a href="javascript:history.back()" class="btn btn-secondary mt-3">Back to Listings</a>
</div>
<?php include("include/footer.php"); ?>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<?php
// ✅ Buyer submits offer
if (isset($_POST['submit_offer']) && $_SESSION['role'] === 'Buyer') {
    $offer_price = $_POST['offer_price'];
    $buyer_id = $_SESSION['user_id'];
    $property_id = $_GET['pid'];
    $offer_date = date('Y-m-d');

    $insert_query = "
        INSERT INTO offer (property_id, buyer_id, offer_price, offer_date, status)
        VALUES ($property_id, $buyer_id, $offer_price, '$offer_date', 'Pending')
    ";
    if (mysqli_query($con, $insert_query)) {
        header("Location: propertydetail.php?pid=$property_id");
        exit();
    } else {
        echo "<script>alert('Error submitting offer');</script>";
    }
}

// ✅ Buyer makes payment
if (isset($_POST['make_payment']) && $_SESSION['role'] === 'Buyer') {
    $offer_id = $_POST['offer_id'];
    $payment_method = $_POST['payment_method'];
    $payment_date = date('Y-m-d');

    $get_property = mysqli_query($con, "
        SELECT seller_id FROM property_listings 
        JOIN offer ON property_listings.property_id = offer.property_id 
        WHERE offer.offer_id = $offer_id
    ");
    $seller = mysqli_fetch_assoc($get_property);
    $seller_id = $seller['seller_id'];

    $get_amount = mysqli_query($con, "SELECT offer_price, property_id FROM offer WHERE offer_id = $offer_id");
    $row = mysqli_fetch_assoc($get_amount);
    $amount = $row['offer_price'];
    $property_id = $row['property_id'];

    $pay = mysqli_query($con, "
        INSERT INTO payment (offer_id, seller_id, amount_paid, payment_method, payment_date, status)
        VALUES ($offer_id, $seller_id, $amount, '$payment_method', '$payment_date', 'Completed')
    ");

    if ($pay) {
        mysqli_query($con, "UPDATE property_listings SET status = 'Sold' WHERE property_id = $property_id");
        mysqli_query($con, "UPDATE offer SET status = 'Sold' WHERE offer_id = $offer_id");
    
        echo "<script>alert('Payment successful. Property marked as sold.'); window.location.href = window.location.href;</script>";
        exit();
    } else {
        echo "<script>alert('Payment failed. Try again.');</script>";
    }
}

// ✅ Seller accepts or rejects offer
if (isset($_POST['accept_offer']) && $_SESSION['role'] === 'Seller') {
    $offer_id = $_POST['offer_id'];
    mysqli_query($con, "UPDATE offer SET status = 'Accepted' WHERE offer_id = $offer_id");
    mysqli_query($con, "UPDATE offer SET status = 'Rejected' WHERE property_id = $pid AND offer_id != $offer_id");
    mysqli_query($con, "UPDATE property_listings SET status = 'Hold' WHERE property_id = $pid");
    header("Location: propertydetail.php?pid=$pid");
    exit();
}

if (isset($_POST['reject_offer']) && $_SESSION['role'] === 'Seller') {
    $offer_id = $_POST['offer_id'];
    mysqli_query($con, "UPDATE offer SET status = 'Rejected' WHERE offer_id = $offer_id");
    header("Location: propertydetail.php?pid=$pid");
    exit();
}
ob_end_flush();
?>
</body>
</html>

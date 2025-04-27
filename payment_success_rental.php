<?php
session_start();
include("config.php");

$pid = (int)$_GET['pid'];
$buyer_id = (int)$_GET['buyer_id'];

// Fetch the correct rental_interest ID
$rental_interest_query = mysqli_query($con, "SELECT interest_id FROM rental_interest WHERE property_id = $pid AND buyer_id = $buyer_id");
$rental_interest = mysqli_fetch_assoc($rental_interest_query);
$rental_interest_id = $rental_interest['interest_id'] ?? null;

if (!$rental_interest_id) {
    die("❌ Rental interest not found for this property and buyer.");
}

// Update rental_interest payment_status to 'Paid'
mysqli_query($con, "UPDATE rental_interest SET payment_status = 'Paid', payment_method = 'Stripe',payment_date = CURDATE() WHERE interest_id = $rental_interest_id");

// Fetch seller_id and price from property_listings
$row = mysqli_fetch_assoc(mysqli_query($con, "SELECT seller_id, price FROM property_listings WHERE property_id = $pid"));
$seller_id = $row['seller_id'];
$amount = $row['price'];

// Insert payment with correct rental_interest_id
mysqli_query($con, "INSERT INTO payment (rental_interest_id, seller_id, amount_paid, payment_method, payment_date, status, payment_type)
    VALUES ($rental_interest_id, $seller_id, $amount, 'Stripe', CURDATE(), 'Completed', 'Rental')");

// Update property status if required (optional for rentals, remove this if not needed)
mysqli_query($con, "UPDATE property_listings SET status = 'sold' WHERE property_id = $pid");

echo "<h2>✅ Rental Payment Successful!</h2><a href='Bought_Propertyes.php'>Go to Dashboard</a>";
?>

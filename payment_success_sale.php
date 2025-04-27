<?php
session_start();
include("config.php");

$offer_id = (int)$_GET['offer_id'];

$get = mysqli_query($con, "SELECT offer_price, property_id FROM offer WHERE offer_id = $offer_id");
$off = mysqli_fetch_assoc($get);
$amount = $off['offer_price'];
$prop_id = $off['property_id'];

// Update Offer
mysqli_query($con, "UPDATE offer SET status = 'Sold' WHERE offer_id = $offer_id");

// Update Property
mysqli_query($con, "UPDATE property_listings SET status = 'Sold' WHERE property_id = $prop_id");

// Insert Payment
$row = mysqli_fetch_assoc(mysqli_query($con, "SELECT seller_id FROM property_listings WHERE property_id = $prop_id"));
$seller_id = $row['seller_id'];

mysqli_query($con, "INSERT INTO payment (offer_id, seller_id, amount_paid, payment_method, payment_date, status, payment_type)
    VALUES ($offer_id, $seller_id, $amount, 'Stripe', CURDATE(), 'Completed', 'Sale')");

echo "<h2>✅ Sale Payment Successful!</h2><a href='Bought_Propertyes.php'>Go to my Properties</a>";
?>

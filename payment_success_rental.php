<?php
session_start();
include("config.php");

$pid = (int)$_GET['pid'];
$buyer_id = (int)$_GET['buyer_id'];

// Update rental_interest to Paid
mysqli_query($con, "UPDATE rental_interest SET payment_status = 'Paid' WHERE property_id = $pid AND buyer_id = $buyer_id");

// Insert Payment Record
$row = mysqli_fetch_assoc(mysqli_query($con, "SELECT seller_id, price FROM property_listings WHERE property_id = $pid"));
$seller_id = $row['seller_id'];
$amount = $row['price'];

mysqli_query($con, "INSERT INTO payment (rental_interest_id, seller_id, amount_paid, payment_method, payment_date, status, payment_type)
    VALUES (NULL, $seller_id, $amount, 'Stripe', CURDATE(), 'Completed', 'Rental')");

// Update property status
mysqli_query($con, "UPDATE property_listings SET status = 'Sold' WHERE property_id = $pid");

echo "<h2>✅ Rental Payment Successful!</h2><a href='dashboard.php'>Go to Dashboard</a>";
?>

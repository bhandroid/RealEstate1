<?php
session_start();
require_once('vendor/autoload.php'); // or stripe-php/init.php manually

include("config.php");

\Stripe\Stripe::setApiKey('sk_test_51RIUdUCvJxhejvyKqPFtEJlPn1a8KbRluAir0RBb56xPhn64OtkcUUiJ2eauVBHhwSpMWX5GkgmjCHzFtz8jZChp00WCHzX0o7'); // put your Secret Key here

$pid = (int)$_GET['pid'];
$buyer_id = $_SESSION['user_id'];

$query = mysqli_query($con, "SELECT * FROM property_listings WHERE property_id = $pid");
$row = mysqli_fetch_assoc($query);

$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency' => 'usd',
            'product_data' => [
                'name' => 'Rental Payment for Property ID ' . $pid,
            ],
            'unit_amount' => intval($row['price'] * 100), // price in cents
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => 'http://localhost/RealEstate1/payment_success_rental.php?pid='.$pid.'&buyer_id='.$buyer_id,
    'cancel_url' => 'http://localhost/RealEstate1/propertydetail.php?pid='.$pid,
]);

header("Location: " . $session->url);
exit;
?>

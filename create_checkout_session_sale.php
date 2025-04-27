<?php
session_start();
require_once('vendor/autoload.php');

include("config.php");

\Stripe\Stripe::setApiKey('sk_test_51RIUdUCvJxhejvyKqPFtEJlPn1a8KbRluAir0RBb56xPhn64OtkcUUiJ2eauVBHhwSpMWX5GkgmjCHzFtz8jZChp00WCHzX0o7');

$offer_id = (int)$_GET['offer_id'];

$get = mysqli_query($con, "SELECT o.offer_price, o.property_id, p.seller_id FROM offer o JOIN property_listings p ON o.property_id = p.property_id WHERE offer_id = $offer_id");
$off = mysqli_fetch_assoc($get);

$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency' => 'usd',
            'product_data' => [
                'name' => 'Sale Payment for Property ID ' . $off['property_id'],
            ],
            'unit_amount' => intval($off['offer_price'] * 100),
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => 'http://localhost/RealEstate1/payment_success_sale.php?offer_id='.$offer_id,
    'cancel_url' => 'http://localhost/RealEstate1/propertydetail.php?pid='.$off['property_id'],
]);

header("Location: " . $session->url);
exit;
?>

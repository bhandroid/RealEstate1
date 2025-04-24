<?php
session_start();
require("config.php");
include("functions.php");  // Include for audit log

//  Check login and role
$user_id = $_SESSION['uid'] ?? null;
$role = strtolower($_SESSION['role'] ?? '');

if (!$user_id || !in_array($role, ['seller', 'agent'])) {
    die(" Access denied. Only sellers or agents can delete properties.");
}

// Check if property ID is provided
if (isset($_GET['id'])) {
    $property_id = intval($_GET['id']);  // Secure casting to integer

    //  Check ownership before delete
    $check_ownership = mysqli_query($con, "SELECT * FROM property_listings WHERE property_id = $property_id AND seller_id = $user_id");
    if (mysqli_num_rows($check_ownership) === 0) {
        die("❌ Property not found or not owned by you.");
    }

    //  Step 1: Delete payments related to offers for this property
    mysqli_query($con, "
        DELETE FROM payment 
        WHERE offer_id IN (SELECT offer_id FROM offer WHERE property_id = $property_id)
    ");

    //  Step 2: Delete related offers
    mysqli_query($con, "DELETE FROM offer WHERE property_id = $property_id");

    //  Step 3: Delete related appointments
    mysqli_query($con, "DELETE FROM appointment WHERE property_id = $property_id");

    //  Step 4: Delete related rental contracts (if applicable)
    mysqli_query($con, "DELETE FROM rental_contracts WHERE property_id = $property_id");

    //  Step 5: Delete related images
    mysqli_query($con, "DELETE FROM property_image WHERE property_id = $property_id");

    //  Step 6: Finally, delete the property itself
    $delete_property = "DELETE FROM property_listings WHERE property_id = $property_id";
    $result = mysqli_query($con, $delete_property);

    if ($result) {
        addAuditLog($user_id, 'DELETE_PROPERTY', 'Seller/Agent deleted property with ID: ' . $property_id);
        $msg = "Property deleted successfully.";
    } else {
        $msg = "Failed to delete property.";
    }

    header("Location: my_properties.php?msg=" . urlencode($msg));
    exit();
} else {
    header("Location: my_properties.php?msg=" . urlencode("No Property ID Provided"));
    exit();
}

mysqli_close($con);
?>

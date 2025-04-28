<?php
include("config.php");

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id > 0) {
    // ✅ Step 1: Delete PAYMENT records (Offer-related payments)
    $offer_result = mysqli_query($con, "SELECT offer_id FROM offer WHERE buyer_id = $user_id");
    while ($offer = mysqli_fetch_assoc($offer_result)) {
        $offer_id = $offer['offer_id'];
        mysqli_query($con, "DELETE FROM payment WHERE offer_id = $offer_id");
    }

    // ✅ Step 2: Delete PAYMENTS where user is SELLER
    mysqli_query($con, "DELETE FROM payment WHERE seller_id = $user_id");

    // ✅ Step 3: Delete OFFERS where buyer_id = user_id
    mysqli_query($con, "DELETE FROM offer WHERE buyer_id = $user_id");

    // ✅ Step 4: Delete RENTAL_INTEREST where buyer_id = user_id
    mysqli_query($con, "DELETE FROM rental_interest WHERE buyer_id = $user_id");

    // ✅ Step 5: Delete related PROPERTY data if the user is a seller
    $property_result = mysqli_query($con, "SELECT property_id FROM property_listings WHERE seller_id = $user_id");
    while ($property = mysqli_fetch_assoc($property_result)) {
        $property_id = $property['property_id'];
        mysqli_query($con, "DELETE FROM payment WHERE property_id = $property_id");               // Payment related to property
        mysqli_query($con, "DELETE FROM offer WHERE property_id = $property_id");                // Offers on the property
        mysqli_query($con, "DELETE FROM property_image WHERE property_id = $property_id");       // Property images
        mysqli_query($con, "DELETE FROM rental_contracts WHERE property_id = $property_id");     // Rental contracts
        mysqli_query($con, "DELETE FROM appointment WHERE property_id = $property_id");          // Appointments on property
        mysqli_query($con, "DELETE FROM favorite WHERE property_id = $property_id");             // Favorites
        mysqli_query($con, "DELETE FROM rental_interest WHERE property_id = $property_id");      // Rental interests
        mysqli_query($con, "DELETE FROM property_listings WHERE property_id = $property_id");    // Finally delete the property itself
    }

    // ✅ Step 6: Delete APPOINTMENT where user is directly involved
    mysqli_query($con, "DELETE FROM appointment WHERE user_id = $user_id");

    // ✅ Step 7: Delete FAVORITES
    mysqli_query($con, "DELETE FROM favorite WHERE user_id = $user_id");

    // ✅ Step 8: Delete TICKETS
    mysqli_query($con, "DELETE FROM tickets WHERE user_id = $user_id");

    // ✅ Step 9: Delete AUDIT LOG
    mysqli_query($con, "DELETE FROM audit_log WHERE user_id = $user_id");

    // ✅ Step 10: Delete NOTIFICATION (if exists)

    // ✅ Step 11: Delete CHAT (if exists)

    // ✅ Step 12: Finally delete the USER
    $sql = "DELETE FROM user WHERE user_id = $user_id";
    $result = mysqli_query($con, $sql);

    if ($result === true) {
        $msg = "<p class='alert alert-success'>User Deleted Successfully</p>";
    } else {
        $msg = "<p class='alert alert-warning'>User Not Deleted</p>";
    }
} else {
    $msg = "<p class='alert alert-danger'>Invalid User ID</p>";
}

header("Location: userlist.php?msg=" . urlencode($msg));
mysqli_close($con);
exit();
?>

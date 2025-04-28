<?php
include("config.php");

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$msg = "";

if ($user_id > 0) {
    // 1. Delete payments where this user is the seller
    mysqli_query($con, "DELETE FROM payment WHERE seller_id = $user_id");

    // 2. Delete payments related to offers placed by this user (buyer)
    mysqli_query($con, "
        DELETE FROM payment 
        WHERE offer_id IN (
            SELECT offer_id FROM offer WHERE buyer_id = $user_id
        )
    ");

    // 3. Delete offers placed by this user (buyer)
    mysqli_query($con, "DELETE FROM offer WHERE buyer_id = $user_id");

    // 4. Delete appointments where this user is directly involved
    mysqli_query($con, "DELETE FROM appointment WHERE user_id = $user_id");

    // 5. Delete appointments related to properties owned by this seller
    mysqli_query($con, "
        DELETE FROM appointment 
        WHERE property_id IN (
            SELECT property_id FROM property_listings WHERE seller_id = $user_id
        )
    ");

    // 6. Delete rental contracts related to properties owned by this seller
    mysqli_query($con, "
        DELETE FROM rental_contracts 
        WHERE property_id IN (
            SELECT property_id FROM property_listings WHERE seller_id = $user_id
        )
    ");

    // 7. Delete rental interest related to properties owned by this seller
    mysqli_query($con, "
        DELETE FROM rental_interest 
        WHERE property_id IN (
            SELECT property_id FROM property_listings WHERE seller_id = $user_id
        )
    ");

    // 8. Delete rental interest where this user is buyer
    mysqli_query($con, "DELETE FROM rental_interest WHERE buyer_id = $user_id");

    // 9. Delete offers related to the seller's properties
    mysqli_query($con, "
        DELETE FROM offer 
        WHERE property_id IN (
            SELECT property_id FROM property_listings WHERE seller_id = $user_id
        )
    ");

    // 10. Delete property images related to the seller's properties
    mysqli_query($con, "
        DELETE FROM property_image 
        WHERE property_id IN (
            SELECT property_id FROM property_listings WHERE seller_id = $user_id
        )
    ");

    // 11. Delete property listings where this user is the seller
    mysqli_query($con, "DELETE FROM property_listings WHERE seller_id = $user_id");

    // 12. Delete tickets raised by this user
    mysqli_query($con, "DELETE FROM tickets WHERE user_id = $user_id");

    // 13. ✅ Finally, delete the user (seller)
    $sql = "DELETE FROM user WHERE user_id = $user_id AND role = 'seller'";
    $result = mysqli_query($con, $sql);

    if ($result === true) {
        $msg = "<p class='alert alert-success'>Seller Deleted Successfully</p>";
    } else {
        $msg = "<p class='alert alert-warning'>Seller Not Deleted</p>";
    }
} else {
    $msg = "<p class='alert alert-danger'>Invalid User ID</p>";
}

header("Location: userseller.php?msg=" . urlencode($msg));
mysqli_close($con);
exit();
?>

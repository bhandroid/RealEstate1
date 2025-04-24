<?php
session_start();
require("config.php");
include("../functions.php");  // ✅ If you want audit logging (optional)

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $property_id = intval($_GET['id']);  // Safe integer casting

    // ✅ Delete payments first (because payments reference offers)
    mysqli_query($con, "
        DELETE FROM payment 
        WHERE offer_id IN (SELECT offer_id FROM offer WHERE property_id = $property_id)
    ");

    // ✅ Delete offers related to the property
    mysqli_query($con, "DELETE FROM offer WHERE property_id = $property_id");

    // ✅ Delete related appointments
    mysqli_query($con, "DELETE FROM appointment WHERE property_id = $property_id");

    // ✅ Delete rental contracts (if any)
    mysqli_query($con, "DELETE FROM rental_contracts WHERE property_id = $property_id");

    // ✅ Delete property images
    mysqli_query($con, "DELETE FROM property_image WHERE property_id = $property_id");

    // ✅ Finally, delete the property itself
    $delete_property = "DELETE FROM property_listings WHERE property_id = $property_id";
    $result = mysqli_query($con, $delete_property);

    if ($result) {
        // ✅ (Optional) Audit log for admin property deletion
        addAuditLog($_SESSION['uid'], 'ADMIN_DELETE_PROPERTY', 'Admin deleted property with ID: ' . $property_id);

        $msg = "✅ Property deleted successfully.";
    } else {
        $msg = "❌ Failed to delete property.";
    }

    header("Location: propertyview.php?msg=" . urlencode($msg));
    exit();
} else {
    header("Location: propertyview.php?msg=" . urlencode("No Property ID Provided"));
    exit();
}

mysqli_close($con);
?>

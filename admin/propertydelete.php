<?php
session_start();
require("config.php");

// Check admin login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $property_id = $_GET['id'];

    // First delete from child table: property_image
    $delete_images = "DELETE FROM property_image WHERE property_id = {$property_id}";
    mysqli_query($con, $delete_images);

    // Then delete from parent table: property_listings
    $delete_property = "DELETE FROM property_listings WHERE property_id = {$property_id}";
    $result = mysqli_query($con, $delete_property);

    if ($result) {
        $msg = "Property Deleted Successfully";
    } else {
        $msg = "Failed to Delete Property";
    }

    header("Location: propertyview.php?msg=" . urlencode($msg));
    exit();
} else {
    header("Location: propertyview.php?msg=" . urlencode("No Property ID Provided"));
    exit();
}

mysqli_close($con);
?>

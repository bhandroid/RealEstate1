<?php
session_start();
include("config.php");

// Ensure user is logged in
if (!isset($_SESSION['uid'])) {
    die("❌ You must be logged in to add favorites.");
}

$user_id = $_SESSION['uid'];
$property_id = $_POST['property_id'] ?? null;

if ($property_id) {
    // Use prepared statement to avoid duplicates and SQL injection
    $stmt = $con->prepare("INSERT IGNORE INTO favorite (user_id, property_id, date) VALUES (?, ?, CURDATE())");
    $stmt->bind_param("ii", $user_id, $property_id);
    if ($stmt->execute()) {
        // ✅ Successfully added or already exists
        header("Location: favorites.php");
        exit;
    } else {
        echo "❌ Failed to add to favorites.";
    }
} else {
    echo "❌ Property ID missing.";
}
?>

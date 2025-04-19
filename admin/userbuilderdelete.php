<?php
include("config.php");

// Sanitize input
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// OPTIONAL: If you have an 'image' column, uncomment this:
/*
$sql = "SELECT image FROM user WHERE user_id = $user_id";
$result = mysqli_query($con, $sql);
if ($row = mysqli_fetch_assoc($result)) {
    $img = $row['image'];
    if (!empty($img)) {
        @unlink("user/" . $img);
    }
}
*/

$msg = "";

// Ensure only builder (seller) gets deleted
$sql = "DELETE FROM user WHERE user_id = $user_id AND role = 'Builder'";
$result = mysqli_query($con, $sql);

if ($result === true) {
    $msg = "<p class='alert alert-success'>Builder Deleted</p>";
} else {
    $msg = "<p class='alert alert-warning'>Builder Not Deleted</p>";
}

header("Location: userbuilder.php?msg=" . urlencode($msg));
mysqli_close($con);
exit();
?>

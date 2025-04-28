<?php
include("config.php");

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id > 0) {
    // ✅ First delete related appointments for this user (avoid foreign key error)
    mysqli_query($con, "DELETE FROM appointment WHERE user_id = $user_id");

    // ✅ Now safely delete the user
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

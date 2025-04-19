<?php
include("config.php");

// ✅ Get user_id from URL and sanitize
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ✅ Optional: handle image deletion if column exists
// $sql = "SELECT image FROM user WHERE user_id = $user_id";
// $result = mysqli_query($con, $sql);
// if ($row = mysqli_fetch_assoc($result)) {
//     $img = $row['image'];
//     if (!empty($img)) {
//         @unlink("user/" . $img);
//     }
// }

$msg = "";

// ✅ Delete user by user_id
$sql = "DELETE FROM user WHERE user_id = $user_id";
$result = mysqli_query($con, $sql);

if ($result === true) {
    $msg = "<p class='alert alert-success'>User Deleted</p>";
} else {
    $msg = "<p class='alert alert-warning'>User Not Deleted</p>";
}

// ✅ Redirect with message
header("Location: userlist.php?msg=" . urlencode($msg));
mysqli_close($con);
exit();
?>

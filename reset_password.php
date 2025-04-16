<?php
include("config.php");
$msg = "";
$error = "";

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $res = mysqli_query($con, "SELECT * FROM user WHERE reset_token='$token'");
    $user = mysqli_fetch_assoc($res);

    if (!$user) {
        die("Invalid or expired token");
    }
} else {
    die("No token provided");
}

if (isset($_POST['reset'])) {
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];

    if ($pass !== $cpass) {
        $error = "<p class='alert alert-danger'>Passwords do not match.</p>";
    } else {
        $hashed = password_hash($pass, PASSWORD_DEFAULT);
        mysqli_query($con, "UPDATE user SET password='$hashed', reset_token=NULL WHERE reset_token='$token'");
        $msg = "<p class='alert alert-success'>Password has been reset. <a href='login.php'>Login now</a>.</p>";
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html>
<head><title>Reset Password</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 bg-white shadow p-4">
            <h3 class="text-center">Reset Password</h3>
            <?php echo $error . $msg; ?>
            <form method="post">
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="pass" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="cpass" class="form-control" required>
                </div>
                <button class="btn btn-success" name="reset" type="submit">Reset Password</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>

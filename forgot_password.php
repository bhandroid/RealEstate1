<?php
include("config.php");
include("include/send_otp_mail.php");

$msg = "";
$error = "";

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if user exists
    $res = mysqli_query($con, "SELECT * FROM user WHERE email='$email'");
    $row = mysqli_fetch_assoc($res);

    if ($row) {
        $token = bin2hex(random_bytes(32)); // secure token
        mysqli_query($con, "UPDATE user SET reset_token='$token' WHERE email='$email'");

        $reset_link = "http://localhost/your_project_path/reset_password.php?token=$token";

        // Send Email
        $body = "Hello, click the link below to reset your password:<br><a href='$reset_link'>Reset Password</a>";
        if (sendOtpMail($email, $row['name'], $body)) {
            $msg = "<p class='alert alert-success'>Reset link sent to your email.</p>";
        } else {
            $error = "<p class='alert alert-danger'>Failed to send email.</p>";
        }
    } else {
        $error = "<p class='alert alert-warning'>Email not found!</p>";
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html>
<head><title>Forgot Password</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 bg-white shadow p-4">
            <h3 class="text-center">Forgot Password</h3>
            <?php echo $error . $msg; ?>
            <form method="post">
                <div class="form-group">
                    <label>Enter your email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <button class="btn btn-primary" name="submit" type="submit">Send Reset Link</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>

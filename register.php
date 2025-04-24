<?php 
include("config.php");
include("functions.php");

include("include/send_otp_mail.php");
session_start();

$error = "";
$msg = "";

if (isset($_POST['reg'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $pass = $_POST['pass'];
    $role = $_POST['role'];


    // Hash the password using password_hash (more secure than sha1)
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    // Check if email already exists
    $query = "SELECT * FROM user WHERE email='$email'";
    $res = mysqli_query($con, $query);
    $num = mysqli_num_rows($res);

    if ($num == 1) {
        $error = "<p class='alert alert-warning'>Email ID already exists</p>";
    } else {
        if (!empty($name) && !empty($email) && !empty($phone) && !empty($pass)) {
            $otp = rand(100000, 999999);

            // Store everything in session temporarily until OTP is verified
            $_SESSION['otp'] = $otp;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['phone'] = $phone;
            $_SESSION['pass'] = $hashed_pass;
            $_SESSION['role'] = $role;


            // Send OTP
            if (sendOtpMail($email, $name, $otp)) {
                header("Location: verify_otp.php");


                exit();
            } else {
                $error = "<p class='alert alert-warning'>Failed to send OTP via email. Please try again.</p>";
            }
        } else {
            $error = "<p class='alert alert-warning'>Please fill all the required fields.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Register | Real Estate PHP</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 shadow p-4 bg-white">
            <h3 class="text-center mb-4">Register</h3>
            <?php echo $error; ?><?php echo $msg; ?>
            <form method="post">
                <div class="form-group">
                    <input type="text" name="name" class="form-control" placeholder="Your Name*" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Your Email*" required>
                </div>
                <div class="form-group">
                    <input type="text" name="phone" class="form-control" placeholder="Your Phone*" maxlength="15" required>
                </div>
                <div class="form-group">
                    <input type="password" name="pass" class="form-control" placeholder="Your Password*" required>
                </div>

                <div class="form-group">
                    <label><b>Select Role*</b></label>
                    <select name="role" class="form-control" required>
                        <option value="">-- Select Role --</option>
                        <option value="Buyer">Buyer</option>
                        <option value="Seller">Seller</option>
                        <option value="Agent">Agent</option>
                    </select>
                </div>


                <button class="btn btn-success btn-block" name="reg" type="submit">Register</button>
            </form>
            <div class="text-center mt-3">
                Already have an account? <a href="login.php">Login</a>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery.min.js"></script> 
<script src="js/bootstrap.min.js"></script>
</body>
</html>

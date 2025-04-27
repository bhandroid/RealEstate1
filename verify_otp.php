<?php
session_start();
include("config.php");
include("functions.php"); // ✅ Include the Audit Log functions
include("include/send_otp_mail.php");

$error = "";
$msg = "";

// ✅ Handle OTP Verification
if (isset($_POST['verify'])) {
    $user_otp = $_POST['otp'];

    if ($user_otp == $_SESSION['otp']) {
        // Retrieve data from session
        $name = $_SESSION['name'];
        $email = $_SESSION['email'];
        $phone = $_SESSION['phone'];
        $pass = $_SESSION['pass'];  // already hashed using password_hash()
        $role = $_SESSION['role'];
        
        
        // ✅ Insert into user table
        $stmt = $con->prepare("INSERT INTO `user` (name, email, phone_num, password, role, date_of_creation) VALUES (?, ?, ?, ?, ?, NOW())");
        if (!$stmt) {
            die("Prepare failed: " . $con->error);
        }
        $stmt->bind_param("sssss", $name, $email, $phone, $pass, $role);
        $result = $stmt->execute();
        if ($result) {
            $userId = $stmt->insert_id;  // ✅ Get the inserted user ID

            // ✅ Add Audit Log after successful registration
            addAuditLog($userId, 'REGISTRATION', 'User registered with name: ' . $name);

            // ✅ Clear session after successful registration
            session_unset();
            session_destroy();

            $msg = "<p class='alert alert-success'>Registration successful! <a href='login.php'>Click here to login</a>.</p>";
        } else {
            $error = "<p class='alert alert-danger'>Something went wrong. Please try again.</p>";
        }
    } else {
        $error = "<p class='alert alert-danger'>Invalid OTP. Please try again.</p>";
    }
}

// ✅ Handle OTP Resend
if (isset($_POST['resend'])) {
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;

    $name = $_SESSION['name'];
    $email = $_SESSION['email'];

    if (sendOtpMail($email, $name, $otp)) {
        $msg = "<p class='alert alert-success'>A new OTP has been sent to your email.</p>";
    } else {
        $error = "<p class='alert alert-danger'>Failed to resend OTP. Please try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Verify OTP - Real Estate</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 bg-white shadow p-4">
            <h3 class="text-center mb-4">Email Verification</h3>
            <?php echo $error; ?><?php echo $msg; ?>

            <!-- ✅ OTP Verification Form -->
            <form method="POST">
                <div class="form-group">
                    <label for="otp">Enter the OTP sent to your email</label>
                    <input type="text" class="form-control" name="otp" required maxlength="6" pattern="\d{6}">
                </div>
                <button type="submit" name="verify" class="btn btn-success btn-block">Verify & Register</button>
            </form>

            <!-- ✅ Resend OTP Form -->
            <form method="POST" class="mt-2">
                <button type="submit" name="resend" class="btn btn-link">Resend OTP</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>

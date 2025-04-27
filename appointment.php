<?php
session_start();
include("config.php");
include("include/header.php"); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'include/mailer/PHPMailer.php';
require 'include/mailer/SMTP.php';
require 'include/mailer/Exception.php';

if (!isset($_SESSION['uid']) || !in_array($_SESSION['role'], ['Buyer', 'Agent'])) {
    die("Access denied.");
}

$user_id = $_SESSION['uid'];
$property_id = $_GET['property_id'] ?? null;
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $time = $_POST['time'];
    $stmt = $con->prepare("INSERT INTO appointment (user_id, property_id, time, status) VALUES (?, ?, ?, 'Pending')");
    
    if ($stmt === false) {
        die("❌ Prepare failed: " . $con->error);
    }

    $stmt->bind_param("iis", $user_id, $property_id, $time);

    if ($stmt->execute()) {
        // ✅ Send confirmation email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'bhanuprakashofcl@gmail.com';
            $mail->Password = 'upqd ysyn qesn hhpe';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('bhanuprakashofcl@gmail.com', 'Real Estate Booking');
            $mail->addAddress($_SESSION['email'], $_SESSION['name']);

            $mail->isHTML(true);
            $mail->Subject = "Appointment Booked Successfully!";
            $mail->Body = "
                <h3>Hi {$_SESSION['name']},</h3>
                <p>Your appointment for Property ID <strong>$property_id</strong> has been successfully booked.</p>
                <p><strong>Date & Time:</strong> $time</p>
                <p>We'll notify you once the seller confirms it.</p>
                <br><p>Regards,<br><b>Real Estate Team</b></p>";

            $mail->send();
            $msg = "<div class='alert alert-success'>✅ Appointment requested and email sent successfully!</div>";
        } catch (Exception $e) {
            $msg = "<div class='alert alert-warning'>✅ Appointment booked, but email failed: {$mail->ErrorInfo}</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>❌ Error booking appointment.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">

<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<head><title>Book Appointment</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/layerslider.css">
<link rel="stylesheet" type="text/css" href="css/color.css" id="color-change">
<link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<h3>📅 Book Appointment</h3>
<?= $msg ?>
<form method="POST">
    <div class="form-group">
        <label>Select Date & Time</label>
        <input type="datetime-local" name="time" class="form-control" required>
    </div>
    <button class="btn btn-success">Confirm Appointment</button>
</form>
</body>
</html>
<?php include("include/footer.php"); ?>

<?php
session_start();
include("config.php");

if (!isset($_SESSION['uid']) || !in_array($_SESSION['role'], ['Buyer', 'Agent'])) {
    die("Access denied.");
}

$user_id = $_SESSION['uid'];
$property_id = $_GET['property_id'] ?? null;
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $time = $_POST['time'];
    $stmt = $con->prepare("INSERT INTO appointment (user_id, property_id, time, status) VALUES (?, ?, ?, 'Pending')");
    $stmt->bind_param("iis", $user_id, $property_id, $time);
    if ($stmt->execute()) {
        $msg = "<div class='alert alert-success'>✅ Appointment Requested successfully an email will be sent once Confrimed!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>❌ Error booking appointment.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Book Appointment</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="container mt-5">
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

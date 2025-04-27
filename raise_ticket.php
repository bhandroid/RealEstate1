<?php
session_start();
include("config.php");
include("include/header.php");

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['uid'];
$msg = $track_msg = "";

// Handle Ticket Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_ticket'])) {
    $comment = mysqli_real_escape_string($con, $_POST['comment']);
    $image = "";

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image);
    }

    $sql = "INSERT INTO tickets (user_id, comment, image) VALUES ('$user_id', '$comment', '$image')";

    if (mysqli_query($con, $sql)) {
        $ticket_id = mysqli_insert_id($con);
        $msg = "<p class='alert alert-success'>
            ✅ Ticket submitted successfully!<br>
            🎫 Your Ticket ID is <strong>#$ticket_id</strong>.
        </p>";
    } else {
        $msg = "<p class='alert alert-danger'>❌ Failed to submit ticket.</p>";
    }
}

// Handle Ticket Tracking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['track_ticket'])) {
    $track_id = intval($_POST['track_id']);
    $res = mysqli_query($con, "SELECT * FROM tickets WHERE ticket_id = $track_id");

    if (mysqli_num_rows($res) > 0) {
        $ticket = mysqli_fetch_assoc($res);
        $track_msg = "<div class='alert alert-info'>
            <strong>🎫 Ticket #{$ticket['ticket_id']}</strong><br>
            <strong>Status:</strong> {$ticket['status']}<br>";
        if ($ticket['status'] === 'Resolved') {
            $track_msg .= "<strong>Resolution:</strong><br>" . nl2br(htmlspecialchars($ticket['resolution']));
        }
        $track_msg .= "</div>";
    } else {
        $track_msg = "<div class='alert alert-danger'>❌ No ticket found with that ID under your account.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Raise Ticket</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">

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
    <h2 class="mb-4">🎫 Raise a Support Ticket</h2>
    <?= $msg ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="submit_ticket" value="1">
        <div class="form-group">
            <label>Your Issue <span class="text-danger">*</span></label>
            <textarea name="comment" class="form-control" rows="4" required placeholder="Describe your issue..."></textarea>
        </div>
        <div class="form-group">
            <label>Attach Screenshot (optional)</label>
            <input type="file" name="image" class="form-control-file">
        </div>
        <button type="submit" class="btn btn-primary">Submit Ticket</button>
    </form>

    <hr class="my-4">

    <h4>🔍 Track Your Ticket</h4>
    <form method="POST">
        <input type="hidden" name="track_ticket" value="1">
        <div class="form-group">
            <label>Enter Ticket ID:</label>
            <input type="number" name="track_id" class="form-control" placeholder="e.g. 101" required>
        </div>
        <button type="submit" class="btn btn-info">Check Status</button>
    </form>

    <?= $track_msg ?>
</body>
<?php include("include/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/custom.js"></script>
</html>

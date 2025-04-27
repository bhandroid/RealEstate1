<?php
session_start();
include("config.php");
$user_id = $_SESSION['uid'] ?? null;
$role = $_SESSION['role'] ?? '';
$property_id = $_GET['property_id'] ?? null;

if (!$user_id || !$property_id || !in_array($role, ['Seller', 'Agent'])) {
    die("❌ Access Denied");
}

// Confirm property ownership
$check = mysqli_query($con, "SELECT seller_id FROM property_listings WHERE property_id = $property_id");
$owner = mysqli_fetch_assoc($check);
if (!$owner || $owner['seller_id'] != $user_id) {
    die("❌ You do not own this property.");
}

// Handle POST: Accept or Reject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['interest_id'], $_POST['action'])) {
    $interest_id = (int)$_POST['interest_id'];
    $action = $_POST['action'];

    if ($action === 'Accept') {
        // Accept this and reject all others
        mysqli_query($con, "UPDATE rental_interest SET status = 'Accepted' WHERE interest_id = $interest_id");
        mysqli_query($con, "UPDATE rental_interest SET status = 'Rejected' WHERE property_id = $property_id AND interest_id != $interest_id");
        mysqli_query($con, "UPDATE property_listings SET status = 'Hold' WHERE property_id = $property_id");
    } elseif ($action === 'Reject') {
        mysqli_query($con, "UPDATE rental_interest SET status = 'Rejected' WHERE interest_id = $interest_id");
    }

    header("Location: view_rental_interest.php?property_id=$property_id");
    exit();
}
include("include/header.php");

// Fetch all rental interests
$result = mysqli_query($con, "
    SELECT ri.*, u.name, u.email 
    FROM rental_interest ri
    JOIN user u ON ri.buyer_id = u.user_id
    WHERE ri.property_id = $property_id
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rental Interest</title>
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
<body>
<h3>📋 Rental Interest for Property #<?= $property_id ?></h3>

<?php if (mysqli_num_rows($result) === 0): ?>
    <div class="alert alert-info">No one has shown interest yet.</div>
<?php else: ?>
    <table class="table table-bordered mt-3">
        <thead class="thead-dark">
            <tr>
                <th>Buyer</th>
                <th>Email</th>
                <th>Interest Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= date('d-m-Y', strtotime($row['interest_date'])) ?></td>
                <td><strong><?= $row['status'] ?></strong></td>
                <td>
                    <?php if ($row['status'] === 'Pending'): ?>
                        <form method="POST" class="form-inline">
                            <input type="hidden" name="interest_id" value="<?= $row['interest_id'] ?>">
                            <button name="action" value="Accept" class="btn btn-success btn-sm mr-2">Accept</button>
                            <button name="action" value="Reject" class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    <?php else: ?>
                        <span class="badge badge-secondary">Final</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>
</body> 
<?php include("include/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/custom.js"></script>
</html>

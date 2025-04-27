<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");


$user_id = $_SESSION['uid'] ?? null;
$role = ucfirst(strtolower($_SESSION['role'] ?? ''));
$property_id = $_GET['property_id'] ?? null;

if (!$user_id || !$property_id || !in_array(strtolower($role), ['seller', 'agent'])) {
    die(" Access denied.");
}

// Allow seller or agent to view their own properties
$check = mysqli_query($con, "SELECT seller_id FROM property_listings WHERE property_id = $property_id LIMIT 1");
$row = mysqli_fetch_assoc($check);

if (!$row) {
    die(" Property not found.");
}

if ((int)$row['seller_id'] !== (int)$user_id) {
    die(" You can only view appointments for properties you posted.");
}

// Handle form submission to update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'], $_POST['status'])) {
    $appointment_id = (int)$_POST['appointment_id'];
    $status = $_POST['status'];
    if (in_array($status, ['Accepted', 'Rejected'])) {
        $stmt = $con->prepare("UPDATE appointment SET status = ? WHERE appointment_id = ?");
        $stmt->bind_param("si", $status, $appointment_id);
        $stmt->execute();
    }
    // Redirect to avoid resubmission
    header("Location: view_appointments.php?property_id=$property_id");
    exit();
}

$result = mysqli_query($con, "
    SELECT a.*, u.name, u.email
    FROM appointment a
    JOIN user u ON a.user_id = u.user_id
    WHERE a.property_id = $property_id
");
?>
<!DOCTYPE html>
<html>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">

<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<head>
    <title>View Appointments</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
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
<body class="container mt-5">
    <h3 class="mb-4">📋 Appointments for Property #<?= $property_id ?></h3>

    <?php if (mysqli_num_rows($result) == 0): ?>
        <div class="alert alert-info">No appointment requests found for this property.</div>
    <?php else: ?>
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Email</th>
                <th>Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['appointment_id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= date('d-m-Y H:i', strtotime($row['time'])) ?></td>
                <td><?= $row['status'] ?></td>
                <td>
                    <?php if ($row['status'] === 'Pending'): ?>
                        <form method="POST" class="form-inline">
                            <input type="hidden" name="appointment_id" value="<?= $row['appointment_id'] ?>">
                            <button name="status" value="Accepted" class="btn btn-success btn-sm mr-1">Accept</button>
                            <button name="status" value="Rejected" class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    <?php else: ?>
                        <span class="badge badge-info"><?= $row['status'] ?></span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php endif; ?>
</body>
</html>
<?php include("include/footer.php"); ?>

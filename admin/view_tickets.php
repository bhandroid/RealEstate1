<?php
require("config.php");
session_start();

// Access Control
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Audit Log Function (Optional: you can reuse your audit log function here)

// Handle ticket resolution
if (isset($_POST['resolve'])) {
    $ticket_id = intval($_POST['ticket_id']);
    $resolution = mysqli_real_escape_string($con, $_POST['resolution']);

    $update = "UPDATE tickets SET status = 'Resolved', resolution = '$resolution' WHERE ticket_id = $ticket_id";
    mysqli_query($con, $update);
}

$result = mysqli_query($con, "
    SELECT t.ticket_id, t.comment, t.image, t.timestamp, t.status, t.resolution, u.name, u.email
    FROM tickets t
    JOIN user u ON t.user_id = u.user_id
    ORDER BY t.ticket_id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>LM Homes - Support Tickets</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/feathericon.min.css">
    <link rel="stylesheet" href="assets/plugins/morris/morris.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Header -->
<?php include("header.php"); ?>
<!-- /Header -->

<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">🛠️ Support Tickets Management</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">View Tickets</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <!-- Ticket Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-table">
                    <div class="card-header">
                        <h4 class="card-title">📋 Raised Tickets List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-center mb-0">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Comment</th>
                                        <th>Screenshot</th>
                                        <th>Status / Resolution</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $row['ticket_id'] ?></td>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td><?= nl2br(htmlspecialchars($row['comment'])) ?></td>
                                        <td>
                                            <?php if ($row['image']): ?>
                                                <a href="../uploads/<?= $row['image'] ?>" target="_blank">View Image</a>
                                            <?php else: ?>
                                                No Image
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?= $row['status'] ?? 'Open' ?></strong><br>
                                            <?php if ($row['status'] === 'Resolved'): ?>
                                                <div><small><strong>Note:</strong> <?= nl2br(htmlspecialchars($row['resolution'])) ?></small></div>
                                            <?php else: ?>
                                                <form method="POST" class="mt-2">
                                                    <input type="hidden" name="ticket_id" value="<?= $row['ticket_id'] ?>">
                                                    <textarea name="resolution" placeholder="Resolution..." class="form-control mb-1" required></textarea>
                                                    <button type="submit" name="resolve" class="btn btn-sm btn-success">Mark as Resolved</button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Ticket Table -->

    </div>
</div>
<!-- /Page Wrapper -->

<!-- Scripts -->
<script src="assets/js/jquery-3.2.1.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/raphael/raphael.min.js"></script>
<script src="assets/plugins/morris/morris.min.js"></script>
<script src="assets/js/chart.morris.js"></script>
<script src="assets/js/script.js"></script>

</body>
</html>

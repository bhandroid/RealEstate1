<?php
session_start();
include("config.php");

// ✅ Access Control
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<p style='color:red;'>Access denied. Admins only.</p>";
    exit();
}

// ✅ Handle ticket resolution
if (isset($_POST['resolve'])) {
    $ticket_id = intval($_POST['ticket_id']);
    $resolution = mysqli_real_escape_string($con, $_POST['resolution']);

    $update = "UPDATE tickets SET status = 'Resolved', resolution = '$resolution' WHERE ticket_id = $ticket_id";
    mysqli_query($con, $update);
}

// ✅ Fetch tickets with status and resolution
$result = mysqli_query($con, "
    SELECT t.ticket_id, t.comment, t.image, t.timestamp, t.status, t.resolution, u.name, u.email
    FROM tickets t
    JOIN user u ON t.user_id = u.user_id
    ORDER BY t.ticket_id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Support Tickets</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2 class="mb-4">📋 All Raised Tickets</h2>

    <table class="table table-bordered">
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
                        <a href="../uploads/<?= $row['image'] ?>" target="_blank">View</a>
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
                            <textarea name="resolution" placeholder="Resolution..." class="form-control" required></textarea>
                            <button type="submit" name="resolve" class="btn btn-sm btn-success mt-1">Mark Resolved</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

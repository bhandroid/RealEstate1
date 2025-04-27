<?php
session_start();
include("config.php");
include("functions.php");  // ✅ Include your audit log function
include("include/header.php");

$user_id = $_SESSION['uid'] ?? null;
$role = $_SESSION['role'] ?? '';
$property_id = $_GET['property_id'] ?? null;

if (!$user_id || !$property_id || !in_array($role, ['Seller', 'Agent'])) {
    die("❌ Access denied.");
}

$check = mysqli_query($con, "SELECT * FROM property_listings WHERE property_id = $property_id AND seller_id = $user_id");
$property = mysqli_fetch_assoc($check);

if (!$property) {
    die("❌ Property not found or not owned by you.");
}

$msg = "";

// ✅ Update Property Details (With Audit Log)
if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $location = $_POST['location'];

    $stmt = $con->prepare("UPDATE property_listings SET title = ?, description = ?, price = ?, location = ? WHERE property_id = ?");
    $stmt->bind_param("ssdsi", $title, $description, $price, $location, $property_id);

    if ($stmt->execute()) {
        // ✅ Audit Log for Property Edit
        addAuditLog($user_id, 'EDIT_PROPERTY', 'Edited property with ID: ' . $property_id);
        $msg = "✅ Property updated successfully.";
    } else {
        $msg = "❌ Failed to update property.";
    }
}

// ✅ Handle Add More Images (Image Upload)
if (isset($_POST['add_image']) && isset($_FILES['new_image']['name'])) {
    $image_name = $_FILES['new_image']['name'];
    $image_tmp = $_FILES['new_image']['tmp_name'];
    $target_dir = "admin/property/";
    $target_file = $target_dir . basename($image_name);

    if (move_uploaded_file($image_tmp, $target_file)) {
        $stmt = $con->prepare("INSERT INTO property_image (property_id, image_url) VALUES (?, ?)");
        $stmt->bind_param("is", $property_id, $image_name);
        if ($stmt->execute()) {
            // ✅ Optional Audit Log for Image Upload
            addAuditLog($user_id, 'ADD_PROPERTY_IMAGE', 'Added new image for Property ID: ' . $property_id);
            $msg = "✅ Image uploaded successfully.";
        }
    } else {
        $msg = "❌ Failed to upload image.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Property with Images</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/layerslider.css">
    <link rel="stylesheet" type="text/css" href="css/color.css" id="color-change">
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        .center-form {
            max-width: 600px;
            margin: 30px auto;
            text-align: left;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="center-form">
        <h3>Edit Property #<?= $property_id ?></h3>
        <?php if (!empty($msg)) echo "<div class='alert alert-info'>$msg</div>"; ?>

        <!-- ✅ Property Edit Form -->
        <form method="POST">
            <div class="form-group">
                <label>Title:</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($property['title']) ?>" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" class="form-control" required><?= htmlspecialchars($property['description']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Price:</label>
                <input type="number" name="price" class="form-control" value="<?= $property['price'] ?>" required>
            </div>
            <div class="form-group">
                <label>Location:</label>
                <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($property['location']) ?>" required>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update Property</button>
        </form>

        <hr>

        <!-- ✅ Add More Images -->
        <h4>Add More Images</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <input type="file" name="new_image" required>
            </div>
            <button type="submit" name="add_image" class="btn btn-secondary">Upload Image</button>
        </form>
    </div>

    <!-- ✅ Show Existing Images -->
    <h5 class="mt-4 text-center">Uploaded Images:</h5>
    <div class="row justify-content-center">
        <?php
        $image_result = mysqli_query($con, "SELECT * FROM property_image WHERE property_id = $property_id");
        while ($img = mysqli_fetch_assoc($image_result)):
        ?>
            <div class="col-md-3 mb-3">
                <img src="admin/property/<?= htmlspecialchars($img['image_url']) ?>" class="img-thumbnail">
            </div>
        <?php endwhile; ?>
    </div>
</body>

<?php include("include/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/custom.js"></script>
</html>

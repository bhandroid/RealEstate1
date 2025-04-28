<?php
session_start();
include("config.php");
include("functions.php"); // ✅ Include your audit log function
include("include/header.php");

$user_id = $_SESSION['uid'] ?? null;
$role = $_SESSION['role'] ?? '';
$property_id = $_GET['property_id'] ?? null;

if (!$user_id || !$property_id || !in_array($role, ['Seller', 'Agent'])) {
    die("❌ Access denied.");
}

$check = mysqli_query($con, "SELECT * FROM property_listings WHERE property_id = $property_id AND seller_id = $user_id");
$property = mysqli_fetch_assoc($check);
$rental_data = [
    'available_date' => '',
    'security_deposit' => ''
];

if (strtolower($property['property_type']) === 'rental') {
    $rental_check = mysqli_query($con, "SELECT available_date, security_deposit FROM rental_contracts WHERE property_id = $property_id");
    if ($rental_row = mysqli_fetch_assoc($rental_check)) {
        $rental_data = $rental_row;
    }
}


if (!$property) {
    die("❌ Property not found or not owned by you.");
}

$msg = "";

// ✅ Update Property Details (With Audit Log)
if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $street = $_POST['street'];
    $location = $_POST['location'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $property_type = $_POST['property_type'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $size_sqft = $_POST['size_sqft'];
    $pool_available = $_POST['pool_available'];
    $is_dog_friendly = $_POST['is_dog_friendly'];
    $nearest_school = $_POST['nearest_school'];
    $bus_availability = $_POST['bus_availability'];
    $tram_availability = $_POST['tram_availability'];
    $status = $_POST['status'];

    $available_date = $_POST['available_date'] ?? null;
    $security_deposit = $_POST['security_deposit'] ?? null;

    // Check if property type is rental
    if (strtolower($property_type) === 'rental') {
        // Check if a rental contract exists
        $rental_check = mysqli_query($con, "SELECT * FROM rental_contracts WHERE property_id = $property_id");
        
        if (mysqli_num_rows($rental_check) > 0) {
            // Update rental contract
            $stmt_rental = $con->prepare("UPDATE rental_contracts SET available_date = ?, security_deposit = ? WHERE property_id = ?");
            $stmt_rental->bind_param("sdi", $available_date, $security_deposit, $property_id);
        } else {
            // Insert rental contract
            $stmt_rental = $con->prepare("INSERT INTO rental_contracts (property_id, available_date, security_deposit) VALUES (?, ?, ?)");
            $stmt_rental->bind_param("isd", $property_id, $available_date, $security_deposit);
        }
        $stmt_rental->execute();

    } else {
        // If changed to Sale, remove any existing rental contract
        mysqli_query($con, "DELETE FROM rental_contracts WHERE property_id = $property_id");
    }

    




    $stmt = $con->prepare("UPDATE property_listings SET title = ?, description = ?, price = ?, street = ?, location = ?, state = ?, zip = ?, property_type = ?, bedrooms = ?, bathrooms = ?, size_sqft = ?, pool_available = ?, is_dog_friendly = ?, nearest_school = ?, bus_availability = ?, tram_availability = ?, status = ? WHERE property_id = ?");
    $stmt->bind_param("ssdsssssiidssssssi", $title, $description, $price, $street, $location, $state, $zip, $property_type, $bedrooms, $bathrooms, $size_sqft, $pool_available, $is_dog_friendly, $nearest_school, $bus_availability, $tram_availability, $status, $property_id);

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
    <meta charset="utf-8">
    <title>Edit Property | Real Estate PHP</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style.css">
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
    <style>
        .center-form {
            max-width: 700px;
            margin: 30px auto;
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
            <div class="form-group"><label>Title:</label><input type="text" name="title" class="form-control" value="<?= htmlspecialchars($property['title']) ?>" required></div>
            <div class="form-group"><label>Description:</label><textarea name="description" class="form-control" required><?= htmlspecialchars($property['description']) ?></textarea></div>
            <div class="form-group"><label>Price:</label><input type="number" name="price" class="form-control" value="<?= $property['price'] ?>" required></div>
            <div class="form-group"><label>Street:</label><input type="text" name="street" class="form-control" value="<?= htmlspecialchars($property['street']) ?>" required></div>
            <div class="form-group"><label>City:</label><input type="text" name="location" class="form-control" value="<?= htmlspecialchars($property['location']) ?>" required></div>
            <div class="form-group"><label>State:</label><input type="text" name="state" class="form-control" value="<?= htmlspecialchars($property['state']) ?>" required></div>
            <div class="form-group"><label>Zip:</label><input type="text" name="zip" class="form-control" value="<?= htmlspecialchars($property['zip']) ?>" required></div>
            <div class="form-group"><label>Property Type:</label>
                <select name="property_type" class="form-control" id="property_type" required>

                    <option value="Sale" <?= $property['property_type'] === 'Sale' ? 'selected' : '' ?>>Sale</option>
                    <option value="Rental" <?= $property['property_type'] === 'Rental' ? 'selected' : '' ?>>Rental</option>
                </select>
            </div>
                

            <div id="rental_fields" style="display: none;">
            <div class="form-group">
             <label>Available Date:</label>
                 <input type="date" name="available_date" class="form-control" 
                 value="<?= htmlspecialchars($rental_data['available_date']) ?>">
                 </div>
                <div class="form-group">
                <label>Security Deposit:</label>
                    <input type="number" name="security_deposit" class="form-control" 
                    value="<?= htmlspecialchars($rental_data['security_deposit']) ?>">
                    </div>
            </div>

            <div class="form-group"><label>Bedrooms:</label><input type="number" name="bedrooms" class="form-control" value="<?= $property['bedrooms'] ?>" required></div>
            <div class="form-group"><label>Bathrooms:</label><input type="number" name="bathrooms" class="form-control" value="<?= $property['bathrooms'] ?>" required></div>
            <div class="form-group"><label>Size (sqft):</label><input type="number" name="size_sqft" class="form-control" value="<?= $property['size_sqft'] ?>" required></div>
            <div class="form-group"><label>Pool Available:</label>
                <select name="pool_available" class="form-control">
                    <option value="Yes" <?= $property['pool_available'] === 'Yes' ? 'selected' : '' ?>>Yes</option>
                    <option value="No" <?= $property['pool_available'] === 'No' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
            <div class="form-group"><label>Dog Friendly:</label>
                <select name="is_dog_friendly" class="form-control">
                    <option value="Yes" <?= $property['is_dog_friendly'] === 'Yes' ? 'selected' : '' ?>>Yes</option>
                    <option value="No" <?= $property['is_dog_friendly'] === 'No' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
            <div class="form-group"><label>Nearest School:</label><input type="text" name="nearest_school" class="form-control" value="<?= htmlspecialchars($property['nearest_school']) ?>"></div>
            <div class="form-group"><label>Bus Availability:</label>
                <select name="bus_availability" class="form-control">
                    <option value="Yes" <?= $property['bus_availability'] === 'Yes' ? 'selected' : '' ?>>Yes</option>
                    <option value="No" <?= $property['bus_availability'] === 'No' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
            <div class="form-group"><label>Tram Availability:</label>
                <select name="tram_availability" class="form-control">
                    <option value="Yes" <?= $property['tram_availability'] === 'Yes' ? 'selected' : '' ?>>Yes</option>
                    <option value="No" <?= $property['tram_availability'] === 'No' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
            <div class="form-group"><label>Status:</label>
                <select name="status" class="form-control" required>
                    <option value="available" <?= $property['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                    <option value="sold" <?= $property['status'] === 'sold' ? 'selected' : '' ?>>Sold</option>
                </select>
            </div>
            <button type="submit" name="update" class="btn btn-primary btn-block">Update Property</button>
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
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const propertyType = document.getElementById('property_type');
        const rentalFields = document.getElementById('rental_fields');

        function toggleRentalFields() {
            if (propertyType.value.toLowerCase() === 'rental') {
                rentalFields.style.display = 'block';
            } else {
                rentalFields.style.display = 'none';
            }
        }

        propertyType.addEventListener('change', toggleRentalFields);
        toggleRentalFields(); // ✅ Call once to show/hide on page load based on existing value
    });
</script>

</body>
<?php include("include/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/custom.js"></script>
</html>

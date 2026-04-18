<?php
session_start();
include 'db_connect.php';

// Task 1e: Restrict unauthorized access
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Task 7: Security Practices (XSS prevention)
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $price = $_POST['price'];
    $user_id = $_SESSION['user_id'];

    // Task 3c & 4: File Upload handling with Validation
    $file_name = $_FILES['service_image']['name'];
    $file_size = $_FILES['service_image']['size'];
    $file_tmp = $_FILES['service_image']['tmp_name'];
    $file_type = $_FILES['service_image']['type'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    $extensions = array("jpeg", "jpg", "png");

    if (in_array($file_ext, $extensions) === false) {
        $error = "Extension not allowed, please choose a JPEG or PNG file.";
    } elseif ($file_size > 2097152) { // Had 2MB
        $error = "File size must be less than 2 MB.";
    } else {
        // Cipta folder uploads jika belum ada
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        // Simpan gambar dengan nama unik
        $new_file_name = time() . "_" . $file_name;
        $target_file = "uploads/" . $new_file_name;

        if (move_uploaded_file($file_tmp, $target_file)) {
            // Task 7: Prepared Statements (5 parameters: isssd)
            // Simpan 'target_file' supaya database tahu lokasi folder 'uploads/'
            $stmt = $conn->prepare("INSERT INTO services (user_id, title, description, price, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isssd", $user_id, $title, $description, $price, $target_file);
            
            if ($stmt->execute()) {
                $success = "Service added successfully!";
            } else {
                $error = "Database Error: " . $conn->error;
            }
            $stmt->close();
        } else {
            $error = "Failed to upload image. Check folder permissions.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Service - Campus Service Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-4">
                    <h3 class="text-center mb-4">Add New Service</h3>
                    
                    <?php if($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <?php if($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Service Title</label>
                            <input type="text" name="title" class="form-control" placeholder="E.g. Laptop Repair" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Describe your service..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price (RM)</label>
                            <input type="number" name="price" step="0.01" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Service Image (JPG/PNG)</label>
                            <input type="file" name="service_image" class="form-control" required>
                            <small class="text-muted">Max size: 2MB</small>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Post Service Now</button>
                            <a href="dashboard.php" class="btn btn-outline-secondary">Back to Dashboard</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
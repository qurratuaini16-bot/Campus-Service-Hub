<?php
// Task 1e: Start a session to check the login status
session_start();

// If the user is not logged in, send them back to login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Campus Service Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Campus Service Hub</a>
            <div class="navbar-nav ms-auto">
                <span class="nav-link text-white">Welcome, <?php echo $_SESSION['username']; ?> (<?php echo $_SESSION['role']; ?>)</span>
                <a class="nav-link btn btn-danger btn-sm ms-2 text-white" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h2>Main Dashboard</h2>
                        <p class="lead">Manage your services and skills effectively.</p>
                        <hr>
                        
                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <a href="add_service.php" class="btn btn-primary btn-lg">Add New Service</a>
                            <a href="index.php" class="btn btn-outline-secondary btn-lg">View All Services</a>
                            
                            <?php if ($_SESSION['role'] == 'Admin'): ?>
                                <a href="manage_users.php" class="btn btn-warning btn-lg">Manage Users (Admin Only)</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
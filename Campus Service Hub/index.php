<?php
// Start session for login/logout navigation
session_start();
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Service Hub - Find Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top { height: 200px; object-fit: cover; }
        .hero-section { background: #f8f9fa; padding: 40px 0; margin-bottom: 30px; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Campus Service Hub</a>
            <div class="navbar-nav ms-auto">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                    <a class="nav-link btn btn-danger btn-sm text-white ms-2" href="logout.php">Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <header class="hero-section text-center">
        <div class="container">
            <h1 class="fw-bold">Discover Campus Services</h1>
            <p class="text-muted">Type below to find skills and services instantly.</p>
            <div class="row justify-content-center mt-4">
                <div class="col-md-6">
                    <input type="text" id="searchBox" class="form-control form-control-lg shadow-sm" 
                           placeholder="Search by title (e.g. Printing, Food, Tutor)..." 
                           onkeyup="liveSearch(this.value)">
                </div>
            </div>
        </div>
    </header>

    <div class="container mb-5">
        <div id="resultsArea">
            <div class="text-center">Loading services...</div>
        </div>
    </div>

    <script>
        function liveSearch(query) {
            const resultsArea = document.getElementById("resultsArea");
            
            // fetch() sends request to your search_process.php
            fetch('search_process.php?q=' + encodeURIComponent(query))
                .then(response => response.text())
                .then(data => {
                    resultsArea.innerHTML = data;
                })
                .catch(err => {
                    console.error('Error:', err);
                    resultsArea.innerHTML = "Error loading services.";
                });
        }

        // Run once on page load to show all services initially
        window.onload = () => liveSearch('');
    </script>

</body>
</html>
<?php
include 'db_connect.php';

$q = $_GET['q'] ?? '';

// Task 6: JOIN to get the owner's username
// Task 7: Prepared Statement for SQL Injection prevention
$query = "SELECT services.*, users.username 
          FROM services 
          JOIN users ON services.user_id = users.id 
          WHERE services.title LIKE ? 
          ORDER BY services.id DESC";

$stmt = $conn->prepare($query);
$search_term = "%$q%";
$stmt->bind_param("s", $search_term);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo '<div class="row">';
    while($row = $result->fetch_assoc()) {
        echo '
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="'.$row['image'].'" class="card-img-top" alt="service" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">'.htmlspecialchars($row['title']).'</h5>
                    <p class="card-text">'.htmlspecialchars($row['description']).'</p>
                    <p class="text-primary fw-bold">RM '.$row['price'].'</p>
                    <small class="text-muted">Posted by: '.$row['username'].'</small>
                </div>
            </div>
        </div>';
    }
    echo '</div>';
} else {
    echo "<p class='text-center'>No services found matching your search.</p>";
}
?>
<?php
session_start();
include 'config/database.php';

$cars = $conn->query("SELECT * FROM cars WHERE status='available' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Available Cars</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<nav>
    <div class="nav-left">
        <a href="index.php" class="nav-logo">Drive<span>Ease</span></a>
    </div>
    <div class="nav-right">
        <a href="index.php">Home</a>
        <a href="cars.php">Cars</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="user/dashboard.php">My Bookings</a>
            <a href="auth/logout.php">Logout</a>
        <?php else: ?>
            <a href="auth/login.php">Login</a>
            <a href="auth/register.php">Register</a>
        <?php endif; ?>
    </div>
</nav>
<div class="container">
    <h1>Available Cars</h1>
    <div class="car-grid">
        <?php while ($car = $cars->fetch_assoc()): ?>
        <div class="car-card">
            <img src="uploads/<?= $car['image'] ?>" alt="<?= $car['name'] ?>">
            <h3><?= $car['name'] ?></h3>
            <p><?= $car['brand'] ?></p>
            <p><strong>$<?= $car['price_per_day'] ?>/day</strong></p>
            <?php if (isset($_SESSION['user_id'])): ?>
           <a href="car_detail.php?id=<?= $car['id'] ?>" class="btn">View Details</a>
            <?php else: ?>
            <a href="auth/login.php" class="btn">Login to Book</a>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
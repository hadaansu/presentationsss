<?php
session_start();
include 'config/database.php';

$id  = $_GET['id'];
$car = $conn->query("SELECT * FROM cars WHERE id=$id")->fetch_assoc();

if (!$car) {
    header("Location: cars.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title><?= $car['name'] ?> - DriveEase</title><link rel="stylesheet" href="assets/css/style.css"></head>
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
    <a href="cars.php" style="color:#7B1F2E;text-decoration:none;font-size:14px;">← Back to Cars</a>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;margin-top:24px;background:#fff;padding:30px;border-radius:12px;border:1px solid #e0d4cc;">
        
        <!-- Left: Image -->
        <div>
            <img src="uploads/<?= $car['image'] ?>" alt="<?= $car['name'] ?>" 
                 style="width:100%;height:320px;object-fit:cover;border-radius:10px;">
        </div>

        <!-- Right: Details -->
        <div>
            <h1 style="color:#5C1520;font-size:32px;margin-bottom:8px;"><?= $car['name'] ?></h1>
            <p style="color:#999;font-size:16px;margin-bottom:20px;"><?= $car['brand'] ?></p>

            <div style="background:#FDF8F3;padding:16px;border-radius:8px;margin-bottom:20px;">
                <p style="font-size:28px;font-weight:bold;color:#7B1F2E;">
                    $<?= number_format($car['price_per_day'], 2) ?>
                    <span style="font-size:14px;font-weight:normal;color:#999;">/ day</span>
                </p>
            </div>

            <div style="margin-bottom:20px;">
                <p style="font-size:14px;color:#6B3A2A;line-height:1.7;"><?= $car['description'] ?></p>
            </div>

            <div style="margin-bottom:24px;">
                <span style="background:<?= $car['status']=='available' ? '#d4edda' : '#f8d7da' ?>;
                             color:<?= $car['status']=='available' ? '#155724' : '#721c24' ?>;
                             padding:6px 16px;border-radius:20px;font-size:13px;font-weight:bold;">
                    <?= ucfirst($car['status']) ?>
                </span>
            </div>

            <?php if ($car['status'] == 'available'): ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="user/book.php?id=<?= $car['id'] ?>" class="btn" 
                       style="display:inline-block;padding:14px 32px;font-size:16px;">
                        Book Now
                    </a>
                <?php else: ?>
                    <a href="auth/login.php" class="btn" 
                       style="display:inline-block;padding:14px 32px;font-size:16px;">
                        Login to Book
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <button disabled class="btn" style="background:#ccc;cursor:not-allowed;padding:14px 32px;">
                    Not Available
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
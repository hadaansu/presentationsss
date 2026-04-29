<?php
session_start();
if ($_SESSION['role'] != 'admin') { header("Location: ../auth/login.php"); exit; }
include '../config/database.php';

$cars = $conn->query("SELECT * FROM cars ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Manage Cars</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="sidebar">
    <h2>DriveEase</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="cars.php" class="active">Cars</a>
    <a href="bookings.php">Bookings</a>
    <a href="users.php">Users</a>
    <a href="../auth/logout.php">Logout</a>
</div>
<div class="main">
    <h1>Cars <a href="add_car.php" class="btn">+ Add Car</a></h1>
    <table>
        <tr><th>Image</th><th>Name</th><th>Brand</th><th>Price/Day</th><th>Status</th><th>Actions</th></tr>
        <?php while ($car = $cars->fetch_assoc()): ?>
        <tr>
            <td><img src="../uploads/<?= $car['image'] ?>" width="80"></td>
            <td><?= $car['name'] ?></td>
            <td><?= $car['brand'] ?></td>
            <td>$<?= $car['price_per_day'] ?></td>
            <td><?= $car['status'] ?></td>
            <td>
                <a href="edit_car.php?id=<?= $car['id'] ?>">Edit</a>
                <a href="delete_car.php?id=<?= $car['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
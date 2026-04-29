<?php
session_start();
if ($_SESSION['role'] != 'admin') { header("Location: ../auth/login.php"); exit; }
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name         = $_POST['name'];
    $brand        = $_POST['brand'];
    $price_per_day = $_POST['price_per_day'];
    $description  = $_POST['description'];
    $status       = 'available';
    $image        = 'default-car.jpg';

    if (!empty($_FILES['image']['name'])) {
        $image = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/$image");
    }

    $stmt = $conn->prepare("INSERT INTO cars (name, brand, price_per_day, description, image, status) VALUES (?,?,?,?,?,?)");
$stmt->bind_param("ssdsss", $name, $brand, $price_per_day, $description, $image, $status);
$stmt->execute();
    header("Location: cars.php");
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Car</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="sidebar">
    <h2>DriveEase</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="cars.php" class="active">Cars</a>
    <a href="../auth/logout.php">Logout</a>
</div>
<div class="main">
    <h1>Add New Car</h1>
    <form method="POST" enctype="multipart/form-data" class="form-box">
        <input type="text"   name="name"          placeholder="Car Name"      required>
        <input type="text"   name="brand"         placeholder="Brand"         required>
        <input type="number" name="price_per_day"  placeholder="Price Per Day" required step="0.01">
        <textarea name="description" placeholder="Description"></textarea>
        <input type="file"   name="image" accept="image/*">
        <button type="submit">Add Car</button>
    </form>
</div>
</body>
</html>
<?php
session_start();
if ($_SESSION['role'] != 'admin') { header("Location: ../auth/login.php"); exit; }
include '../config/database.php';

$id  = $_GET['id'];
$car = $conn->query("SELECT * FROM cars WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name          = $_POST['name'];
    $brand         = $_POST['brand'];
    $price_per_day = $_POST['price_per_day'];
    $description   = $_POST['description'];
    $status        = $_POST['status'];
    $image         = $car['image'];

    if (!empty($_FILES['image']['name'])) {
        $image = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/$image");
    }

    $stmt = $conn->prepare("UPDATE cars SET name=?, brand=?, price_per_day=?, description=?, image=?, status=? WHERE id=?");
    $stmt->bind_param("ssdsssi", $name, $brand, $price_per_day, $description, $image, $status, $id);
    $stmt->execute();
    header("Location: cars.php");
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Car</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="sidebar">
    <h2>DriveEase</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="cars.php" class="active">Cars</a>
    <a href="../auth/logout.php">Logout</a>
</div>
<div class="main">
    <h1>Edit Car</h1>
    <form method="POST" enctype="multipart/form-data" class="form-box">
        <input type="text"   name="name"          value="<?= $car['name'] ?>"          required>
        <input type="text"   name="brand"         value="<?= $car['brand'] ?>"         required>
        <input type="number" name="price_per_day"  value="<?= $car['price_per_day'] ?>" required step="0.01">
        <textarea name="description"><?= $car['description'] ?></textarea>
        <select name="status">
            <option value="available"  <?= $car['status']=='available'  ? 'selected':'' ?>>Available</option>
            <option value="unavailable"<?= $car['status']=='unavailable'? 'selected':'' ?>>Unavailable</option>
        </select>
        <input type="file" name="image" accept="image/*">
        <button type="submit">Update Car</button>
    </form>
</div>
</body>
</html>
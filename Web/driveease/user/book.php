<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
include '../config/database.php';

$car_id = $_GET['id'];
$car    = $conn->query("SELECT * FROM cars WHERE id=$car_id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start = $_POST['start_date'];
    $end   = $_POST['end_date'];
    $days  = (strtotime($end) - strtotime($start)) / 86400;
    $total = $days * $car['price_per_day'];
    $uid   = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO bookings (user_id, car_id, start_date, end_date, total_price, status) VALUES (?,?,?,?,?,'booked')");
    $stmt->bind_param("iissd", $uid, $car_id, $start, $end, $total);
    $stmt->execute();
    $booking_id = $conn->insert_id;

    header("Location: payment.php?booking_id=$booking_id&total=$total");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Book Car</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="sidebar">
    <h2>DriveEase</h2>
    <a href="../cars.php">Browse Cars</a>
    <a href="dashboard.php">My Bookings</a>
    <a href="../auth/logout.php">Logout</a>
</div>
<div class="main">
    <h1>Book: <?= $car['name'] ?></h1>
    <img src="../uploads/<?= $car['image'] ?>" width="200" style="border-radius:10px;margin-bottom:16px;">
    <p><strong>Brand:</strong> <?= $car['brand'] ?></p>
    <p><strong>Price:</strong> $<?= $car['price_per_day'] ?>/day</p>
    <br>
    <form method="POST" class="form-box">
        <label>Start Date</label>
        <input type="date" name="start_date" required min="<?= date('Y-m-d') ?>">
        <label>End Date</label>
        <input type="date" name="end_date" required min="<?= date('Y-m-d') ?>">
        <p id="day-info" style="color:#7B1F2E;font-weight:bold;margin-bottom:10px;"></p>
        <button type="submit" class="btn">Proceed to Payment</button>
    </form>
</div>
<script>
const start = document.querySelector('input[name="start_date"]');
const end   = document.querySelector('input[name="end_date"]');
function calc() {
    if (start.value && end.value) {
        const days = Math.ceil((new Date(end.value) - new Date(start.value)) / 86400000);
        if (days > 0) {
            document.getElementById('day-info').textContent = days + ' day(s) — Total: $' + (days * <?= $car['price_per_day'] ?>).toFixed(2);
        }
    }
}
start.addEventListener('change', calc);
end.addEventListener('change', calc);
</script>
</body>
</html>
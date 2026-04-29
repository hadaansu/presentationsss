<?php
session_start();
if ($_SESSION['role'] != 'admin') { header("Location: ../auth/login.php"); exit; }
include '../config/database.php';

$cars      = $conn->query("SELECT COUNT(*) AS total FROM cars")->fetch_assoc()['total'];
$users     = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='user'")->fetch_assoc()['total'];
$bookings  = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'];
$paid      = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status='paid'")->fetch_assoc()['total'];
$cancelled = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status='cancelled'")->fetch_assoc()['total'];
$revenue   = $conn->query("SELECT SUM(total_price) AS total FROM bookings WHERE status='paid'")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html>
<head><title>Admin Dashboard</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="sidebar">
    <h2>DriveEase</h2>
    <a href="dashboard.php" class="active">Dashboard</a>
    <a href="cars.php">Cars</a>
    <a href="bookings.php">Bookings</a>
    <a href="users.php">Users</a>
    <a href="../auth/logout.php">Logout</a>
</div>
<div class="main">
    <h1>Welcome, <?= $_SESSION['name'] ?> 👋</h1>
    <div class="stats">
        <div class="card">Total Cars<span><?= $cars ?></span></div>
        <div class="card">Total Users<span><?= $users ?></span></div>
        <div class="card">Total Bookings<span><?= $bookings ?></span></div>
        <div class="card">Paid Bookings<span><?= $paid ?></span></div>
        <div class="card">Cancelled<span><?= $cancelled ?></span></div>
        <div class="card">Total Revenue<span>$<?= number_format($revenue ?? 0, 2) ?></span></div>
    </div>

    <h2 style="margin-bottom:16px;">Recent Bookings</h2>
    <?php
    $recent = $conn->query("
        SELECT b.*, u.name AS user_name, c.name AS car_name
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN cars  c ON b.car_id  = c.id
        ORDER BY b.id DESC LIMIT 5
    ");
    ?>
    <table>
        <tr><th>User</th><th>Car</th><th>Total</th><th>Payment</th><th>Status</th></tr>
        <?php while ($b = $recent->fetch_assoc()): ?>
        <tr>
            <td><?= $b['user_name'] ?></td>
            <td><?= $b['car_name'] ?></td>
            <td>$<?= number_format($b['total_price'], 2) ?></td>
            <td><?= isset($b['payment_method']) ? ucfirst(str_replace('_',' ',$b['payment_method'])) : '-' ?></td>
            <td>
                <?php
                $status = $b['status'];
                if ($status == 'paid')      echo "<span style='color:green;font-weight:bold;'>Paid</span>";
                elseif ($status == 'cancelled') echo "<span style='color:red;font-weight:bold;'>Cancelled</span>";
                else echo "<span style='color:orange;font-weight:bold;'>".ucfirst($status)."</span>";
                ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
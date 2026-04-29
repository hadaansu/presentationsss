<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
include '../config/database.php';

$uid      = $_SESSION['user_id'];
$bookings = $conn->query("
    SELECT b.*, c.name AS car_name, c.image, c.brand
    FROM bookings b
    JOIN cars c ON b.car_id = c.id
    WHERE b.user_id = $uid
    ORDER BY b.id DESC
");
?>
<!DOCTYPE html>
<html>
<head><title>My Bookings</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="sidebar">
    <h2>DriveEase</h2>
    <a href="../cars.php">Browse Cars</a>
    <a href="dashboard.php" class="active">My Bookings</a>
    <a href="../auth/logout.php">Logout</a>
</div>
<div class="main">
    <h1>My Bookings</h1>

  <?php if (isset($_GET['success'])): ?>
<div style="background:#d4edda;color:#155724;padding:14px;border-radius:8px;margin-bottom:20px;">
    ✅ Payment successful! Your car is booked.
</div>
<?php endif; ?>

<?php if (isset($_GET['cancelled'])): ?>
<div style="background:#fdecea;color:#c0392b;padding:14px;border-radius:8px;margin-bottom:20px;">
    ❌ Booking has been cancelled successfully.
</div>
<?php endif; ?>

    <?php if ($bookings->num_rows == 0): ?>
    <div style="background:#fff;padding:30px;border-radius:10px;text-align:center;color:#6B3A2A;">
        <h3>No bookings yet!</h3>
        <p>Browse cars and make your first booking.</p>
        <a href="../cars.php" class="btn" style="margin-top:14px;">Browse Cars</a>
    </div>
    <?php else: ?>
    <table>
        <tr><th>Car</th><th>From</th><th>To</th><th>Total</th><th>Payment</th><th>Status</th><th>Action</th></tr>
        <?php while ($b = $bookings->fetch_assoc()): ?>
        <tr>
            <td>
                <img src="../uploads/<?= $b['image'] ?>" width="60" style="border-radius:6px;vertical-align:middle;margin-right:8px;">
                <?= $b['car_name'] ?><br>
                <small style="color:#999;"><?= $b['brand'] ?></small>
            </td>
            <td><?= $b['start_date'] ?></td>
            <td><?= $b['end_date'] ?></td>
            <td><strong>$<?= number_format($b['total_price'], 2) ?></strong></td>
            <td><?= isset($b['payment_method']) ? ucfirst(str_replace('_',' ',$b['payment_method'])) : '-' ?></td>
            <td>
               <?php
$status = $b['status'];
if ($status == 'paid')          echo "<span style='color:green;font-weight:bold;'>Paid</span>";
elseif ($status == 'unpaid')    echo "<span style='color:orange;font-weight:bold;'>Unpaid (Cash)</span>";
elseif ($status == 'cancelled') echo "<span style='color:red;font-weight:bold;'>Cancelled</span>";
elseif ($status == 'booked')    echo "<span style='color:blue;font-weight:bold;'>Booked</span>";
else echo "<span style='color:gray;font-weight:bold;'>".ucfirst($status)."</span>";
?>
            </td>
           <td>
    <?php if ($b['status'] == 'booked' || $b['status'] == 'paid' || $b['status'] == 'unpaid'): ?>
    <a href="cancel.php?id=<?= $b['id'] ?>" 
       onclick="return confirm('Are you sure you want to cancel this booking?')" 
       style="color:red;font-weight:bold;text-decoration:none;">Cancel</a>
    <?php else: ?>
    <span style="color:#ccc;">—</span>
    <?php endif; ?>
</td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php endif; ?>
</div>
</body>
</html>
<?php
session_start();
if ($_SESSION['role'] != 'admin') { header("Location: ../auth/login.php"); exit; }
include '../config/database.php';

$bookings = $conn->query("
    SELECT b.*, u.name AS user_name, c.name AS car_name, c.brand,
        CASE 
            WHEN b.status NOT IN ('returned','cancelled','rejected') 
                 AND b.end_date < CURDATE() 
            THEN DATEDIFF(CURDATE(), b.end_date)
            ELSE 0 
        END AS days_overdue
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN cars  c ON b.car_id  = c.id
    ORDER BY b.id DESC
");
?>
<!DOCTYPE html>
<html>
<head><title>Bookings</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="sidebar">
    <h2>DriveEase</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="cars.php">Cars</a>
    <a href="bookings.php" class="active">Bookings</a>
    <a href="users.php">Users</a>
    <a href="../auth/logout.php">Logout</a>
</div>
<div class="main">
    <h1>All Bookings</h1>

    <?php if ($bookings->num_rows == 0): ?>
    <div style="background:#fff;padding:30px;border-radius:10px;text-align:center;color:#6B3A2A;">
        <h3>No bookings yet!</h3>
    </div>
    <?php else: ?>
    <table>
        <tr>
            <th>User</th>
            <th>Car</th>
            <th>From</th>
            <th>To</th>
            <th>Total</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Overdue</th>
            <th>Action</th>
        </tr>
        <?php while ($b = $bookings->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($b['user_name']) ?></td>
            <td>
                <?= htmlspecialchars($b['car_name']) ?><br>
                <small style="color:#999;"><?= htmlspecialchars($b['brand']) ?></small>
            </td>
            <td><?= $b['start_date'] ?></td>
            <td><?= $b['end_date'] ?></td>
            <td><strong>$<?= number_format($b['total_price'], 2) ?></strong></td>
            <td><?= isset($b['payment_method']) ? ucfirst(str_replace('_', ' ', $b['payment_method'])) : '-' ?></td>

            <!-- STATUS -->
            <td>
                <?php
                $status = $b['status'];
                if ($status == 'paid')          echo "<span style='color:green;font-weight:bold;'>Paid</span>";
                elseif ($status == 'unpaid')    echo "<span style='color:orange;font-weight:bold;'>Unpaid (Cash)</span>";
                elseif ($status == 'cancelled') echo "<span style='color:red;font-weight:bold;'>Cancelled</span>";
                elseif ($status == 'rejected')  echo "<span style='color:red;font-weight:bold;'>Rejected</span>";
                elseif ($status == 'booked')    echo "<span style='color:blue;font-weight:bold;'>Booked</span>";
                elseif ($status == 'returned')  echo "<span style='color:#7B1F2E;font-weight:bold;'>Returned</span>";
                elseif ($status == 'overdue')   echo "<span style='color:red;font-weight:bold;'>Overdue</span>";
                else echo "<span style='color:gray;font-weight:bold;'>" . ucfirst($status) . "</span>";
                ?>
            </td>

            <!-- OVERDUE -->
            <td>
                <?php if ($b['days_overdue'] > 0): ?>
                    <?php
                    $total_days = max(1, (strtotime($b['end_date']) - strtotime($b['start_date'])) / 86400);
                    $daily_rate = $b['total_price'] / $total_days;
                    $overdue_charge = round($b['days_overdue'] * $daily_rate, 2);
                    ?>
                    <span style="color:red;font-weight:bold;">
                        <?= $b['days_overdue'] ?> day(s)<br>
                        <small>+$<?= number_format($overdue_charge, 2) ?></small>
                    </span>
                <?php elseif ($b['status'] == 'returned' && $b['overdue_days'] > 0): ?>
                    <span style="color:#999;">
                        Was <?= $b['overdue_days'] ?> day(s) late<br>
                        <small>Charged: $<?= number_format($b['overdue_charge'], 2) ?></small>
                    </span>
                <?php else: ?>
                    <span style="color:#ccc;">—</span>
                <?php endif; ?>
            </td>

            <!-- ACTION -->
            <td>
                <?php if ($b['status'] == 'unpaid'): ?>
                    <a href="mark_paid.php?id=<?= $b['id'] ?>" style="color:green;font-weight:bold;">Mark Paid</a> &nbsp;
                    <a href="cancel_booking.php?id=<?= $b['id'] ?>" onclick="return confirm('Cancel this booking?')" style="color:red;">Cancel</a>

                <?php elseif ($b['status'] == 'paid' || $b['status'] == 'booked'): ?>
                    <a href="mark_returned.php?id=<?= $b['id'] ?>" onclick="return confirm('Mark this car as returned?')" style="color:#7B1F2E;font-weight:bold;">Mark Returned</a> &nbsp;
                    <a href="cancel_booking.php?id=<?= $b['id'] ?>" onclick="return confirm('Cancel this booking?')" style="color:red;">Cancel</a>

                <?php elseif ($b['days_overdue'] > 0 && $b['status'] != 'returned'): ?>
                    <a href="mark_returned.php?id=<?= $b['id'] ?>" onclick="return confirm('Mark this overdue car as returned?')" style="color:orange;font-weight:bold;">⚠ Mark Returned</a>

                <?php elseif ($b['status'] == 'returned'): ?>
                    <span style="color:green;">✓ Done</span>

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
<?php
session_start();
if ($_SESSION['role'] != 'admin') { header("Location: ../auth/login.php"); exit; }
include '../config/database.php';

$id = intval($_GET['id']);

// Get booking details
$result = $conn->query("SELECT * FROM bookings WHERE id = $id");
$booking = $result->fetch_assoc();

if ($booking) {
    $today = date('Y-m-d');
    $end_date = $booking['end_date'];
    $overdue_days = max(0, (strtotime($today) - strtotime($end_date)) / 86400);
    
    // Calculate overdue charge (same daily rate)
    $total_days = max(1, (strtotime($end_date) - strtotime($booking['start_date'])) / 86400);
    $daily_rate = $booking['total_price'] / $total_days;
    $overdue_charge = round($overdue_days * $daily_rate, 2);

    // Update booking
    $stmt = $conn->prepare("
        UPDATE bookings 
        SET status = 'returned', 
            returned_at = ?, 
            overdue_days = ?, 
            overdue_charge = ?
        WHERE id = ?
    ");
    $stmt->bind_param("sddi", $today, $overdue_days, $overdue_charge, $id);
    $stmt->execute();

    // Set car back to available
    $conn->query("UPDATE cars SET status = 'available' WHERE id = {$booking['car_id']}");
}

header("Location: bookings.php");
exit;
?>
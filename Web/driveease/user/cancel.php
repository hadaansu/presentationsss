<?php
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: ../auth/login.php"); 
    exit; 
}
include '../config/database.php';

$id  = intval($_GET['id']);
$uid = $_SESSION['user_id'];

// Check booking belongs to this user and is cancellable
$check = $conn->query("SELECT * FROM bookings WHERE id=$id AND user_id=$uid");

if ($check->num_rows > 0) {
    $booking = $check->fetch_assoc();
    
    if ($booking['status'] == 'paid' || $booking['status'] == 'booked' || $booking['status'] == 'unpaid') {
        $conn->query("UPDATE bookings SET status='cancelled' WHERE id=$id AND user_id=$uid");
    }
}

header("Location: dashboard.php?cancelled=1");
exit;
?>
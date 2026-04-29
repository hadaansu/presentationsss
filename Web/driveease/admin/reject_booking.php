<?php
session_start();
if ($_SESSION['role'] != 'admin') { header("Location: ../auth/login.php"); exit; }
include '../config/database.php';

$id = $_GET['id'];
$conn->query("UPDATE bookings SET status='rejected' WHERE id=$id");
header("Location: bookings.php");
?>
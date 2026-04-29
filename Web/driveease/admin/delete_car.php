<?php
session_start();
if ($_SESSION['role'] != 'admin') { header("Location: ../auth/login.php"); exit; }
include '../config/database.php';

$id = $_GET['id'];
$conn->query("DELETE FROM cars WHERE id=$id");
header("Location: cars.php");
?>
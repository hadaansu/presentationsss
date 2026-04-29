<?php
session_start();
if ($_SESSION['role'] != 'admin') { header("Location: ../auth/login.php"); exit; }
include '../config/database.php';

$users = $conn->query("SELECT * FROM users WHERE role='user' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Users</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="sidebar">
    <h2>DriveEase</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="users.php" class="active">Users</a>
    <a href="../auth/logout.php">Logout</a>
</div>
<div class="main">
    <h1>All Users</h1>
    <table>
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Action</th></tr>
        <?php while ($u = $users->fetch_assoc()): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= $u['name'] ?></td>
            <td><?= $u['email'] ?></td>
            <td><a href="delete_user.php?id=<?= $u['id'] ?>" onclick="return confirm('Delete user?')">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
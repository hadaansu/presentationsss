<?php
session_start();
include '../config/database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Email already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            header("Location: login.php?registered=1");
            exit;
        } else {
            $error = "Registration failed. Try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>DriveEase - Register</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
    body { background:#F0E6DC; display:flex; align-items:center; justify-content:center; min-height:100vh; margin:0; }
    .login-wrap { display:grid; grid-template-columns:1fr 1fr; width:820px; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 8px 32px rgba(0,0,0,0.12); }
    .login-left { background:linear-gradient(135deg,#5C1520,#7B1F2E); padding:50px 40px; display:flex; flex-direction:column; justify-content:center; }
    .login-left h1 { color:#C4933A; font-size:32px; margin-bottom:10px; }
    .login-left p  { color:#f0c9ad; font-size:15px; line-height:1.7; margin-bottom:30px; }
    .login-left ul { list-style:none; padding:0; }
    .login-left ul li { color:#fff; font-size:14px; padding:6px 0; }
    .login-left ul li::before { content:"✓ "; color:#C4933A; font-weight:bold; }
    .login-right { padding:50px 40px; }
    .login-right h2 { color:#5C1520; font-size:24px; margin-bottom:6px; }
    .login-right p  { color:#999; font-size:14px; margin-bottom:24px; }
    .form-group { margin-bottom:16px; }
    .form-group label { display:block; font-size:13px; color:#6B3A2A; margin-bottom:6px; font-weight:bold; }
    .form-group input { width:100%; padding:11px 14px; border:1px solid #d0bfb4; border-radius:8px; font-size:14px; outline:none; box-sizing:border-box; }
    .form-group input:focus { border-color:#7B1F2E; }
    .btn-login { width:100%; padding:12px; background:#7B1F2E; color:#fff; border:none; border-radius:8px; font-size:15px; cursor:pointer; margin-top:6px; }
    .btn-login:hover { background:#5C1520; }
    .error { background:#fdecea; color:#c0392b; padding:10px 14px; border-radius:8px; font-size:13px; margin-bottom:16px; }
    .register-link { text-align:center; margin-top:20px; font-size:13px; color:#999; }
    .register-link a { color:#7B1F2E; font-weight:bold; }
</style>
</head>
<body>
<div class="login-wrap">
    <div class="login-left">
        <h1>DriveEase</h1>
        <p>Car Rental System</p>
        <ul>
            <li>Browse Available Cars</li>
            <li>Book Instantly</li>
            <li>Track Your Bookings</li>
            <li>Easy Payment</li>
        </ul>
    </div>
    <div class="login-right">
        <h2>Create Account</h2>
        <p>Register to start booking cars</p>
        <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="Enter your full name" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Create a password" required>
            </div>
            <button type="submit" class="btn-login">Create Account →</button>
        </form>
        <div class="register-link">
            Already have an account? <a href="login.php">Sign in here</a>
        </div>
    </div>
</div>
</body>
</html>
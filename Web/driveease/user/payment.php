<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
include '../config/database.php';

$booking_id = $_GET['booking_id'];
$total      = $_GET['total'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $method = $_POST['payment_method'];

    // Cash = unpaid, Card/eSewa/Khalti = paid
    if ($method == 'cash') {
        $status = 'unpaid';
    } else {
        $status = 'paid';
    }

    $conn->query("UPDATE bookings SET status='$status', payment_method='$method' WHERE id=$booking_id");

    header("Location: dashboard.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Payment</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="sidebar">
    <h2>DriveEase</h2>
    <a href="../cars.php">Browse Cars</a>
    <a href="dashboard.php">My Bookings</a>
    <a href="../auth/logout.php">Logout</a>
</div>
<div class="main">
    <h1>Payment</h1>
    <div style="background:#fff;border-radius:12px;padding:30px;max-width:500px;border:1px solid #e0d4cc;">
        <h2 style="color:#7B1F2E;margin-bottom:20px;">Total Amount: <span style="font-size:28px;">$<?= number_format($total, 2) ?></span></h2>

        <form method="POST" class="form-box">
            <label>Select Payment Method</label>
            <select name="payment_method" required>
                <option value="">-- Choose --</option>
                <option value="credit_card">Credit Card</option>
                <option value="debit_card">Debit Card</option>
                <option value="cash">Cash on Pickup</option>
                <option value="esewa">eSewa</option>
                <option value="khalti">Khalti</option>
            </select>

            <div id="card-fields" style="display:none;">
                <label>Card Number</label>
                <input type="text" placeholder="1234 5678 9012 3456" maxlength="19">
                <label>Expiry Date</label>
                <input type="text" placeholder="MM/YY" maxlength="5">
                <label>CVV</label>
                <input type="text" placeholder="123" maxlength="3">
            </div>

            <br>
            <button type="submit" class="btn" style="width:100%;padding:14px;font-size:16px;">
                Confirm Payment ✓
            </button>
        </form>
    </div>
</div>
<script>
document.querySelector('select[name="payment_method"]').addEventListener('change', function() {
    const cardFields = document.getElementById('card-fields');
    if (this.value === 'credit_card' || this.value === 'debit_card') {
        cardFields.style.display = 'block';
    } else {
        cardFields.style.display = 'none';
    }
});
</script>
</body>
</html>
<?php
include 'config/database.php';

// Check connection
echo "Connected to DB: " . $conn->host_info . "<br><br>";

// Show ALL users
$result = $conn->query("SELECT id, name, email, role FROM users");
echo "Total users found: " . $result->num_rows . "<br><br>";

while ($row = $result->fetch_assoc()) {
    echo "ID: " . $row['id'] . " | Name: " . $row['name'] . " | Email: [" . $row['email'] . "] | Role: " . $row['role'] . "<br>";
}
?>
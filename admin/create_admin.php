<?php
require "../config/db.php";

$name = "Admin Name";
$email = "admin@example.com";
$password = password_hash("123456", PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $password);
$stmt->execute();

echo "Admin created!";
?>

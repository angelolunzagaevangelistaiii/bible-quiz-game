<?php
session_start();
require "../config/db.php";
require "auth_check.php";

// Filters
$category = $_GET['category'] ?? '';
$difficulty = $_GET['difficulty'] ?? '';

// Base query
$sql = "SELECT * FROM leaderboard WHERE 1=1";
$params = [];
$types = "";

// Add filters
if ($category) {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

if ($difficulty) {
    $sql .= " AND difficulty = ?";
    $params[] = $difficulty;
    $types .= "s";
}

$sql .= " ORDER BY score DESC, date_taken DESC";

$stmt = $conn->prepare($sql);
if (count($params) > 0) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Leaderboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "

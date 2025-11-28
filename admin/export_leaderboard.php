<?php
session_start();
require "../config/db.php";
require "auth_check.php";

$category = $_GET['category'] ?? '';
$difficulty = $_GET['difficulty'] ?? '';

$sql = "SELECT name,email,score,category,difficulty,date_taken FROM leaderboard WHERE 1=1";
$params = [];
$types = "";

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

$stmt = $conn->prepare($sql);
if (count($params) > 0) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// CSV headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="leaderboard.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Name','Email','Score','Category','Difficulty','Date Taken']);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['name'],$row['email'],$row['score'],$row['category'],$row['difficulty'],$row['date_taken']]);
}
fclose($output);
exit;

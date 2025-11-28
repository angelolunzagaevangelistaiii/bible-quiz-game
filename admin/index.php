<?php
require_once "admin_protect.php"; // session check
require_once "../config/config.php";

$admin_name = $_SESSION['admin_name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
<div class="container">
    <h2>Welcome, <?= htmlspecialchars($admin_name); ?></h2>
    <ul>
        <li><a href="add_question.php">Add Question</a></li>
        <li><a href="manage_questions.php">Manage Questions</a></li>
        <li><a href="leaderboard_admin.php">View Leaderboard</a></li>
        <li><a href="admin_logout.php">Logout</a></li>
    </ul>
</div>
</body>
</html>

<?php
session_start();
require_once __DIR__ . '/admin_protect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
<div class="container">
    <h2>Admin Dashboard</h2>

    <p>Welcome, <?= $_SESSION['admin_username'] ?>!</p>

    <ul>
        <li><a href="add_question.php">Add Question</a></li>
        <li><a href="edit_question.php">Edit Question</a></li>
        <li><a href="delete_question.php">Delete Question</a></li>
        <li><a href="questions_list.php">View All Questions</a></li>
        <li><a href="../leaderboard.php">View Leaderboard</a></li>
        <li><a href="admin_logout.php">Logout</a></li>
    </ul>
</div>
</body>
</html>

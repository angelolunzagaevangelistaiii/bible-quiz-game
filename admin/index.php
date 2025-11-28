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

<p>Welcome, <strong><?= $_SESSION['admin_name'] ?></strong> (<?= $_SESSION['admin_email'] ?>)</p>

<ul class="admin-menu">
    <li><a href="add_question.php">Add Question</a></li>
    <li><a href="questions_list.php">Manage All Questions</a></li>
    <li><a href="../leaderboard.php">Leaderboard</a></li>
    <li><a href="admin_logout.php">Logout</a></li>
</ul>

</div>
</body>
</html>

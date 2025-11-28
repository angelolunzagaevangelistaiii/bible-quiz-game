<?php
session_start();
require "../config/db.php";
require "auth_check.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="sidebar">
    <h2><?= $_SESSION['admin_name'] ?></h2>
    <a href="index.php">Dashboard</a>
    <a href="add_question.php">Add Question</a>
    <a href="manage_questions.php">Manage Questions</a>
    <a href="admin_logout.php">Logout</a>
</div>

<div class="topbar">Christian Quiz Admin</div>

<div class="content">
    <h1>Welcome, <?= $_SESSION['admin_name'] ?></h1>
    <p>You are logged in as <strong><?= $_SESSION['admin_email'] ?></strong></p>
</div>
</body>
</html>

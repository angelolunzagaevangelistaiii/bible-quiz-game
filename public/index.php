<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// User info from session
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Christian Quiz</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Welcome, <?= htmlspecialchars($user_name); ?>!</h2>
    <p>Email: <?= htmlspecialchars($user_email); ?></p>

    <div style="margin-top:20px;">
        <a href="quiz.php" class="btn">Take Quiz</a>
        <a href="quiz_history.php" class="btn">My Quiz History</a>
        <a href="leaderboard.php" class="btn">Leaderboard</a>
        <a href="logout.php" class="btn">Logout</a>
    </div>
</div>
</body>
</html>

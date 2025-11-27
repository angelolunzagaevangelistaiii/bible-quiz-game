<?php
session_start();
require_once __DIR__ . '/src/functions.php';
requireLogin();

$score = $_SESSION['quiz_score'] ?? 0;
unset($_SESSION['quiz_score']);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Quiz Result</title>
<link rel="stylesheet" href="public/style.css">
</head>
<body>
<div class="container">
    <h2>Quiz Result</h2>
    <p>Your Score: <strong><?= $score ?></strong> out of 5</p>
    <p><a href="quiz.php">Take Quiz Again</a> | <a href="public/index.php">Back to Dashboard</a></p>
</div>
</body>
</html>

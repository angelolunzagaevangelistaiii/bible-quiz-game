<?php
session_start();
require_once __DIR__ . '/../src/functions.php';
$score = $_SESSION['quiz_score'] ?? null;
$total = $_SESSION['quiz_total'] ?? null;
$name = $_SESSION['user_name'] ?? 'Guest';
$email = $_SESSION['user_email'] ?? '';
// clear stored attempt info if you want
unset($_SESSION['quiz_score'], $_SESSION['quiz_total'], $_SESSION['quiz_category'], $_SESSION['quiz_difficulty'], $_SESSION['quiz_n']);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Quiz Result</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h2>Quiz Result</h2>

  <?php if ($score === null): ?>
    <p>No quiz result found. <a href="index.php">Start quiz</a>.</p>
  <?php else: ?>
    <p><strong><?= esc($name) ?></strong> (<?= esc($email) ?>)</p>
    <p>Your score: <strong><?= intval($score) ?></strong> out of <?= intval($total) ?></p>
    <p class="small">Your result was saved to the leaderboard.</p>
    <p><a class="link" href="leaderboard.php">View Leaderboard</a> | <a class="link" href="index.php">Take Again</a></p>
  <?php endif; ?>
</div>
</body>
</html>

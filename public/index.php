<?php
session_start();
require_once __DIR__ . '/../src/functions.php';

// Require user to be logged in
requireLogin();

$name  = $_SESSION['user_name'];
$email = $_SESSION['user_email'];
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Christian Bible Quiz - Start</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

  <h1>Christian Bible Quiz</h1>
  <p class="small">Welcome, <strong><?= esc($name) ?></strong> (<?= esc($email) ?>)</p>

  <form action="quiz.php" method="get">

    <label>Category</label>
    <select name="category" required>
      <option value="Faith">Faith</option>
      <option value="Gospels">Gospels</option>
      <option value="Prophecy">Prophecy</option>
      <option value="Wisdom">Wisdom</option>
      <option value="Prayer">Prayer</option>
      <option value="End Times">End Times</option>
    </select>

    <label>Difficulty</label>
    <select name="difficulty" required>
      <option value="Easy">Easy</option>
      <option value="Medium">Medium</option>
      <option value="Hard">Hard</option>
    </select>

    <label>Number of Questions</label>
    <select name="n">
      <option value="5">5</option>
      <option value="10" selected>10</option>
      <option value="15">15</option>
      <option value="20">20</option>
    </select>

    <button type="submit" style="margin-top:15px;">Start Quiz</button>
    <a class="link" href="leaderboard.php" style="margin-left:12px;">Leaderboard</a>
  </form>

</div>
</body>
</html>

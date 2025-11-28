<?php
// public/index.php - User landing page to enter name/email and pick category/difficulty
session_start();
require_once __DIR__ . '/../src/functions.php';

// If user previously set name/email in session, prefill
$prefName = $_SESSION['user_name'] ?? '';
$prefEmail = $_SESSION['user_email'] ?? '';

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
  <p class="small">Enter your details, choose a category and difficulty, then start the quiz.</p>

  <form action="quiz.php" method="get">
    <label>Your name</label>
    <input type="text" name="name" value="<?= esc($prefName) ?>" required>

    <label>Your email</label>
    <input type="email" name="email" value="<?= esc($prefEmail) ?>" required>

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

    <label>Number of questions</label>
    <select name="n">
      <option value="5">5</option>
      <option value="10" selected>10</option>
    </select>

    <div style="margin-top:12px;">
      <button type="submit">Start Quiz</button>
      <a class="link" href="leaderboard.php" style="margin-left:12px;">View Leaderboard</a>
    </div>
  </form>
</div>
</body>
</html>

<?php
session_start();
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
if (!isset($_SESSION['user_id'])) header("Location: login.php");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Bible Quiz - Dashboard</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
  <h1>Bible Quiz Game</h1>
  <p>Welcome, <?php echo esc($_SESSION['name']); ?> | <a href="logout.php">Logout</a></p>
  <hr>
  <h3>Start a Quiz</h3>
  <form id="start-form" method="get" action="quiz.php">
    <label>Number of questions:
      <select name="n">
        <option value="5">5</option>
        <option value="10" selected>10</option>
        <option value="15">15</option>
      </select>
    </label>
    <label>Time per question (seconds):
      <select name="t">
        <option value="15">15</option>
        <option value="20" selected>20</option>
        <option value="30">30</option>
      </select>
    </label>
    <button type="submit">Start Quiz</button>
  </form>

  <hr>
  <p><a href="leaderboard.php">View Leaderboard</a></p>
  <?php if (isAdmin()): ?>
    <p><a href="../admin/index.php">Admin: Manage Questions</a></p>
<?php endif; ?>

</div>
</body>
</html>

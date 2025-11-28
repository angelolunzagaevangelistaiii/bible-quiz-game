<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';

// fetch top results; show latest top 100 by score then date
$stmt = $mysqli->prepare("SELECT name, email, score, category, difficulty, created_at FROM leaderboard ORDER BY score DESC, created_at ASC LIMIT 100");
$stmt->execute();
$res = $stmt->get_result();
$rows = $res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Leaderboard</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h1>Leaderboard</h1>
  <table class="table">
    <thead>
      <tr><th>#</th><th>Name</th><th>Email</th><th>Score</th><th>Category</th><th>Difficulty</th><th>Date</th></tr>
    </thead>
    <tbody>
      <?php if (count($rows)===0): ?>
        <tr><td colspan="7">No results yet.</td></tr>
      <?php else: ?>
        <?php $i=1; foreach($rows as $r): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= esc($r['name']) ?></td>
            <td><?= esc($r['email']) ?></td>
            <td><?= intval($r['score']) ?></td>
            <td><?= esc($r['category']) ?></td>
            <td><?= esc($r['difficulty']) ?></td>
            <td><?= esc($r['created_at']) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>

  <p style="margin-top:12px;"><a class="link" href="index.php">Back to Quiz</a></p>
</div>
</body>
</html>

<?php
session_start();
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/functions.php';
requireLogin();

// Calculate leaderboard from user_quiz_results
$sql = "SELECT u.username, SUM(r.is_correct) AS score
        FROM users u
        JOIN user_quiz_results r ON u.id = r.user_id
        GROUP BY u.id
        ORDER BY score DESC
        LIMIT 10";
$res = $mysqli->query($sql);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Leaderboard</title>
<link rel="stylesheet" href="public/style.css">
</head>
<body>
<div class="container">
    <h2>Leaderboard</h2>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>User</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
        <?php $rank = 1; while($row = $res->fetch_assoc()): ?>
            <tr>
                <td><?= $rank++ ?></td>
                <td><?= esc($row['username']) ?></td>
                <td><?= $row['score'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <p><a href="public/index.php">Back to Dashboard</a></p>
</div>
</body>
</html>

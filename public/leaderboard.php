<?php
session_start();
require_once "../config/config.php";

// Fetch all results
$result = $conn->query("
    SELECT name, email, score, category, difficulty, date_taken 
    FROM leaderboard 
    ORDER BY score DESC, date_taken DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Leaderboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="quiz-container">
    <h2>Leaderboard</h2>

    <table class="table">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Score</th>
            <th>Category</th>
            <th>Difficulty</th>
            <th>Date</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']); ?></td>
            <td><?= htmlspecialchars($row['email']); ?></td>
            <td><?= intval($row['score']); ?></td>
            <td><?= htmlspecialchars($row['category']); ?></td>
            <td><?= htmlspecialchars($row['difficulty']); ?></td>
            <td><?= $row['date_taken']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="index.php" class="btn">Back to Quiz Menu</a>
</div>

</body>
</html>

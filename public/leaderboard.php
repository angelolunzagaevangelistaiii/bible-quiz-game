<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
require_once "../config/config.php"; // Database connection

// Fetch all quiz results
$query = "SELECT name, email, score, category, difficulty, date_taken
          FROM leaderboard
          ORDER BY score DESC, date_taken DESC";

$result = $conn->query($query);

if (!$result) {
    die("Database query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Leaderboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Leaderboard</h2>
    <a href="index.php" class="btn">Back to Quiz Menu</a>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Score</th>
                <th>Category</th>
                <th>Difficulty</th>
                <th>Date Taken</th>
            </tr>
        </thead>
        <tbody>
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
        </tbody>
    </table>
</div>

</body>
</html>

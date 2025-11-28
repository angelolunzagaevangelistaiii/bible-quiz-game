<?php
session_start();
require_once "../config/config.php";
require_once "../src/functions.php";

// Make sure user is logged in
requireLogin();

$user_id = $_SESSION['user_id'];

// Fetch user's past quiz results
$stmt = $conn->prepare("
    SELECT score, category, difficulty, date_taken
    FROM leaderboard
    WHERE user_id = ?
    ORDER BY date_taken DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Quiz History</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>My Quiz History</h2>
    <p>Welcome, <strong><?= htmlspecialchars($_SESSION['user_name']); ?></strong></p>

    <?php if ($result->num_rows == 0): ?>
        <p>You have not taken any quizzes yet.</p>
        <a href="index.php" class="btn">Take a Quiz</a>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Difficulty</th>
                    <th>Score</th>
                    <th>Date Taken</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['category']); ?></td>
                    <td><?= htmlspecialchars($row['difficulty']); ?></td>
                    <td><?= intval($row['score']); ?></td>
                    <td><?= $row['date_taken']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn">Take Another Quiz</a>
    <?php endif; ?>

</div>

</body>
</html>

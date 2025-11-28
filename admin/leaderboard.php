<?php
require_once "admin_protect.php";
require_once "../config/config.php";

// Fetch leaderboard: count correct answers per user
$sql = "SELECT user_name, user_email, COUNT(*) AS total_correct
        FROM user_quiz_results
        WHERE is_correct = 1
        GROUP BY user_id
        ORDER BY total_correct DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Leaderboard</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
<div class="container">
    <h2>Leaderboard</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Total Correct Answers</th>
        </tr>
        <?php $i = 1; while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $i ?></td>
            <td><?= htmlspecialchars($row['user_name']) ?></td>
            <td><?= htmlspecialchars($row['user_email']) ?></td>
            <td><?= $row['total_correct'] ?></td>
        </tr>
        <?php $i++; endwhile; ?>
    </table>
    <p><a href="index.php">Back to Dashboard</a></p>
</div>
</body>
</html>

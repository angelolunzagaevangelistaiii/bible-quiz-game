<?php
// Enable error reporting for debugging (remove on production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Include database connection
require_once "../config/config.php"; // Make sure path is correct

// Check database connection
if (!$conn) {
    die("Database connection failed.");
}

// Fetch all quiz results from leaderboard table
$sql = "SELECT name, email, score, category, difficulty, date_taken
        FROM leaderboard
        ORDER BY score DESC, date_taken DESC";

$result = $conn->query($sql);

// Check query
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Leaderboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 0; padding: 0; }
        .container { width: 90%; max-width: 1000px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        table th { background-color: #4CAF50; color: white; }
        tr:hover { background-color: #f1f1f1; }
        .btn { display: inline-block; padding: 8px 12px; margin: 10px 0; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; }
        .btn:hover { background-color: #45a049; }
    </style>
</head>
<body>

<div class="container">
    <h2>Quiz Leaderboard</h2>

    <a href="index.php" class="btn">Back to Quiz Menu</a>

    <?php if ($result->num_rows > 0): ?>
        <table>
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
    <?php else: ?>
        <p>No quiz results found yet.</p>
    <?php endif; ?>
</div>

</body>
</html>

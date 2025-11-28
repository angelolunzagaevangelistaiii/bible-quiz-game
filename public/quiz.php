<?php
session_start();
require_once "../config/config.php";

// Ensure user logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$email = $_SESSION['email'];

// Validate request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid access.");
}

$score = intval($_POST['score']);
$category = $_POST['category'];
$difficulty = $_POST['difficulty'];

// Insert result into leaderboard
$stmt = $conn->prepare("INSERT INTO leaderboard (user_id, name, email, score, category, difficulty, date_taken) 
                        VALUES (?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("ississ", $user_id, $name, $email, $score, $category, $difficulty);
$stmt->execute();
$stmt->close();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Quiz Result</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="quiz-container">
    <h2>Your Result</h2>

    <p><strong>Name:</strong> <?= htmlspecialchars($name); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($email); ?></p>
    <p><strong>Category:</strong> <?= htmlspecialchars($category); ?></p>
    <p><strong>Difficulty:</strong> <?= htmlspecialchars($difficulty); ?></p>
    <p><strong>Your Score:</strong> <?= $score; ?></p>

    <a href="index.php" class="btn">Take Another Quiz</a>
    <a href="leaderboard.php" class="btn">View Leaderboard</a>
</div>

</body>
</html>

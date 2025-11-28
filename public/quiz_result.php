<?php
session_start();

// Ensure user is logged in and has a quiz result
if (!isset($_SESSION['user_id']) || !isset($_SESSION['last_quiz'])) {
    // Redirect to quiz page if accessed directly
    header("Location: quiz.php");
    exit;
}

// Get last quiz result from session
$quiz = $_SESSION['last_quiz'];

// Optional: clear the last quiz session so user can't reload result
unset($_SESSION['last_quiz']);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Result</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Quiz Result</h2>

    <p><strong>Question:</strong> <?= htmlspecialchars($quiz['question']); ?></p>
    <p><strong>Your Answer:</strong> <?= htmlspecialchars($quiz['selected']); ?></p>
    <p><strong>Correct Answer:</strong> <?= htmlspecialchars($quiz['correct']); ?></p>
    <p><strong>Category:</strong> <?= htmlspecialchars($quiz['category']); ?></p>
    <p><strong>Difficulty:</strong> <?= htmlspecialchars($quiz['difficulty']); ?></p>
    <p><strong>Score:</strong> <?= $quiz['score']; ?></p>

    <div style="margin-top:20px;">
        <a href="quiz.php" class="btn">Take Another Quiz</a>
        <a href="index.php" class="btn">Back to Menu</a>
    </div>
</div>
</body>
</html>

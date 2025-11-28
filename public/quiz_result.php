<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['last_quiz'])) {
    header("Location: quiz.php");
    exit;
}

$quiz = $_SESSION['last_quiz'];
unset($_SESSION['last_quiz']); // prevent reload

$is_correct = ($quiz['selected'] === $quiz['correct']);
$score_display = $is_correct ? 1 : 0;
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
    <p><strong>Your Answer:</strong> <?= htmlspecialchars($quiz['selected']); ?> - 
        <?= htmlspecialchars($quiz['option_' . strtolower($quiz['selected'])] ?? ''); ?></p>
    <p><strong>Correct Answer:</strong> <?= htmlspecialchars($quiz['correct']); ?> - <?= htmlspecialchars($quiz['correct_text']); ?></p>

    <p><strong>Category:</strong> <?= htmlspecialchars($quiz['category']); ?></p>
    <p><strong>Difficulty:</strong> <?= htmlspecialchars($quiz['difficulty']); ?></p>
    <p><strong>Score:</strong> <?= $score_display; ?></p>

    <div style="margin-top:20px;">
        <a href="quiz.php" class="btn">Take Another Quiz</a>
        <a href="index.php" class="btn">Back to Menu</a>
    </div>
</div>
</body>
</html>

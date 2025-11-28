<?php
session_start();

// Check login & quiz session
if (!isset($_SESSION['user_id']) || !isset($_SESSION['last_quiz'])) {
    header("Location: quiz.php");
    exit;
}

// Get quiz data
$quiz = $_SESSION['last_quiz'];
unset($_SESSION['last_quiz']); // prevent reload

$is_correct = ($quiz['selected'] === $quiz['correct']);
$score_display = $is_correct ? 1 : 0;

// Function to get option text
function getOptionText($quiz, $option)
{
    switch ($option) {
        case 'A': return $quiz['option_a'] ?? '';
        case 'B': return $quiz['option_b'] ?? '';
        case 'C': return $quiz['option_c'] ?? '';
        case 'D': return $quiz['option_d'] ?? '';
        default: return '';
    }
}
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
    <p><strong>Your Answer:</strong> <?= htmlspecialchars($quiz['selected']) ?> - <?= htmlspecialchars(getOptionText($quiz, $quiz['selected'])); ?></p>
    <p><strong>Correct Answer:</strong> <?= htmlspecialchars($quiz['correct']) ?> - <?= htmlspecialchars(getOptionText($quiz, $quiz['correct'])); ?></p>

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

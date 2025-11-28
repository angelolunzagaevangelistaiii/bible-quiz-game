<?php
session_start();
require_once "../config/config.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// User info
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

// Fetch a random question (you can later add category/difficulty filters)
$query = "SELECT * FROM questions ORDER BY RAND() LIMIT 1";
$result = $conn->query($query);

if (!$result || $result->num_rows == 0) {
    die("No questions available. Please ask admin to add questions.");
}

$question = $result->fetch_assoc();

// If form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected = $_POST['answer'] ?? '';
    $score = ($selected === $question['correct_answer']) ? 1 : 0;

    // Save to leaderboard
    $stmt = $conn->prepare("INSERT INTO leaderboard (user_id, name, email, score, category, difficulty) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ississ",
        $user_id,
        $user_name,
        $user_email,
        $score,
        $question['category'],
        $question['difficulty']
    );
    $stmt->execute();
    $stmt->close();

    header("Location: quiz_result.php?score=$score");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Take Quiz</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Quiz Question</h2>

    <form method="POST">
        <p><strong><?= htmlspecialchars($question['question']); ?></strong></p>

        <input type="radio" name="answer" value="A" required> <?= htmlspecialchars($question['option_a']); ?><br>
        <input type="radio" name="answer" value="B"> <?= htmlspecialchars($question['option_b']); ?><br>
        <input type="radio" name="answer" value="C"> <?= htmlspecialchars($question['option_c']); ?><br>
        <input type="radio" name="answer" value="D"> <?= htmlspecialchars($question['option_d']); ?><br><br>

        <button type="submit">Submit Answer</button>
    </form>

    <a href="index.php" class="btn">Back to Menu</a>
</div>
</body>
</html>

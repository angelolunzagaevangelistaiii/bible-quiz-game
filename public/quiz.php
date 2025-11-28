<?php
session_start();
require_once "../config/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

$error = '';
$question = null;

// Fetch distinct categories and difficulties for dropdown
$cat_result = $conn->query("SELECT DISTINCT category FROM questions");
$categories = [];
while ($row = $cat_result->fetch_assoc()) $categories[] = $row['category'];

$diff_result = $conn->query("SELECT DISTINCT difficulty FROM questions");
$difficulties = [];
while ($row = $diff_result->fetch_assoc()) $difficulties[] = $row['difficulty'];

// Stage 2: Submit answer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'])) {
    $selected = $_POST['answer'];
    $score = ($selected === $_POST['correct_answer']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO leaderboard (user_id, name, email, score, category, difficulty) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ississ",
        $user_id,
        $user_name,
        $user_email,
        $score,
        $_POST['category'],
        $_POST['difficulty']
    );
    $stmt->execute();
    $stmt->close();

    header("Location: quiz_result.php?score=$score");
    exit;
}

// Stage 1: Fetch question after category/difficulty selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fetch_question'])) {
    $category = $_POST['category'];
    $difficulty = $_POST['difficulty'];

    $stmt = $conn->prepare("SELECT * FROM questions WHERE category=? AND difficulty=? ORDER BY RAND() LIMIT 1");
    $stmt->bind_param("ss", $category, $difficulty);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $question = $result->fetch_assoc();
    } else {
        $error = "No questions found for this category and difficulty.";
    }
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
    <h2>Quiz</h2>

    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

    <?php if (!$question): ?>
        <!-- Stage 1: Category/Difficulty Form -->
        <form method="POST">
            <label>Category:</label>
            <select name="category" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat) echo "<option value=\"$cat\">$cat</option>"; ?>
            </select>

            <label>Difficulty:</label>
            <select name="difficulty" required>
                <option value="">Select Difficulty</option>
                <?php foreach ($difficulties as $diff) echo "<option value=\"$diff\">$diff</option>"; ?>
            </select>

            <button type="submit" name="fetch_question">Start Quiz</button>
        </form>
    <?php else: ?>
        <!-- Stage 2: Show Question Form -->
        <form method="POST">
            <p><strong><?= htmlspecialchars($question['question']); ?></strong></p>

            <input type="hidden" name="category" value="<?= htmlspecialchars($question['category']); ?>">
            <input type="hidden" name="difficulty" value="<?= htmlspecialchars($question['difficulty']); ?>">
            <input type="hidden" name="correct_answer" value="<?= htmlspecialchars($question['correct_answer']); ?>">

            <input type="radio" name="answer" value="A" required> <?= htmlspecialchars($question['option_a']); ?><br>
            <input type="radio" name="answer" value="B"> <?= htmlspecialchars($question['option_b']); ?><br>
            <input type="radio" name="answer" value="C"> <?= htmlspecialchars($question['option_c']); ?><br>
            <input type="radio" name="answer" value="D"> <?= htmlspecialchars($question['option_d']); ?><br><br>

            <button type="submit">Submit Answer</button>
        </form>
    <?php endif; ?>

    <a href="index.php" class="btn">Back to Menu</a>
</div>
</body>
</html>

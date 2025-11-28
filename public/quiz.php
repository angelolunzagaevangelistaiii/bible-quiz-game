<?php
session_start();
require_once "../config/config.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

$error = '';
$question = null;

// Fetch categories and difficulties
$categories = [];
$cat_result = $conn->query("SELECT DISTINCT category FROM questions");
if ($cat_result) {
    while ($row = $cat_result->fetch_assoc()) $categories[] = $row['category'];
}

$difficulties = [];
$diff_result = $conn->query("SELECT DISTINCT difficulty FROM questions");
if ($diff_result) {
    while ($row = $diff_result->fetch_assoc()) $difficulties[] = $row['difficulty'];
}

// Stage 2: Answer submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'])) {
    $selected = $_POST['answer'];
    $correct_answer = $_POST['correct_answer'];
    $question_text = $_POST['question_text'];
    $category = $_POST['category'];
    $difficulty = $_POST['difficulty'];

    // Store score
    $score = ($selected === $correct_answer) ? 1 : 0;

    // Save to leaderboard
    $stmt = $conn->prepare("INSERT INTO leaderboard (user_id, name, email, score, category, difficulty) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississ", $user_id, $user_name, $user_email, $score, $category, $difficulty);
    $stmt->execute();
    $stmt->close();

    // Store all data in session for quiz_result.php
    $_SESSION['last_quiz'] = [
    'question' => $question_text,
    'selected' => $selected,
    'correct' => $correct_answer, // letter e.g., A/B/C/D
    'correct_text' => $question['option_' . strtolower($correct_answer)], // text of correct answer
    'option_a' => $question['option_a'],
    'option_b' => $question['option_b'],
    'option_c' => $question['option_c'],
    'option_d' => $question['option_d'],
    'category' => $category,
    'difficulty' => $difficulty,
    'score' => $score
    ];

    header("Location: quiz_result.php");
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
        <!-- Stage 1: Category/Difficulty Selection -->
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

            <!-- Hidden fields for quiz result -->
            <input type="hidden" name="question_text" value="<?= htmlspecialchars($question['question']); ?>">
            <input type="hidden" name="category" value="<?= htmlspecialchars($question['category']); ?>">
            <input type="hidden" name="difficulty" value="<?= htmlspecialchars($question['difficulty']); ?>">
            <input type="hidden" name="correct_answer" value="<?= htmlspecialchars($question['correct_answer']); ?>">
            <input type="hidden" name="option_a" value="<?= htmlspecialchars($question['option_a']); ?>">
            <input type="hidden" name="option_b" value="<?= htmlspecialchars($question['option_b']); ?>">
            <input type="hidden" name="option_c" value="<?= htmlspecialchars($question['option_c']); ?>">
            <input type="hidden" name="option_d" value="<?= htmlspecialchars($question['option_d']); ?>">

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

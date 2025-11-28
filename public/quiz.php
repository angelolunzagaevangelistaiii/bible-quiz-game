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
    $correct_answer = $_POST['correct_answer'];
    $category = $_POST['category'];
    $difficulty = $_POST['difficulty'];
    $question_text = $_POST['question_text'];

    $score = ($selected === $correct_answer) ? 1 : 0;

    // Save to leaderboard
    $stmt = $conn->prepare("INSERT INTO leaderboard (user_id, name, email, score, category, difficulty) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ississ",
        $user_id,
        $user_name,
        $user_email,
        $score,
        $category,
        $difficulty
    );
    $stmt->execute();
    $stmt->close();

    // Store quiz result info in session
    $_SESSION['last_quiz'] = [
        'question' => $question_text,
        'selected' => $selected,
        'correct' => $correct_answer,
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

    <?php if

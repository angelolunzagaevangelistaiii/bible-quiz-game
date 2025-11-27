<?php
session_start();
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/functions.php';
requireLogin();

// Handle category & difficulty filters
$category_filter = $_GET['category'] ?? '';
$difficulty_filter = $_GET['difficulty'] ?? '';

// Build WHERE clause
$where = [];
if($category_filter) $where[] = "category='".$mysqli->real_escape_string($category_filter)."'";
if($difficulty_filter) $where[] = "difficulty='".$mysqli->real_escape_string($difficulty_filter)."'";

$sql = "SELECT * FROM questions";
if(count($where)) $sql .= " WHERE ".implode(' AND ', $where);
$sql .= " ORDER BY RAND() LIMIT 5"; // 5 random questions

$questions_res = $mysqli->query($sql);

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;

    foreach($_POST['answers'] as $qid => $answer){
        $qid = intval($qid);
        $res = $mysqli->query("SELECT correct FROM questions WHERE id=$qid");
        $correct = $res->fetch_assoc()['correct'];
        $is_correct = ($answer === $correct) ? 1 : 0;
        if($is_correct) $score++;

        // Save user answer for leaderboard
        $stmt = $mysqli->prepare("INSERT INTO user_quiz_results (user_id, question_id, user_answer, is_correct) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisi", $_SESSION['user_id'], $qid, $answer, $is_correct);
        $stmt->execute();
    }

    // Save score to leaderboard
    $stmt = $mysqli->prepare("INSERT INTO leaderboard (user_id, score) VALUES (?, ?)");
    $stmt->bind_param("ii", $_SESSION['user_id'], $score);
    $stmt->execute();

    $_SESSION['quiz_score'] = $score;
    header("Location: quiz_result.php");
    exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Bible Quiz</title>
<link rel="stylesheet" href="public/style.css">
</head>
<body>
<div class="container">
    <h2>Bible Quiz</h2>

    <!-- Filter form -->
    <form method="get" class="filter-form">
        <label>Category:</label>
        <select name="category">
            <option value="">All</option>
            <option value="Faith" <?= $category_filter=='Faith'?'selected':'' ?>>Faith</option>
            <option value="Gospels" <?= $category_filter=='Gospels'?'selected':'' ?>>Gospels</option>
            <option value="Prophecy" <?= $category_filter=='Prophecy'?'selected':'' ?>>Prophecy</option>
            <option value="Wisdom" <?= $category_filter=='Wisdom'?'selected':'' ?>>Wisdom</option>
        </select>

        <label>Difficulty:</label>
        <select name="difficulty">
            <option value="">All</option>
            <option value="Easy" <?= $difficulty_filter=='Easy'?'selected':'' ?>>Easy</option>
            <option value="Medium" <?= $difficulty_filter=='Medium'?'selected':'' ?>>Medium</option>
            <option value="Hard" <?= $difficulty_filter=='Hard'?'selected':'' ?>>Hard</option>
        </select>

        <button type="submit">Start Quiz</button>
    </form>

    <!-- Quiz Form -->
    <form method="post">
        <?php if($questions_res->num_rows > 0): ?>
            <?php while($q = $questions_res->fetch_assoc()): ?>
                <div class="quiz-question">
                    <p><strong><?= esc($q['scripture_ref']) ?>:</strong> <?= esc($q['question']) ?></p>
                    <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="A" required> A. <?= esc($q['option_a']) ?></label><br>
                    <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="B"> B. <?= esc($q['option_b']) ?></label><br>
                    <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="C"> C. <?= esc($q['option_c']) ?></label><br>
                    <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="D"> D. <?= esc($q['option_d']) ?></label>
                </div>
                <hr>
            <?php endwhile; ?>
            <button type="submit">Submit Quiz</button>
        <?php else: ?>
            <p>No questions found for selected filters.</p>
        <?php endif; ?>
    </form>

    <p><a href="public/index.php">Back to Dashboard</a> | <a href="leaderboard.php">View Leaderboard</a></p>
</div>
</body>
</html>

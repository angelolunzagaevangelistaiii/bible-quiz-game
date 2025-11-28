<?php
// public/quiz.php
session_start();
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';

// Get user info from GET (start) OR session if already set (allow replays)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // starting the quiz
    $name = trim($_GET['name'] ?? '');
    $email = trim($_GET['email'] ?? '');
    $category = $_GET['category'] ?? '';
    $difficulty = $_GET['difficulty'] ?? '';
    $n = intval($_GET['n'] ?? 10);
    if ($n <= 0) $n = 10;

    // save in session for later (used for leaderboard insert)
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    $_SESSION['quiz_category'] = $category;
    $_SESSION['quiz_difficulty'] = $difficulty;
    $_SESSION['quiz_n'] = $n;
}

// on submit (POST): evaluate
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // require answers array
    $answers = $_POST['answers'] ?? [];
    $score = 0;
    // store each answer
    $stmtQ = $mysqli->prepare("SELECT correct FROM questions WHERE id = ?");
    $stmtInsert = $mysqli->prepare("INSERT INTO user_quiz_results (user_name, user_email, user_id, question_id, user_answer, is_correct) VALUES (?, ?, ?, ?, ?, ?)");

    $user_name = $_SESSION['user_name'] ?? 'Guest';
    $user_email = $_SESSION['user_email'] ?? '';
    $user_id = $_SESSION['user_id'] ?? null; // optional if you have site users
    foreach ($answers as $qid => $uans) {
        $qid = intval($qid);
        $uans = strtoupper(substr($uans,0,1));

        $stmtQ->bind_param("i", $qid);
        $stmtQ->execute();
        $res = $stmtQ->get_result()->fetch_assoc();
        $correct = $res ? strtoupper($res['correct']) : '';
        $is_correct = ($uans !== '' && $correct !== '' && $uans === $correct) ? 1 : 0;
        if ($is_correct) $score++;

        // insert per-question result
        $stmtInsert->bind_param("ssisis", $user_name, $user_email, $user_id, $qid, $uans, $is_correct);
        $stmtInsert->execute();
    }
    // save overall leaderboard entry
    $category = $_SESSION['quiz_category'] ?? '';
    $difficulty = $_SESSION['quiz_difficulty'] ?? '';
    $stmtL = $mysqli->prepare("INSERT INTO leaderboard (name, email, score, category, difficulty) VALUES (?, ?, ?, ?, ?)");
    $stmtL->bind_param("sisss", $user_name, $user_email, $score, $category, $difficulty);
    $stmtL->execute();

    // store score in session for result page
    $_SESSION['quiz_score'] = $score;
    $_SESSION['quiz_total'] = count($answers);
    header("Location: quiz_result.php");
    exit;
}

// For GET flow display: fetch questions using filters from session
$category = $_SESSION['quiz_category'] ?? '';
$difficulty = $_SESSION['quiz_difficulty'] ?? '';
$n = intval($_SESSION['quiz_n'] ?? 10);
if ($n <= 0) $n = 10;

$where = [];
$params = [];
$types = '';

if ($category !== '') {
    $where[] = "category = ?";
    $types .= 's';
    $params[] = $category;
}
if ($difficulty !== '') {
    $where[] = "difficulty = ?";
    $types .= 's';
    $params[] = $difficulty;
}

$sql = "SELECT id, scripture_ref, question, option_a, option_b, option_c, option_d FROM questions";
if (count($where)) $sql .= " WHERE " . implode(' AND ', $where);
$sql .= " ORDER BY RAND() LIMIT ?";

$stmt = $mysqli->prepare($sql);
if (count($params)) {
    // bind existing params + n
    $types_full = $types . 'i';
    $params_full = $params;
    $params_full[] = $n;
    $stmt->bind_param($types_full, ...$params_full);
} else {
    $stmt->bind_param('i', $n);
}
$stmt->execute();
$result = $stmt->get_result();

$questions = $result->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Quiz - Bible Quiz</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h2>Quiz: <?= esc($_SESSION['user_name'] ?? 'Guest') ?></h2>
  <p class="small">Category: <?= esc($category) ?> | Difficulty: <?= esc($difficulty) ?></p>

  <?php if (count($questions) === 0): ?>
    <p>No questions found for the selected filters. <a href="index.php">Go back</a>.</p>
  <?php else: ?>
  <form method="post" id="quizForm">
    <?php foreach($questions as $i => $q): ?>
      <div class="quiz-question">
        <p><strong>Q<?= $i+1 ?> — <?= esc($q['scripture_ref']) ?>:</strong> <?= esc($q['question']) ?></p>
        <div class="options">
          <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="A" required> A. <?= esc($q['option_a']) ?></label>
          <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="B"> B. <?= esc($q['option_b']) ?></label>
          <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="C"> C. <?= esc($q['option_c']) ?></label>
          <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="D"> D. <?= esc($q['option_d']) ?></label>
        </div>
      </div>
      <hr>
    <?php endforeach; ?>

    <button type="submit">Submit Quiz</button>
  </form>
  <?php endif; ?>

  <p style="margin-top:12px;"><a class="link" href="index.php">Change Options</a> | <a class="link" href="leaderboard.php">View Leaderboard</a></p>
</div>
</body>
</html>

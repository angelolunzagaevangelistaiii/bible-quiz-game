<?php
session_start();
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
requireLogin();
requireAdmin();

$id = intval($_GET['id']);
$res = $mysqli->query("SELECT * FROM questions WHERE id=$id");
$question = $res->fetch_assoc();
if(!$question) die("Question not found!");

$msg = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scripture = trim($_POST['scripture_ref']);
    $qtext = trim($_POST['question']);
    $a = trim($_POST['option_a']);
    $b = trim($_POST['option_b']);
    $c = trim($_POST['option_c']);
    $d = trim($_POST['option_d']);
    $correct = $_POST['correct'];
    $category = $_POST['category'];
    $difficulty = $_POST['difficulty'];

    if($scripture && $qtext && $a && $b && $c && $d && $correct && $category && $difficulty){
        $stmt = $mysqli->prepare("UPDATE questions SET scripture_ref=?, question=?, option_a=?, option_b=?, option_c=?, option_d=?, correct=?, category=?, difficulty=? WHERE id=?");
        $stmt->bind_param("sssssssssi", $scripture, $qtext, $a, $b, $c, $d, $correct, $category, $difficulty, $id);
        if($stmt->execute()) $msg = "Question updated successfully!";
        else $msg = "Error updating question!";
    } else $msg = "All fields are required!";
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Question</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="content">
    <h2>Edit Question #<?= $id ?></h2>
    <?php if($msg) echo "<p class='success'>$msg</p>"; ?>

    <form method="post">
        <input type="text" name="scripture_ref" value="<?= esc($question['scripture_ref']) ?>" required>
        <textarea name="question" required><?= esc($question['question']) ?></textarea>
        <input type="text" name="option_a" value="<?= esc($question['option_a']) ?>" required>
        <input type="text" name="option_b" value="<?= esc($question['option_b']) ?>" required>
        <input type="text" name="option_c" value="<?= esc($question['option_c']) ?>" required>
        <input type="text" name="option_d" value="<?= esc($question['option_d']) ?>" required>

        <label>Correct Answer:</label>
        <select name="correct" required>
            <option value="A" <?= $question['correct']=='A'?'selected':'' ?>>A</option>
            <option value="B" <?= $question['correct']=='B'?'selected':'' ?>>B</option>
            <option value="C" <?= $question['correct']=='C'?'selected':'' ?>>C</option>
            <option value="D" <?= $question['correct']=='D'?'selected':'' ?>>D</option>
        </select>

        <label>Category:</label>
        <select name="category" required>
            <option value="Faith" <?= $question['category']=='Faith'?'selected':'' ?>>Faith</option>
            <option value="Gospels" <?= $question['category']=='Gospels'?'selected':'' ?>>Gospels</option>
            <option value="Prophecy" <?= $question['category']=='Prophecy'?'selected':'' ?>>Prophecy</option>
            <option value="Wisdom" <?= $question['category']=='Wisdom'?'selected':'' ?>>Wisdom</option>
        </select>

        <label>Difficulty:</label>
        <select name="difficulty" required>
            <option value="Easy" <?= $question['difficulty']=='Easy'?'selected':'' ?>>Easy</option>
            <option value="Medium" <?= $question['difficulty']=='Medium'?'selected':'' ?>>Medium</option>
            <option value="Hard" <?= $question['difficulty']=='Hard'?'selected':'' ?>>Hard</option>
        </select>

        <button type="submit">Update Question</button>
    </form>

    <p><a href="index.php">Back to Admin Dashboard</a></p>
</div>
</body>
</html>

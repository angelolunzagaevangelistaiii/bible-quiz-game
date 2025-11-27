<?php
session_start();
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
requireLogin();
requireAdmin();

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scripture = trim($_POST['scripture_ref']);
    $question  = trim($_POST['question']);
    $a = trim($_POST['option_a']);
    $b = trim($_POST['option_b']);
    $c = trim($_POST['option_c']);
    $d = trim($_POST['option_d']);
    $correct = $_POST['correct'];
    $category = $_POST['category'];
    $difficulty = $_POST['difficulty'];

    if ($scripture && $question && $a && $b && $c && $d && $correct && $category && $difficulty) {
        $stmt = $mysqli->prepare("INSERT INTO questions (scripture_ref, question, option_a, option_b, option_c, option_d, correct, category, difficulty)
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $scripture, $question, $a, $b, $c, $d, $correct, $category, $difficulty);
        if($stmt->execute()) $msg = "Question added successfully!";
        else $msg = "Error adding question!";
    } else $msg = "All fields are required!";
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Add Question</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="content">
    <h2>Add New Question</h2>
    <?php if($msg) echo "<p class='success'>$msg</p>"; ?>

    <form method="post">
        <input type="text" name="scripture_ref" placeholder="Scripture Reference" required>
        <textarea name="question" placeholder="Question text" required></textarea>
        <input type="text" name="option_a" placeholder="Option A" required>
        <input type="text" name="option_b" placeholder="Option B" required>
        <input type="text" name="option_c" placeholder="Option C" required>
        <input type="text" name="option_d" placeholder="Option D" required>

        <label>Correct Answer:</label>
        <select name="correct" required>
            <option value="">Select Correct Option</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
        </select>

        <label>Category:</label>
        <select name="category" required>
            <option value="Faith">Faith</option>
            <option value="Gospels">Gospels</option>
            <option value="Prophecy">Prophecy</option>
            <option value="Wisdom">Wisdom</option>
        </select>

        <label>Difficulty:</label>
        <select name="difficulty" required>
            <option value="Easy">Easy</option>
            <option value="Medium">Medium</option>
            <option value="Hard">Hard</option>
        </select>

        <button type="submit">Add Question</button>
    </form>

    <p><a href="index.php">Back to Admin Dashboard</a></p>
</div>
</body>
</html>

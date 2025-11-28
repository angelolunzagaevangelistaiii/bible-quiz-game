<?php
session_start();
require "../config/db.php";
require "auth_check.php";

$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = $_POST['question'];
    $category = $_POST['category'];
    $difficulty = $_POST['difficulty'];
    $correct = $_POST['correct_answer'];
    $a = $_POST['option_a'];
    $b = $_POST['option_b'];
    $c = $_POST['option_c'];
    $d = $_POST['option_d'];

    $stmt = $conn->prepare("INSERT INTO questions (question, category, difficulty, correct_answer, option_a, option_b, option_c, option_d)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $question, $category, $difficulty, $correct, $a, $b, $c, $d);
    $stmt->execute();

    $success = "Question added successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Question</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "sidebar_topbar.php"; ?>

<div class="content">
    <h2>Add Question</h2>

    <?php if ($success): ?>
        <div class="success-box"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">

        <label>Question:</label>
        <textarea name="question" required></textarea>

        <label>Category:</label>
        <select name="category" required>
            <option value="Faith">Faith</option>
            <option value="Gospels">Gospels</option>
            <option value="Prophecy">Prophecy</option>
            <option value="Commandments">Commandments</option>
        </select>

        <label>Difficulty:</label>
        <select name="difficulty" required>
            <option value="Easy">Easy</option>
            <option value="Medium">Medium</option>
            <option value="Hard">Hard</option>
        </select>

        <label>Correct Answer:</label>
        <input type="text" name="correct_answer" required>

        <label>Option A:</label>
        <input type="text" name="option_a" required>

        <label>Option B:</label>
        <input type="text" name="option_b" required>

        <label>Option C:</label>
        <input type="text" name="option_c">

        <label>Option D:</label>
        <input type="text" name="option_d">

        <button type="submit">Add Question</button>
    </form>
</div>
</body>
</html>

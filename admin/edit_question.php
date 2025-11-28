<?php
session_start();
require "../config/db.php";
require "auth_check.php";

if (!isset($_GET['id'])) {
    header("Location: manage_questions.php");
    exit;
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM questions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$questionData = $stmt->get_result()->fetch_assoc();

if (!$questionData) {
    die("Question not found.");
}

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

    $update = $conn->prepare("UPDATE questions 
                              SET question=?, category=?, difficulty=?, correct_answer=?, 
                                  option_a=?, option_b=?, option_c=?, option_d=?
                              WHERE id=?");
    $update->bind_param("ssssssssi", $question, $category, $difficulty, $correct, $a, $b, $c, $d, $id);
    $update->execute();

    $success = "Question updated!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Question</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "sidebar_topbar.php"; ?>

<div class="content">
    <h2>Edit Question</h2>

    <?php if ($success): ?>
        <div class="success-box"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Question:</label>
        <textarea name="question" required><?= $questionData['question'] ?></textarea>

        <label>Category:</label>
        <select name="category">
            <option <?= $questionData['category']=="Faith"?"selected":"" ?>>Faith</option>
            <option <?= $questionData['category']=="Gospels"?"selected":"" ?>>Gospels</option>
            <option <?= $questionData['category']=="Prophecy"?"selected":"" ?>>Prophecy</option>
            <option <?= $questionData['category']=="Commandments"?"selected":"" ?>>Commandments</option>
        </select>

        <label>Difficulty:</label>
        <select name="difficulty">
            <option <?= $questionData['difficulty']=="Easy"?"selected":"" ?>>Easy</option>
            <option <?= $questionData['difficulty']=="Medium"?"selected":"" ?>>Medium</option>
            <option <?= $questionData['difficulty']=="Hard"?"selected":"" ?>>Hard</option>
        </select>

        <label>Correct Answer:</label>
        <input type="text" name="correct_answer" value="<?= $questionData['correct_answer'] ?>">

        <label>Option A:</label>
        <input type="text" name="option_a" value="<?= $questionData['option_a'] ?>">

        <label>Option B:</label>
        <input type="text" name="option_b" value="<?= $questionData['option_b'] ?>">

        <label>Option C:</label>
        <input type="text" name="option_c" value="<?= $questionData['option_c'] ?>">

        <label>Option D:</label>
        <input type="text" name="option_d" value="<?= $questionData['option_d'] ?>">

        <button type="submit">Save Changes</button>
    </form>

</div>
</body>
</html>

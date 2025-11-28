<?php
require_once "admin_protect.php";
require_once "../config/config.php";

if (!isset($_GET['id'])) { header("Location: manage_questions.php"); exit; }

$id = intval($_GET['id']);
$error = $success = '';

$stmt = $conn->prepare("SELECT * FROM questions WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$question = $result->fetch_assoc();
$stmt->close();

if (!$question) die("Question not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scripture_ref = trim($_POST['scripture_ref']);
    $q_text = trim($_POST['question']);
    $option_a = trim($_POST['option_a']);
    $option_b = trim($_POST['option_b']);
    $option_c = trim($_POST['option_c']);
    $option_d = trim($_POST['option_d']);
    $correct = trim($_POST['correct']);
    $category = trim($_POST['category']);
    $difficulty = trim($_POST['difficulty']);

    $stmt = $conn->prepare("UPDATE questions SET scripture_ref=?, question=?, option_a=?, option_b=?, option_c=?, option_d=?, correct=?, category=?, difficulty=? WHERE id=?");
    $stmt->bind_param("sssssssssi", $scripture_ref, $q_text, $option_a, $option_b, $option_c, $option_d, $correct, $category, $difficulty, $id);
    if ($stmt->execute()) $success = "Question updated successfully!";
    else $error = "Database error: " . $stmt->error;
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Question</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
<div class="container">
    <h2>Edit Question</h2>
    <?php if($success) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>Scripture Reference:</label><br>
        <input type="text" name="scripture_ref" value="<?= htmlspecialchars($question['scripture_ref']) ?>" required><br><br>

        <label>Question:</label><br>
        <textarea name="question" required><?= htmlspecialchars($question['question']) ?></textarea><br><br>

        <label>Option A:</label><br>
        <input type="text" name="option_a" value="<?= htmlspecialchars($question['option_a']) ?>" required><br><br>

        <label>Option B:</label><br>
        <input type="text" name="option_b" value="<?= htmlspecialchars($question['option_b']) ?>" required><br><br>

        <label>Option C:</label><br>
        <input type="text" name="option_c" value="<?= htmlspecialchars($question['option_c']) ?>" required><br><br>

        <label>Option D:</label><br>
        <input type="text" name="option_d" value="<?= htmlspecialchars($question['option_d']) ?>" required><br><br>

        <label>Correct Answer (A/B/C/D):</label><br>
        <input type="text" name="correct" maxlength="1" value="<?= htmlspecialchars($question['correct']) ?>" required><br><br>

        <label>Category:</label><br>
        <input type="text" name="category" value="<?= htmlspecialchars($question['category']) ?>" required><br><br>

        <label>Difficulty:</label><br>
        <input type="text" name="difficulty" value="<?= htmlspecialchars($question['difficulty']) ?>" required><br><br>

        <button type="submit">Update Question</button>
    </form>
    <p><a href="manage_questions.php">Back to Manage Questions</a></p>
</div>
</body>
</html>

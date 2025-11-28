<?php
require_once "admin_protect.php";
require_once "../config/config.php";

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scripture_ref = trim($_POST['scripture_ref']);
    $question = trim($_POST['question']);
    $option_a = trim($_POST['option_a']);
    $option_b = trim($_POST['option_b']);
    $option_c = trim($_POST['option_c']);
    $option_d = trim($_POST['option_d']);
    $correct = trim($_POST['correct']);
    $category = trim($_POST['category']);
    $difficulty = trim($_POST['difficulty']);

    if ($scripture_ref && $question && $option_a && $option_b && $option_c && $option_d && $correct && $category && $difficulty) {
        $stmt = $conn->prepare("INSERT INTO questions (scripture_ref, question, option_a, option_b, option_c, option_d, correct, category, difficulty) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $scripture_ref, $question, $option_a, $option_b, $option_c, $option_d, $correct, $category, $difficulty);
        if ($stmt->execute()) {
            $success = "Question added successfully!";
        } else {
            $error = "Database error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Question</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
<div class="container">
    <h2>Add New Question</h2>
    <?php if($success) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>Scripture Reference:</label><br>
        <input type="text" name="scripture_ref" required><br><br>

        <label>Question:</label><br>
        <textarea name="question" required></textarea><br><br>

        <label>Option A:</label><br>
        <input type="text" name="option_a" required><br><br>

        <label>Option B:</label><br>
        <input type="text" name="option_b" required><br><br>

        <label>Option C:</label><br>
        <input type="text" name="option_c" required><br><br>

        <label>Option D:</label><br>
        <input type="text" name="option_d" required><br><br>

        <label>Correct Answer (A/B/C/D):</label><br>
        <input type="text" name="correct" maxlength="1" required><br><br>

        <label>Category:</label><br>
        <input type="text" name="category" required><br><br>

        <label>Difficulty:</label><br>
        <input type="text" name="difficulty" required><br><br>

        <button type="submit">Add Question</button>
    </form>
    <p><a href="index.php">Back to Dashboard</a></p>
</div>
</body>
</html>

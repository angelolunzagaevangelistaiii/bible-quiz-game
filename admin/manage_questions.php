<?php
require_once "admin_protect.php";
require_once "../config/config.php";

$result = $conn->query("SELECT * FROM questions ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Questions</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
<div class="container">
    <h2>Manage Questions</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Scripture</th>
            <th>Question</th>
            <th>Category</th>
            <th>Difficulty</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['scripture_ref']) ?></td>
            <td><?= htmlspecialchars($row['question']) ?></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td><?= htmlspecialchars($row['difficulty']) ?></td>
            <td>
                <a href="edit_question.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="delete_question.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this question?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <p><a href="index.php">Back to Dashboard</a></p>
</div>
</body>
</html>

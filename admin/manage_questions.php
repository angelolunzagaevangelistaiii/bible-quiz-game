<?php
session_start();
require "../config/db.php";
require "auth_check.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Questions</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "sidebar_topbar.php"; ?>

<div class="content">
    <h2>Manage Questions</h2>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Question</th>
                <th>Category</th>
                <th>Difficulty</th>
                <th>Correct</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM questions ORDER BY id DESC");
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= htmlspecialchars($row['question']); ?></td>
                <td><?= htmlspecialchars($row['category']); ?></td>
                <td><?= htmlspecialchars($row['difficulty']); ?></td>
                <td><?= htmlspecialchars($row['correct_answer']); ?></td>
                <td>
                    <a href="edit_question.php?id=<?= $row['id']; ?>">Edit</a> |
                    <a href="delete_question.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>
</body>
</html>

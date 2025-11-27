<?php
session_start();
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
requireLogin();
requireAdmin();

// Filters
$where = [];
$category_filter = $_GET['category'] ?? '';
$difficulty_filter = $_GET['difficulty'] ?? '';

if($category_filter) $where[] = "category='" . $mysqli->real_escape_string($category_filter) . "'";
if($difficulty_filter) $where[] = "difficulty='" . $mysqli->real_escape_string($difficulty_filter) . "'";

$sql = "SELECT * FROM questions";
if(count($where)) $sql .= " WHERE " . implode(' AND ', $where);
$sql .= " ORDER BY id DESC";

$res = $mysqli->query($sql);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="content">
    <h2>Admin Dashboard</h2>

    <!-- Filters -->
    <form method="get">
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

        <button type="submit">Filter</button>
    </form>

    <p>
        <a href="add_question.php" class="btn btn-add">Add New Question</a>
        <a href="import_csv.php" class="btn btn-add">Import CSV</a>
        <a href="export_csv.php" class="btn btn-edit">Export CSV</a>
    </p>
	<p>CSV format:</p>
<p>scripture_ref,question,option_a,option_b,option_c,option_d,correct,category,difficulty
John 3:16,Who loved the world?,God,Jesus,Prophet,Angel,A,Faith,Easy
</p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Scripture</th>
                <th>Question</th>
                <th>Category</th>
                <th>Difficulty</th>
                <th>Correct</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $res->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= esc($row['scripture_ref']) ?></td>
                <td><?= esc($row['question']) ?></td>
                <td><?= esc($row['category']) ?></td>
                <td><?= esc($row['difficulty']) ?></td>
                <td><?= esc($row['correct']) ?></td>
                <td>
                    <a href="edit_question.php?id=<?= $row['id'] ?>" class="btn btn-edit">Edit</a>
                    <a href="delete_question.php?id=<?= $row['id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <p><a href="../public/index.php">Back to Dashboard</a></p>
</div>
</body>
</html>

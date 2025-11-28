<?php
// Make sure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require "auth_check.php";
?>
<div class="sidebar">
    <h2><?= htmlspecialchars($_SESSION['admin_name']); ?></h2>
    <a href="index.php">Dashboard</a>
    <a href="add_question.php">Add Question</a>
    <a href="manage_questions.php">Manage Questions</a>
    <a href="admin_logout.php">Logout</a>
</div>

<div class="topbar">Christian Quiz Admin</div>

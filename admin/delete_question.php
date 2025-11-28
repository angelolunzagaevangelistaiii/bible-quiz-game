<?php
session_start();
require "../config/db.php";
require "auth_check.php";

if (!isset($_GET['id'])) {
    header("Location: manage_questions.php");
    exit;
}

$id = intval($_GET['id']);

// Delete question
$stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: manage_questions.php");
exit;
?>

<?php
require_once "admin_protect.php";
require_once "../config/config.php";

if (!isset($_GET['id'])) { header("Location: manage_questions.php"); exit; }

$id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM questions WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: manage_questions.php");
exit;

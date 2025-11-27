<?php
session_start();
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
requireLogin();
requireAdmin();

$id = intval($_GET['id']);
$mysqli->query("DELETE FROM questions WHERE id=$id");

header("Location: index.php");
exit;
?>
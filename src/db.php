<?php
require_once __DIR__ . '/../config/config.php';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) {
    die("DB Connection failed: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");
?>
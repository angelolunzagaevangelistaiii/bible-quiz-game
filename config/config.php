<?php
$host = "localhost";
$user = "db_username";
$pass = "db_password";
$dbname = "christian_quiz_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

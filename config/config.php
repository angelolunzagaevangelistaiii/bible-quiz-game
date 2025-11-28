<?php
$host = "localhost";          // Your DB host
$user = "faith7qz_projects";       // Your DB username
$pass = "qwer1234qwer";   // Your DB password
$dbname = "faith7qz_bible_quiz_game"; // Your DB name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>

<?php
session_start();
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
requireLogin();
requireAdmin();

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="quiz_questions.csv"');

$output = fopen('php://output', 'w');
// CSV header
fputcsv($output, ['scripture_ref','question','option_a','option_b','option_c','option_d','correct']);

$result = $mysqli->query("SELECT scripture_ref, question, option_a, option_b, option_c, option_d, correct FROM questions");
while($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}
fclose($output);
exit;
?>
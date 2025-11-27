<?php
session_start();
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';

$action = $_REQUEST['action'] ?? '';
header('Content-Type: application/json');

if ($action === 'get_questions') {
    $n = intval($_GET['n'] ?? 10);
    if ($n <= 0) $n = 10;
    // fetch random questions; reveal 'correct' for client-side scoring (easy mode)
    $stmt = $mysqli->prepare("SELECT id, scripture_ref, question, option_a, option_b, option_c, option_d, correct FROM questions ORDER BY RAND() LIMIT ?");
    $stmt->bind_param("i",$n);
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = $res->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows);
    exit;
}

if ($action === 'submit_score' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status'=>'error','message'=>'Not logged']);
        exit;
    }
    $score = intval($_POST['score'] ?? 0);
    $total = intval($_POST['total'] ?? 0);
    $stmt = $mysqli->prepare("INSERT INTO scores (user_id, score, total_questions) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $_SESSION['user_id'], $score, $total);
    $stmt->execute();
    echo json_encode(['status'=>'ok']);
    exit;
}

echo json_encode(['status'=>'error','message'=>'Invalid action']);
exit;

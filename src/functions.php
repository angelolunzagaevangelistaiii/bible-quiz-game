<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function esc($s){
    return htmlspecialchars($s, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8');
}

function isAdmin() {
    return isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1;
}

function requireAdmin() {
    if (!isAdmin()) {
        die("Access denied. Admins only.");
    }
}
?>
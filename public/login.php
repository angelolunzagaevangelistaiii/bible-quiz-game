<?php
session_start();
require_once __DIR__ . '/../src/db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $mysqli->prepare("SELECT id,name,password FROM users WHERE email = ?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if ($res && password_verify($password, $res['password'])) {
        $_SESSION['user_id'] = $res['id'];
        $_SESSION['name'] = $res['name'];
        header("Location: index.php");
        exit;
    } else $error = "Invalid credentials.";
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login - Bible Quiz</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
  <h2>Login</h2>
  <?php if($error) echo "<p class='error'>".esc($error)."</p>"; ?>
  <form method="post">
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password" required>
    <button type="submit">Login</button>
  </form>
  <p>No account? <a href="register.php">Register</a></p>
</div>
</body>
</html>

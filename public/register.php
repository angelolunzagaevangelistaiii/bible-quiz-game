<?php
session_start();
require_once __DIR__ . '/../src/db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $error = "All fields are required.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("INSERT INTO users (name,email,password) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $name, $email, $hash);
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $mysqli->insert_id;
            $_SESSION['name'] = $name;
            header("Location: index.php");
            exit;
        } else {
            $error = "Email already exists or error.";
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Register - Bible Quiz</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
  <h2>Create account</h2>
  <?php if($error) echo "<p class='error'>".esc($error)."</p>"; ?>
  <form method="post">
    <input name="name" placeholder="Full name" required>
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password" required>
    <button type="submit">Register</button>
  </form>
  <p>Already have account? <a href="login.php">Login</a></p>
</div>
</body>
</html>

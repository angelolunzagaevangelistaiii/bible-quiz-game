<?php
session_start();
require_once __DIR__ . '/../src/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $mysqli->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $res = $mysqli->query("SELECT * FROM admin_users WHERE username='$username' LIMIT 1");

    if ($res->num_rows === 1) {
        $admin = $res->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Admin not found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
<div class="container">
    <h2>Admin Login</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p><a href="../public/index.php">Back to Site</a></p>
</div>
</body>
</html>

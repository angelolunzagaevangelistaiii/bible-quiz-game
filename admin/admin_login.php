<?php
session_start();
require_once __DIR__ . '/../src/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $mysqli->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $res = $mysqli->query("SELECT * FROM admin_users WHERE email='$email' LIMIT 1");

    if ($res->num_rows === 1) {
        $admin = $res->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            $_SESSION['admin_email'] = $admin['email'];

            header("Location: index.php");
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No admin found using that email.";
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
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p><a href="../public/index.php">Back to site</a></p>
</div>
</body>
</html>

<?php
require 'config.php';
require 'helpers.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Prepared statement → защита от SQL Injection
    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE username = ? LIMIT 1"
    );
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $user = $result->fetch_assoc()) {
        // Для учебного проекта: простой сравнение
        // (На защите скажи, что в проде нужно password_hash/password_verify)
        if ($password === $user['password']) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];

            header("Location: messages.php");
            exit;
        }
    }

    $error = "Invalid username or password";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login (Secure)</title>
</head>
<body>
    <h1>Admin Login (Secure)</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label>
            Username:<br>
            <input type="text" name="username" value="<?= e($_POST['username'] ?? '') ?>">
        </label><br><br>

        <label>
            Password:<br>
            <input type="password" name="password">
        </label><br><br>

        <button type="submit">Login</button>
    </form>

    <p><a href="index.php">Back to guestbook</a></p>
</body>
</html>

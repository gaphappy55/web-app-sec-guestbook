<?php
session_start();
include 'config.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Без фильтрации
    $username = $_POST['username'];
    $password = $_POST['password'];

    // УЯЗВИМО: SQL Injection
    $sql = "SELECT * FROM users
            WHERE username = '$username'
              AND password = '$password'
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header("Location: messages.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login (Vulnerable)</title>
</head>
<body>
    <h1>Admin Login (Vulnerable)</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label>
            Username:<br>
            <input type="text" name="username">
        </label><br><br>

        <label>
            Password:<br>
            <input type="password" name="password">
        </label><br><br>

        <!-- нет CSRF, нет защиты от перебора -->
        <button type="submit">Login</button>
    </form>

    <p>Hint: default admin / admin123 (stored in plain text)</p>

    <p><a href="index.php">Back to guestbook</a></p>
</body>
</html>

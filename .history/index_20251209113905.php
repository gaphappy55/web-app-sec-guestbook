<?php
require 'config.php';
require 'helpers.php';

$info = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $message  = trim($_POST['message'] ?? '');

    // Простая валидация
    if ($username === '') {
        $errors[] = "Name is required.";
    }
    if ($message === '') {
        $errors[] = "Message is required.";
    }

    if (empty($errors)) {
        // Prepared statement → защита от SQL Injection
        $stmt = $conn->prepare(
            "INSERT INTO messages (username, message, created_at)
             VALUES (?, ?, NOW())"
        );
        $stmt->bind_param("ss", $username, $message);
        $stmt->execute();
        $info = "Message saved securely!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guestbook - Add Message (Secure)</title>
</head>
<body>
    <h1>Guestbook (Secure Version)</h1>

    <?php if ($info): ?>
        <p style="color:green;"><?= e($info) ?></p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $err): ?>
                <li><?= e($err) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h2>Leave a message</h2>
    <form method="POST" action="index.php">
        <label>
            Name:<br>
            <input type="text" name="username" value="<?= e($_POST['username'] ?? '') ?>">
        </label><br><br>

        <label>
            Message:<br>
            <textarea name="message" rows="4" cols="40"><?= e($_POST['message'] ?? '') ?></textarea>
        </label><br><br>

        <button type="submit">Send</button>
    </form>

    <p>
        <a href="messages.php">View all messages</a> |
        <?php if (is_admin()): ?>
            Logged in as <b><?= e($_SESSION['username']) ?></b>
            (<a href="logout.php">Logout</a>)
        <?php else: ?>
            <a href="login.php">Admin login</a>
        <?php endif; ?>
    </p>
</body>
</html>

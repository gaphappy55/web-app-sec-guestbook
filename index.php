<?php
session_start();
include 'config.php';

$info = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // НИКАКОЙ фильтрации — XSS + SQLi в принципе возможен
    $username = $_POST['username'];
    $message  = $_POST['message'];

    // Вставка без prepared statements → SQL Injection
    $sql = "INSERT INTO messages (username, message, created_at)
            VALUES ('$username', '$message', NOW())";

    if (mysqli_query($conn, $sql)) {
        $info = "Message saved!";
    } else {
        $info = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guestbook - Add Message (Vulnerable)</title>
</head>
<body>
    <h1>Guestbook (Vulnerable)</h1>

    <?php if ($info): ?>
        <p style="color:green;"><?= $info ?></p>
    <?php endif; ?>

    <h2>Leave a message</h2>
    <form method="POST" action="index.php">
        <label>
            Name:<br>
            <input type="text" name="username">
        </label><br><br>

        <label>
            Message:<br>
            <textarea name="message" rows="4" cols="40"></textarea>
        </label><br><br>

        <!-- нет CSRF защиты -->
        <button type="submit">Send</button>
    </form>

    <p>
        <a href="messages.php">View all messages</a> |
        <a href="login.php">Admin login</a>
    </p>
</body>
</html>

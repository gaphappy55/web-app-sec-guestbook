<?php
session_start();
include 'config.php';

// Поиск без фильтрации → SQL Injection
$search = isset($_GET['search']) ? $_GET['search'] : "";

if ($search !== "") {
    $sql = "SELECT * FROM messages
            WHERE username LIKE '%$search%'
            ORDER BY created_at DESC";
} else {
    $sql = "SELECT * FROM messages ORDER BY created_at DESC";
}

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guestbook - Messages (Vulnerable)</title>
</head>
<body>
    <h1>Messages (Vulnerable)</h1>

    <form method="GET" action="messages.php">
        <label>
            Search by name:
            <input type="text" name="search" value="<?= $search ?>">
        </label>
        <button type="submit">Search</button>
    </form>

    <p>
        <a href="index.php">Add new message</a> |
        <?php if (!empty($_SESSION['username'])): ?>
            Logged in as <b><?= $_SESSION['username'] ?></b>
            (<a href="logout.php">Logout</a>)
        <?php else: ?>
            <a href="login.php">Admin login</a>
        <?php endif; ?>
    </p>

    <hr>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div style="margin-bottom: 15px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                <!-- XSS: вывод без htmlspecialchars -->
                <p><b>ID:</b> <?= $row['id'] ?></p>
                <p><b>Name:</b> <?= $row['username'] ?></p>
                <p><b>Message:</b><br><?= nl2br($row['message']) ?></p>
                <p><small><?= $row['created_at'] ?></small></p>

                <!-- IDOR + CSRF + SQLi через id -->
                <p>
                    <a href="delete.php?id=<?= $row['id'] ?>">Delete (no auth check!)</a>
                </p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No messages yet.</p>
    <?php endif; ?>

</body>
</html>

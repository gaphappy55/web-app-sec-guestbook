<?php
require 'config.php';
require 'helpers.php';

$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    // Prepared statement → защита от SQL Injection в поиске
    $like = '%' . $search . '%';
    $stmt = $conn->prepare(
        "SELECT * FROM messages
         WHERE username LIKE ?
         ORDER BY created_at DESC"
    );
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query(
        "SELECT * FROM messages ORDER BY created_at DESC"
    );
}

$csrf_token = get_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guestbook - Messages (Secure)</title>
</head>
<body>
    <h1>Messages (Secure Version)</h1>

    <form method="GET" action="messages.php">
        <label>
            Search by name:
            <input type="text" name="search" value="<?= e($search) ?>">
        </label>
        <button type="submit">Search</button>
    </form>

    <p>
        <a href="index.php">Add new message</a> |
        <?php if (is_admin()): ?>
            Logged in as <b><?= e($_SESSION['username']) ?></b>
            (<a href="logout.php">Logout</a>)
        <?php else: ?>
            <a href="login.php">Admin login</a>
        <?php endif; ?>
    </p>

    <hr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div style="margin-bottom: 15px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                <p><b>ID:</b> <?= (int)$row['id'] ?></p>

                <!-- ЭКРАНИРУЕМ ВСЁ, ЧТО ПРИШЛО ОТ ПОЛЬЗОВАТЕЛЯ -->
                <p><b>Name:</b> <?= e($row['username']) ?></p>
                <p><b>Message:</b><br><?= nl2br(e($row['message'])) ?></p>
                <p><small><?= e($row['created_at']) ?></small></p>

                <?php if (is_admin()): ?>
                    <!-- Delete только для админа + CSRF + POST -->
                    <form method="POST" action="delete.php" style="margin-top:10px;">
                        <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
                        <button type="submit">Delete</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No messages yet.</p>
    <?php endif; ?>

</body>
</html>

<?php
session_start();
include 'config.php';

// НЕТ проверки, залогинен ли пользователь
// НЕТ CSRF токена
// НЕТ валидации id
if (!isset($_GET['id'])) {
    die("No id provided");
}

$id = $_GET['id'];

// УЯЗВИМО: SQL Injection через id, например ?id=1 OR 1=1
$sql = "DELETE FROM messages WHERE id = $id";

mysqli_query($conn, $sql);

// Никакой обработки ошибок и логирования
header("Location: messages.php");
exit;

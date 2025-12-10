<?php
require 'config.php';
require 'helpers.php';

// Только админ может удалять сообщения
require_admin();

// Разрешаем только POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "405 Method Not Allowed";
    exit;
}

// Проверяем CSRF токен
$token = $_POST['csrf_token'] ?? null;
if (!check_csrf_token($token)) {
    http_response_code(400);
    echo "Bad request: invalid CSRF token.";
    exit;
}

// Валидируем id
$id = $_POST['id'] ?? '';
if (!ctype_digit($id)) {
    http_response_code(400);
    echo "Bad request: invalid id.";
    exit;
}

$intId = (int)$id;

// Prepared statement → защита от SQL Injection
$stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
$stmt->bind_param("i", $intId);
$stmt->execute();

header("Location: messages.php");
exit;

<?php
session_start();

/**
 * Экранирование вывода (защита от XSS)
 */
function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Проверка: админ ли пользователь
 */
function is_admin(): bool {
    return !empty($_SESSION['username']) && $_SESSION['username'] === 'admin';
}

/**
 * Требовать роль админа (иначе 403)
 */
function require_admin(): void {
    if (!is_admin()) {
        http_response_code(403);
        echo "403 Forbidden: Admin only.";
        exit;
    }
}

/**
 * Получить CSRF-токен (генерируем, если нет)
 */
function get_csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Проверка CSRF-токена
 */
function check_csrf_token(?string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
}

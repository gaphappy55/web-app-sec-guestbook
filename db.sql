-- Создание базы
CREATE DATABASE IF NOT EXISTS guestbook_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE guestbook_db;

-- Таблица пользователей (слабая аутентификация)
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Уязвимый админ: пароль в открытом виде
INSERT INTO users (username, password)
VALUES ('admin', 'admin123');

-- Таблица сообщений гостевой книги
DROP TABLE IF EXISTS messages;
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME NOT NULL
);

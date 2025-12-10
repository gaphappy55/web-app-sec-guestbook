<?php
// Простое небезопасное подключение к БД
$host = "localhost";
$user = "root";     // по умолчанию в XAMPP
$pass = "";         // пустой пароль
$dbname = "guestbook_db";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

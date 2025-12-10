<?php
$host = "localhost";
$user = "root";
$pass = ""; // по умолчанию в XAMPP
$dbname = "guestbook_db";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Включим отчёты об ошибках (удобно для отладки)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>

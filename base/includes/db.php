<?php
$host = "127.127.126.50";
$dbname = "gamestore_db";
$username = "root";
$password = "";

// Создаем подключение
$conn = new mysqli($host, $username, $password, $dbname);

// Проверяем подключение
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>

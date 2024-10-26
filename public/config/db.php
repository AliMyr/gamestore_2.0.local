<?php
$servername = "127.127.126.50";
$username = "root";
$password = "";
$dbname = "game_store";

// Создаем соединение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>
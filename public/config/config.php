<?php
// Параметры для подключения к базе данных
$host = '127.127.126.50';
$dbname = 'gamestore';
$user = 'root';  // Укажите своё имя пользователя базы данных
$pass = '';      // Укажите свой пароль для базы данных

try {
    // Подключение к базе данных через PDO
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Если возникает ошибка подключения
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
    exit();
}
?>
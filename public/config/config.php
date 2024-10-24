<?php
$host = '127.127.126.50';  // Имя хоста
$dbname = 'gamestore_db';  // Имя базы данных
$username = 'root';  // Имя пользователя MySQL (обычно root)
$password = '';  // Пароль (оставь пустым, если не установлен)

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Ошибка подключения: ' . $e->getMessage();
}
?>

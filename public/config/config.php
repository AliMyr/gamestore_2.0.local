<?php
// Настройки подключения к базе данных
$host = 'localhost';
$db   = 'gamestore_v2';  // Имя базы данных, которую мы только что создали
$user = 'root';          // Имя пользователя базы данных (обычно root на локальном сервере)
$pass = '';              // Пароль, если он есть. Обычно на локалке пустой
$charset = 'utf8mb4';

// Настройка DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Режим ошибок - выбрасывать исключения
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Режим выборки данных - ассоциативный массив
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Отключаем эмуляцию подготовленных запросов для безопасности
];

try {
    $db = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>

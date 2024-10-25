<?php
session_start();  // Стартуем сессию
// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');  // Перенаправляем на страницу входа, если не авторизован
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админка - Магазин игр</title>
    <link rel="stylesheet" href="/path/to/your/css/admin-styles.css">  <!-- Путь к стилям для админки -->
</head>
<body>
<header>
    <h1>Панель администратора</h1>
    <nav>
        <ul>
            <li><a href="/admin/dashboard.php">Главная</a></li>
            <li><a href="/admin/manage_games.php">Управление играми</a></li>
            <li><a href="/admin/manage_orders.php">Управление заказами</a></li>
            <li><a href="/admin/manage_users.php">Управление пользователями</a></li>
            <li><a href="/admin/logout.php">Выход</a></li>
        </ul>
    </nav>
</header>

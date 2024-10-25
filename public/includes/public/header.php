<?php
session_start();  // Убедись, что сессия стартует на всех страницах!
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин игр</title>
    <link rel="stylesheet" href="/path/to/your/css/styles.css">  <!-- Путь к стилям -->
</head>
<body>
<header>
    <h1>Магазин игр</h1>
    <nav>
        <ul>
            <li><a href="/public/index.php">Главная</a></li>
            <li><a href="/public/games.php">Игры</a></li>
            <li><a href="/public/cart.php">Корзина</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="/public/profile.php">Профиль</a></li>
                <li><a href="/public/logout.php">Выход</a></li>
            <?php else: ?>
                <li><a href="/public/login.php">Вход</a></li>
                <li><a href="/public/register.php">Регистрация</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

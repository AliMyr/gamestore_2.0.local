<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин игр</title>
    <link rel="stylesheet" href="/css/public-styles.css">  <!-- Подключаем стили для пользователей -->
</head>
<body>
<header>
    <h1>Магазин игр</h1>
    <nav>
        <ul>
            <li><a href="index.php">Главная</a></li>
            <li><a href="games.php">Игры</a></li>
            <li><a href="cart.php">Корзина</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="profile.php">Профиль</a></li>
                <li><a href="logout.php">Выход</a></li>
            <?php else: ?>
                <li><a href="login.php">Вход</a></li>
                <li><a href="register.php">Регистрация</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

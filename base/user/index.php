<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добро пожаловать в GameStore</title>
    <link rel="stylesheet" href="https://gamestore.local/css/style.css">
</head>
<body>

<?php
// Подключение шаблонов
include_once "../includes/header.php";
include_once "../includes/navbar.php";
?>

<div class="welcome-container">
    <h1>Добро пожаловать в GameStore</h1>
    <p>Здесь вы можете найти игры и программы для покупки и загрузки. Ознакомьтесь с нашим каталогом и выберите что-то для себя!</p>

    <div class="button-container">
        <a href="catalog.php" class="main-button">Перейти в каталог</a>
        <a href="profile.php" class="main-button">Мой профиль</a>
    </div>
</div>

<?php
// Подключение подвала
include_once "../includes/footer.php";
?>

</body>
</html>

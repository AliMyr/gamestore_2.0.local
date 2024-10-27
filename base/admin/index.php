<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Административная панель GameStore</title>
    <link rel="stylesheet" href="https://gamestore.local/css/style.css">
</head>
<body>

<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php"); // Перенаправление на страницу входа для администратора
    exit;
}

// Подключение шаблонов
include_once "../includes/header.php";
include_once "../includes/admin_navbar.php";
?>

<div class="admin-welcome-container">
    <h1>Административная панель GameStore</h1>
    <p>Здесь вы можете управлять играми, пользователями и заказами.</p>

    <div class="admin-button-container">
        <a href="manage_games.php" class="admin-button">Управление играми</a>
        <a href="manage_users.php" class="admin-button">Управление пользователями</a>
        <a href="manage_reviews.php" class="admin-button">Управление отзывами</a>
    </div>
</div>

<?php
// Подключение подвала
include_once "../includes/footer.php";
?>

</body>
</html>

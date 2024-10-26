<?php
include '../../config/db.php';
session_start();

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
    die("Пожалуйста, войдите в систему, чтобы видеть ваш профиль.");
}
?>

<h1>Личный кабинет</h1>
<a href="library.php">Моя библиотека</a> | <a href="orders.php">Мои заказы</a>
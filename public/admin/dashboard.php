<?php
session_start();
// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');  // Перенаправляем на страницу входа, если не авторизован
    exit();
}

include '../includes/admin/header.php';  // Подключаем шапку для админки
?>

<h1>Панель администратора</h1>
<p>Добро пожаловать в административную панель. Здесь вы можете управлять играми и заказами.</p>

<ul>
    <li><a href="manage_games.php">Управление играми</a></li>
    <li><a href="manage_orders.php">Управление заказами</a></li>
</ul>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал для админки
?>

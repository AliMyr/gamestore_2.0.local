<?php
session_start();

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

include '../includes/admin/header.php';  // Подключаем шапку для админки
?>

<h1>Панель администратора</h1>

<nav>
    <ul>
        <li><a href="manage_games.php">Управление играми</a></li>
        <li><a href="manage_orders.php">Управление заказами</a></li>
        <li><a href="manage_users.php">Управление пользователями</a></li>
        <li><a href="reports.php">Отчеты</a></li>  <!-- Добавили ссылку на страницу отчетов -->
    </ul>
</nav>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал для админки
?>

<?php
include 'includes/header.php';
session_start();

// Проверка авторизации администратора
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    die("Доступ запрещен. Только администраторы могут видеть эту страницу.");
}
?>

<h1>Административная панель Game Store</h1>
<a href="pages/add_game.php">Добавить игру</a> | <a href="pages/manage_games.php">Управление играми</a> | <a href="pages/manage_orders.php">Управление заказами</a>

<?php
include 'includes/footer.php';
?>
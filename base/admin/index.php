<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php"); // Перенаправление на страницу входа для администратора
    exit;
}
?>
<?php
// Подключение шаблонов
include_once "../includes/header.php";
include_once "../includes/admin_navbar.php";
?>

<h1>Административная панель GameStore</h1>
<p>Здесь вы можете управлять играми, пользователями и заказами.</p>

<?php
// Подключение подвала
include_once "../includes/footer.php";
?>

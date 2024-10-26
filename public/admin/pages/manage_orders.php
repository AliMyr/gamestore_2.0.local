<?php
include '../../config/db.php';
include '../includes/header.php';
session_start();

// Проверка авторизации администратора
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    die("Доступ запрещен. Только администраторы могут управлять заказами.");
}

// Отображение списка заказов
$sql = "SELECT orders.id, orders.order_date, users.username, games.title FROM orders 
        JOIN users ON orders.user_id = users.id 
        JOIN games ON orders.game_id = games.id";
$result = $conn->query($sql);
?>

<h1>Управление заказами</h1>

<table border="1">
    <tr>
        <th>ID заказа</th>
        <th>Дата заказа</th>
        <th>Пользователь</th>
        <th>Игра</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['order_date']; ?></td>
        <td><?php echo $row['username']; ?></td>
        <td><?php echo $row['title']; ?></td>
    </tr>
    <?php } ?>
</table>
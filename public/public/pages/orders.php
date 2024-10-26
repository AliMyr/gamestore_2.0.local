<?php
include '../../config/db.php';
session_start();

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
    die("Пожалуйста, войдите в систему, чтобы просмотреть ваши заказы.");
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT orders.id, orders.order_date, games.title, games.price FROM orders 
        JOIN games ON orders.game_id = games.id 
        WHERE orders.user_id = '$user_id'";
$result = $conn->query($sql);
?>

<h1>Мои заказы</h1>

<table border="1">
    <tr>
        <th>ID заказа</th>
        <th>Дата заказа</th>
        <th>Игра</th>
        <th>Цена</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['order_date']; ?></td>
        <td><?php echo $row['title']; ?></td>
        <td><?php echo $row['price']; ?> KZT</td>
    </tr>
    <?php } ?>
</table>
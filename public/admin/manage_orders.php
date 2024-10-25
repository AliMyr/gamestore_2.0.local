<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');  // Перенаправляем на страницу входа, если не авторизован
    exit();
}

// Получаем все заказы
$stmt = $db->prepare("SELECT * FROM orders");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/admin/header.php';  // Подключаем шапку для админки
?>

<h1>Управление заказами</h1>

<table>
    <tr>
        <th>ID заказа</th>
        <th>ID пользователя</th>
        <th>Общая сумма</th>
        <th>Статус</th>
        <th>Дата создания</th>
        <th>Действия</th>
    </tr>
    <?php foreach ($orders as $order): ?>
        <tr>
            <td><?php echo $order['id']; ?></td>
            <td><?php echo $order['user_id']; ?></td>
            <td><?php echo $order['total_price']; ?> тенге</td>
            <td><?php echo htmlspecialchars($order['status']); ?></td>
            <td><?php echo $order['created_at']; ?></td>
            <td>
                <a href="view_order.php?id=<?php echo $order['id']; ?>">Просмотр</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал для админки
?>
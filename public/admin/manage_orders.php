<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}


// Получаем список всех заказов
$stmt = $db->query("SELECT orders.*, users.username FROM orders LEFT JOIN users ON orders.user_id = users.id ORDER BY orders.created_at DESC");
$orders = $stmt->fetchAll();

include '../includes/admin/header.php';  // Подключаем шапку для админки
?>

<h1>Управление заказами</h1>

<?php if (count($orders) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Заказ</th>
                <th>Пользователь</th>
                <th>Общая стоимость</th>
                <th>Статус</th>
                <th>Дата заказа</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                    <td><?php echo htmlspecialchars($order['username']); ?></td>
                    <td><?php echo htmlspecialchars($order['total_price']); ?> тенге</td>
                    <td><?php echo htmlspecialchars($order['status']); ?></td>
                    <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                    <td>
                        <a href="view_order.php?id=<?php echo $order['id']; ?>">Просмотр</a> |
                        <a href="change_status.php?id=<?php echo $order['id']; ?>">Изменить статус</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Заказов пока нет.</p>
<?php endif; ?>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал для админки
?>

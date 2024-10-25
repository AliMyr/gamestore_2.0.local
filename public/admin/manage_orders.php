<?php
session_start();

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

include '../config/config.php';  // Подключение к базе данных

// Логика изменения статуса заказа
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);

    header('Location: manage_orders.php');  // Перенаправляем на ту же страницу после обновления статуса
    exit();
}

// Получаем список всех заказов
$stmt = $db->query("SELECT orders.*, users.username FROM orders LEFT JOIN users ON orders.user_id = users.id ORDER BY orders.created_at DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/admin/header.php';  // Подключаем шапку админки
?>

<h1>Управление заказами</h1>

<table>
    <thead>
        <tr>
            <th>ID Заказа</th>
            <th>Пользователь</th>
            <th>Общая стоимость</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo htmlspecialchars($order['id']); ?></td>
                <td><?php echo htmlspecialchars($order['username'] ? $order['username'] : 'Гость'); ?></td>
                <td><?php echo htmlspecialchars($order['total_price']); ?> тенге</td>
                <td><?php echo htmlspecialchars($order['status']); ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <select name="status">
                            <option value="new" <?php if ($order['status'] === 'new') echo 'selected'; ?>>Новый</option>
                            <option value="in_progress" <?php if ($order['status'] === 'in_progress') echo 'selected'; ?>>В обработке</option>
                            <option value="completed" <?php if ($order['status'] === 'completed') echo 'selected'; ?>>Завершён</option>
                            <option value="cancelled" <?php if ($order['status'] === 'cancelled') echo 'selected'; ?>>Отменён</option>
                        </select>
                        <button type="submit" name="update_status">Обновить статус</button>
                    </form>
                    <a href="view_order.php?id=<?php echo $order['id']; ?>">Просмотреть</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал админки
?>
